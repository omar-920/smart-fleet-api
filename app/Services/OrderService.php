<?php

namespace App\Services;

use App\DTOs\CreateOrderDTO;
use App\Models\Order;


class OrderService
{
    public function createOrder(CreateOrderDTO $dto): Order
    {
        $order = Order::create([
            'shop_id'          => $dto->shop_id,
            'pickup_location'  => $dto->pickup_location,
            'dropoff_location' => $dto->dropoff_location,
            'customer_phone'   => $dto->customer_phone,
            'status'           => 'pending',
            'delivery_fee'     => 0,
        ]);
        return $order;
    }

    public function getShopOrders(int $shopId, array $filters = [])
    {
        return Order::where('shop_id', $shopId)
            // فلترة بحالة الطلب لو تم إرسالها (مثلاً: pending)
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            // فلترة برقم هاتف العميل لو تم إرساله
            ->when(isset($filters['customer_phone']), function ($query) use ($filters) {
                // استخدمنا like للبحث المرن عن أي جزء من الرقم
                $query->where('customer_phone', 'like', '%' . $filters['customer_phone'] . '%');
            })
            // ترتيب الطلبات من الأحدث للأقدم
            ->latest()
            // تقسيم النتائج لصفحات (10 طلبات في كل صفحة)
            ->paginate(10); 
    }


}