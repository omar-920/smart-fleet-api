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

    public function acceptOrder($orderId , Request $request )
    {   try {
            $driver = $request->user()->driver;
        if (!$driver || $driver->is_active == 0) {
            return response()->json(['message' => 'غير مصرح لك بقبول طلبات حالياً.'], 403);
        }
            $order = $this->orderService->acceptOrder($orderId, $driver->id);
            return response()->json([
                'message' => 'مبروك! تم استلام الطلب بنجاح وهو الآن قيد التنفيذ.',
                'data'    => $order
            ]);
        } catch (\Exception $e) {
        return response()->json([
        'message' => $e->getMessage()
        ], 400);
        }
    }

    public function deliverOrder($orderId , Request $request )
    {   try {

        $driver = $request->user()->driver;
        $request->validate([
            'proof_of_delivery' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $order = $this->orderService->deliverOrder($orderId, $driver->id , $request->file('proof_of_delivery'));

        return response()->json([
            'message' => 'تم تسليم الاوردر عاش يا بطل !!!',
            'data'    => $order
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
        'file' => $e->getFile()
        ], 400);
    }
    }

}
