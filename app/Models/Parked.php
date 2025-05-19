<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Parked extends Model
{
    protected $table = 'parked';

    protected $fillable = [
        'in',
        'out',
        'total_payment',
        'payment_status',
        'area_id',
        'custom_user_id'
    ];

    public function customuser(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'custome_user_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
