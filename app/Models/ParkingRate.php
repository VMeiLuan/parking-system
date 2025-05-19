<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Area;

class ParkingRate extends Model
{
    protected $table = 'parking_rates';

    protected $fillable = [
        'title',
        'hours',
        'fees',
        'description',
        'remark'
    ];

    public function area(): HasMany
    {
        return $this->hasMany(Area::class);
    }

}
