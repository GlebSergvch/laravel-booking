<?php

namespace App\DTO\Booking;

use App\Interfaces\DtoInterface;
use Spatie\DataTransferObject\DataTransferObject;

class BookingObjectDto extends DataTransferObject implements DtoInterface
{
    public ?int $id = null;
    public ?int $room_id = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $type = null;
    public ?int $page = 1;
    public ?int $per_page = 15;
}
