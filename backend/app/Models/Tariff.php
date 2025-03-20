<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tariff extends Model {
    protected $table = 'tariffs';

    protected $fillable = [
        'booking_object_id',
        'price_per_hour',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function bookingObject() {
        return $this->belongsTo(BookingObject::class);
    }
}
