<?php

namespace App\DTO\TimeSlot;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateAvailabilityDto extends DataTransferObject
{
    public int $id;
    public bool $is_available;
    public ?int $updated_by = null;
}
