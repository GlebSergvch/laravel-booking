<?php

namespace App\Resources\Booking;

use App\Resources\AbstractResource;
use Illuminate\Http\Request;

class BookingListResource extends AbstractResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'time_slot_id' => $this->time_slot_id,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
