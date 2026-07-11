<?php

namespace App\Jobs;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotifyDriverNewOrder implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    public $order;
    public $driverIds;

    public function __construct(Order $order ,array $driverIds)
    {
        $this->order = $order;
        $this->driverIds = $driverIds;
    }



    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. لو مفيش سواقين قريبين، مفيش داعي نكمل
        if (empty($this->driverIds)) {
            return;
        }

        // 2. الفلترة هنا هي السحر: هنجيب توكنز السواقين القريبين بس
        // (ملحوظة: لو الـ ID اللي متسجل في Redis هو user_id، غير كلمة 'id' لـ 'user_id' حسب الداتا بيز بتاعتك)
        $tokens = Driver::whereIn('user_id', $this->driverIds)
            ->where('is_active', 1)
            ->whereNotNull('device_token')
            ->pluck('device_token')
            ->toArray();

        // 3. لو السواقين القريبين معندهمش توكنز (مثلاً قافلين نت)، نوقف التنفيذ
        if (empty($tokens)) {
            return;
        }

        try {
            $messaging = Firebase::messaging();
            $title = 'طلب جديد متاح! 🚀';
            $body = 'يوجد طلب جديد بالقرب من موقعك، افتح التطبيق للتفاصيل.';

            $message = CloudMessage::new()
                ->withNotification(\Kreait\Firebase\Messaging\Notification::create($title, $body))
                ->withData([
                    'order_id' => (string) $this->order->id,
                    'type' => 'new_order'
                ]);

            $chunks = array_chunk($tokens, 300);

            foreach ($chunks as $chunk) {
                $messaging->sendMulticast($message, $chunk);
            }

        } catch (\Exception $e) {
            Log::error('Firebase Multicast Error: ' . $e->getMessage());
        }
    }
}
