<?php

namespace App\Http\Controllers;


use App\Classes\Helpers\Cart;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Customer;


class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        list($products, $cartItems) = Cart::getProductsAndCartItems();

        $line_items = [];
        $totalPrice = 0;
        $collectionItems = new Collection();
        foreach ($products as $product) {
            $totalPrice += $product->price * $cartItems[$product->id]['quantity'];
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $cartItems[$product->id]['quantity'],
            ];
            $item = new OrderItem();
            $item->product_id = $product->id;
            $item->unit_price = $product->price;
            $item->quantity = $cartItems[$product->id]['quantity'];
            $collectionItems->push($item);
        }

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $session = $stripe->checkout->sessions->create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $order = Order::firstOrCreate([
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);


        $payment = Payment::firstOrCreate([
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $session->id
        ]);

        foreach ($collectionItems as $item) {
            $item->order_id = $order->id;
            $item->save();
        }

        CartItem::where(['user_id' => $user->id])->delete();

        return redirect($session->url);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
//                        'images' => [$product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();


        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        try {
            $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
            if (!$session) {
                return view('checkout.failure');
            }
            $payment = Payment::query()->where(['session_id' => $session->id])->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])->first();

            if (is_null($payment)) {
                return view('checkout.failure', ['message' => "Invalid session ID."]);
            }
            if ($payment->status === PaymentStatus::Pending) {
                $this->updateStatus($payment);
            }
            $customer = $stripe->customers->retrieve($session->customer);

            return view('checkout.success')->with(['customer' => $customer]);
        } catch (\Throwable $e) {
            Log::error('Payment:error', ['message' => $e->getMessage()]);
            return view('checkout.failure', ['message' => "Payment doesn't exist"]);
        }


    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => "Payment doesn't exist"]);
    }

    public function checkoutUnpaid(Order $order, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        foreach ($order->items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                        'images' => [$item->product->image]
                    ],
                    'unit_amount' => $item->product->price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $session = $stripe->checkout->sessions->create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);
        $order->payment->session_id = $session->id;
        $order->payment->save();

        return redirect($session->url);
    }

    public function webhook()
    {
        // stripe listen --forward-to  192.168.56.10/webhook/stripe
        //command for test from VM: stripe trigger payment_intent.succeeded
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $endpoint_secret = env('STRIPE_SECRET_HOOK');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 401);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
            exit();
        }

// Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $payment = Payment::query()->where(['session_id' => $paymentIntent['id'], 'status' => [PaymentStatus::Pending]])->first();
                if ($payment) {
                    $this->updateStatus($payment);
                }
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }

    private function updateStatus($payment)
    {
        $payment->status = PaymentStatus::Paid;
        $payment->update();
        $order = $payment->order;
        $order->status = OrderStatus::Paid;
        $order->update();
        $adminUsers = User::where('is_admin', 1)->get();
        foreach ([...$adminUsers, $order->user] as $user) {
            Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        }
    }

}