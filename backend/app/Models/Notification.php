<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model {
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'message',
        'is_read',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
