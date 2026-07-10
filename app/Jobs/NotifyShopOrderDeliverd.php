<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NotifyShopOrderDeliverd implements ShouldQueue
{
    use Queueable;

    public $order;
    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(5);
        Log::info("تم إرسال إيميل للمتجر: طلب رقم {$this->order->id} تم توصيله بنجاح للعميل!");
    }
}
