<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model {
    protected $table = 'promo_codes';

    protected $fillable = [
        'code',
        'discount',
        'expires_at',
        'created_by',
        'updated_by',
    ];
}
