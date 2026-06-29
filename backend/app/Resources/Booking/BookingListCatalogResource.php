<?php

namespace App\Resources\Booking;

use App\Resources\AbstractResource;
use Illuminate\Http\Request;

class BookingListCatalogResource extends AbstractResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}

