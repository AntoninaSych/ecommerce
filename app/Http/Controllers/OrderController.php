<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $orders = Order::query()
            ->withCount('items')
            ->where(['created_by' => $user->id])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('order.index')->with(['orders' => $orders]);
    }

    public function show(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = \request()->user();
        if ($order->created_by !== $user->id) {
            return response("You don't have permission to view this order", 403);
        }


        return view('order.view')->with(['order' => $order]);
    }

    public function view(Request $request, Order $order)
    {
        $user = $request->user();
        if ($order->created_by !== $user->id) {
            return response("You don't have permission to view this order", 403);
        }
        return view('order.view')->with(['order' => $order]);
    }


}
