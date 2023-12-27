<?php

namespace App\Http\Controllers\api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Order::query()
            ->where('id', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return OrderListResource::collection($query);
    }

    public function view(Order $order)
    {
        return new OrderResource($order);
    }

    public function changeStatus(Order $order, $status)
    {
        $order->status = $status;
        $order->save();

//        Mail::to($order->user)->send(new OrderUpdateEmail($order));

        return response('', 200);
    }

    public function getStatuses()
    {
        return OrderStatus::getStatuses();
    }
}
