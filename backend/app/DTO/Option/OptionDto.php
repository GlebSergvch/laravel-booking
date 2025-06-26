<?php

namespace App\DTO\Option;

use Spatie\DataTransferObject\DataTransferObject;

class OptionDto extends DataTransferObject
{
    public ?int $id = null;
    public ?string $name = null;
    public ?int $page = 1;
    public ?int $per_page = 15;
}
