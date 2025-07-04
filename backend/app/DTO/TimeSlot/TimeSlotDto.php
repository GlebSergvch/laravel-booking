<?php

namespace App\DTO\TimeSlot;

use Spatie\DataTransferObject\DataTransferObject;

class TimeSlotDto extends DataTransferObject
{
    public ?int $id = null;
    public ?int $booking_object_id = null;
    public ?string $start_time = null;
    public ?string $end_time = null;
    public ?bool $is_available = null;
    public ?int $page = 1;
    public ?int $per_page = 15;
}
