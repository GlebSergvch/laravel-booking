<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model {
    protected $table = 'rooms';

    protected $fillable = [
        'hotel_id',
        'name',
        'capacity',
        'price_per_night',
    ];

    /**
     * @return BelongsTo
     */
    public function hotel() {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * @return BelongsToMany
     */
    public function options() {
        return $this->belongsToMany(Option::class, 'room_option');
    }
}
