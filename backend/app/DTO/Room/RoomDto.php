<?php

namespace App\DTO\Room;

use Spatie\DataTransferObject\DataTransferObject;

class RoomDto extends DataTransferObject
{
    public ?int $id; // Добавляем свойство id
    public ?int $hotel_id = null;
    public ?string $name = null;
    public ?int $capacity = null;
    public ?float $price_per_night = null;
    public array $option_ids = [];
    public ?int $page = 1;
    public ?int $per_page = 15;
    public ?float $price_min = null;
    public ?float $price_max = null;
}
