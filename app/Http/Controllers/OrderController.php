<?php

namespace App\Http\Controllers;

use App\DTOs\CreateOrderDTO;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {}

    public function store(OrderStoreRequest $request)
    {
        $dto = CreateOrderDTO::fromRequest($request);

        $order = $this->orderService->createOrder($dto);
        return response()->json([
            'message' => 'تم إنشاء الطلب بنجاح',
            'order'   => $order
        ], 201); // 201 تعني Created
    }

    function index(Request $request)
    {
        $filters = $request->only(['status','customer_phone']);
        $orders = $this->orderService->getShopOrders($request->user()->id , $filters);
        return response()->json([
            'message'=> 'This is orders',
            'orders:'=> $orders
            ],200);
    }
}
