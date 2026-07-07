<?php

namespace App\DTOs;

use Illuminate\Http\Request;
class CreateOrderDTO{
    public function __construct(
        public readonly int $shop_id,
        public readonly string $pickup_location,
        public readonly string $dropoff_location,
        public readonly string $customer_phone
    ){}

    public static function fromRequest(Request $request): self{
        return new self(
            shop_id: $request->user()->id,
            pickup_location: $request->pickup_location,
            dropoff_location: $request->dropoff_location,
            customer_phone: $request->customer_phone,
        );
    }
}
