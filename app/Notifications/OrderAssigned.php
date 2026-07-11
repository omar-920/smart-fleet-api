<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderAssigned extends Notification
{
    use Queueable;

    protected $order;
    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('لديك طلب جديد جاهز للتوصيل! 📦')
            ->greeting("أهلاً بك يا كابتن،")
            ->line("تم تعيين طلب جديد لك برقم: #" . $this->order->id)
            ->line("عنوان العميل: " . $this->order->dropoff_location)
            ->action('عرض تفاصيل الطلب', url('/orders/' . $this->order->id))
            ->line('بالتوفيق في رحلتك!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => "لديك طلب جديد جاهز للتوصيل برقم #" . $this->order->id,
            'amount' => $this->order->delivery_fee,
        ];
    }
}
