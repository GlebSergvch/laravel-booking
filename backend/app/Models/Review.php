<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model {
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'booking_object_id',
        'review',
        'rating',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function bookingObject() {
        return $this->belongsTo(BookingObject::class);
    }
}
