<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $time_slot_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property User $user
 * @property TimeSlot $timeSlot
 */
class Booking extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'time_slot_id',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function timeSlot()
    {
        return $this->belongsTo('App\Models\TimeSlot');
    }
}
