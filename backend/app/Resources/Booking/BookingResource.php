<?php

namespace App\Resources\Booking;

use App\Resources\AbstractResource;
use Illuminate\Http\Request;

class BookingResource extends AbstractResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'time_slot_id' => $this->time_slot_id,
            'status' => $this->status,
            'time_slot' => [
                'id' => $this->timeSlot->id,
                'booking_object_id' => $this->timeSlot->booking_object_id,
                'start_time' => $this->timeSlot->start_time->format('Y-m-d H:i:s'),
                'end_time' => $this->timeSlot->end_time->format('Y-m-d H:i:s'),
                'is_available' => $this->timeSlot->is_available,
            ],
            'booking_object' => [
                'id' => $this->timeSlot->bookingObject->id,
                'name' => $this->timeSlot->bookingObject->name,
                'room_id' => $this->timeSlot->bookingObject->room_id,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
