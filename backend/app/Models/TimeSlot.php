<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSlot extends Model {
    protected $table = 'time_slots';

    protected $fillable = [
        'booking_object_id',
        'start_time',
        'end_time',
        'is_available',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function bookingObject() {
        return $this->belongsTo(BookingObject::class);
    }
}
