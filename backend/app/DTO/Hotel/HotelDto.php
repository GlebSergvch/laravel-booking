<?php

namespace App\DTO\Hotel;

use App\Interfaces\DtoInterface;
use Spatie\DataTransferObject\DataTransferObject;

class HotelDto extends DataTransferObject implements DtoInterface
{
    public readonly ?int $id;
    public readonly string $name;
    public readonly string $address;
    public readonly string $city;
    public readonly string $country;

}
