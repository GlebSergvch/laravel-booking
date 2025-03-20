<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model {
    protected $table = 'hotels';

    protected $fillable = [
        'name',
        'address',
        'city',
        'country',
    ];

    /**
     * @return HasMany
     */
    public function rooms() {
        return $this->hasMany(Room::class);
    }
}
