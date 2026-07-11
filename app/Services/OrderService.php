<?php

namespace App\Services;

use App\DTOs\CreateOrderDTO;
use App\Jobs\NotifyDriverNewOrder;
use App\Jobs\NotifyShopOrderDeliverd;
use App\Models\Order;
use App\Notifications\OrderAssigned;
use Illuminate\Support\Facades\Cache;



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
        Cache::tags(["shop_{$order->shop_id}_orders"])->flush();

        NotifyDriverNewOrder::dispatch($order);

        return $order;
    }

//    public function getShopOrders(int $shopId, array $filters = [])
//    {
//        return Order::where('shop_id', $shopId)
//            // فلترة بحالة الطلب لو تم إرسالها (مثلاً: pending)
//            ->when(isset($filters['status']), function ($query) use ($filters) {
//                $query->where('status', $filters['status']);
//            })
//            // فلترة برقم هاتف العميل لو تم إرساله
//            ->when(isset($filters['customer_phone']), function ($query) use ($filters) {
//                // استخدمنا like للبحث المرن عن أي جزء من الرقم
//                $query->where('customer_phone', 'like', '%' . $filters['customer_phone'] . '%');
//            })
//            // ترتيب الطلبات من الأحدث للأقدم
//            ->latest()
//            // تقسيم النتائج لصفحات (10 طلبات في كل صفحة)
//            ->paginate(10);
//    }

    public function getShopOrders(int $shopId, array $filters = [])
    {
        $page = request('page', 1);
        $filter_hash = md5(json_encode($filters));
        $cache_key = "shop_{$shopId}_orders_{$filter_hash}_page_{$page}";
        return Cache::tags(["shop_{$shopId}_orders"])->remember($cache_key , 60 , function () use ($shopId, $filters) {
            return Order::with('driver')->where('shop_id', $shopId)
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
                ->paginate(10)
                ->toArray();
        });
    }


    public function acceptOrder($orderId,$driver_id)
    {
        $updated = Order::where('id',$orderId)->whereNull('driver_id')->where('status' , 'pending')->update([
            'driver_id' => $driver_id,
            'status'   => 'in_progress'
        ]);

        if ($updated === 0) {
            throw new \Exception('عفواً، الطلب غير متاح أو تم استلامه من سائق آخر بالفعل.');
        }

        $order = Order::with('driver')->find($orderId);
        if ($order->driver) {
            $order->driver->notify(new OrderAssigned($order));
        }

        // 4. الضربة القاضية: مسح كاش المتجر عشان يشوف الحالة الجديدة فوراً
        Cache::tags(["shop_{$order->shop_id}_orders"])->flush();

        return $order;

    }

    public function deliverOrder($orderId,$driver_id, $proofOfDeliveryFile = null)
    {

        $file_path  = null;
        if ($proofOfDeliveryFile) {
            $file_path = $proofOfDeliveryFile->store('proofs', 's3');
        }

        $updated = Order::where('id', $orderId)->where('driver_id' , $driver_id)->where('status' , 'in_progress')->update([
            'status'   => 'delivered',
            'proof_of_delivery' => $file_path,
        ]);
        if ($updated === 0) {
            throw new \Exception('Error please try again');
        }
        $order = Order::with('shop')->find($orderId);

        // إرسال الإشعار للمتجر (هيروح للـ Queue تلقائياً)
        $order->shop->notify(new \App\Notifications\ShopOrderDeliveredNotification($order));

        return $order;
    }

}
