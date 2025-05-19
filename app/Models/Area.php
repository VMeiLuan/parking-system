<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ParkingRate;

class Area extends Model
{
    protected $table = 'area';

    protected $casts = [
        'parking_space_normal_user' => 'array',
        'parking_space_oku_user' => 'array',
    ];

    protected $fillable = [
        'title',
        'parking_space_normal',
        'parking_space_normal_user',
        'parking_space_oku',
        'parking_space_oku_user',
    ];

    public function ParkingRate(): BelongsTo
    {
        return $this->belongsTo(ParkingRate::class, 'parking_rate_id');
    }

}
