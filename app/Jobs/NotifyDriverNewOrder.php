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

    public function __construct(Order $order )
    {
        $this->order = $order;
    }



    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $tokens = Driver::whereNotNull('device_token')->pluck('device_token')->toArray();
        if (empty($tokens))
        {
            return;
        }

        try {


            $messaging = Firebase::messaging();
            $title = 'طلب جديد متاح! 🚀';
            $body = 'يوجد طلب جديد من المتجر، افتح التطبيق للتفاصيل.';


            $message = CloudMessage::new()
                ->withNotification(\Kreait\Firebase\Messaging\Notification::create($title, $body))
                ->withData([
                    'order_id' => (string) $this->order->id,
                    'type' => 'new_order'
                ]);

            $chunks = array_chunk($tokens, 300);

            foreach ($chunks as $chunk) {

                $messaging->sendMulticast($message,$chunk);
            }

        }catch (\Exception $e){
            Log::error('Firebase Multicast Error: ' . $e->getMessage());
        }
    }
}
