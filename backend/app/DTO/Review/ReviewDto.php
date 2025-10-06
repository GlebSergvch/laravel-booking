<?php

namespace App\DTO\Review;

use Spatie\DataTransferObject\DataTransferObject;

class ReviewDto extends DataTransferObject
{
    public ?int $id = null;
    public ?int $user_id = null;
    public ?int $booking_object_id = null;
    public ?string $review = null;
    public ?int $rating = null;
    public ?int $page = 1;
    public ?int $per_page = 15;
}
