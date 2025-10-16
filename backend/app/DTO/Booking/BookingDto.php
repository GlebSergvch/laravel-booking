<?php

namespace App\DTO\Booking;

use Spatie\DataTransferObject\DataTransferObject;

class BookingDto extends DataTransferObject
{
    public ?int $id = null;
    public ?int $user_id = null;
    public int $time_slot_id;
    public ?string $status = 'pending';
    public ?int $page = 1;
    public ?int $per_page = 15;
}
