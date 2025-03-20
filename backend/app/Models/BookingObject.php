<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingObject extends Model {
    protected $table = 'booking_objects';

    protected $fillable = [
        'room_id',
        'name',
        'description',
        'type',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function room() {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return HasMany
     */
    public function timeSlots() {
        return $this->hasMany(TimeSlot::class);
    }
}
