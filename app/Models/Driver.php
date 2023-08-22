<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    use HasFactory;

    public function vehicle_type():belongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function owner():belongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function equipment()
    {
        return $this->belongsToMany(Equipment::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class)->where('driver_id', $this->id);
    }

    protected $fillable = [
        'number', 'fullname', 'phone', 'owner_id', 'citizenship', 'email',
        'vehicle_type_id', 'capacity', 'dimension',
        'insurance_expdate', 'register_expdate', 'plate_state', 'plate_number',
        'availability', 'dnu',
        'note',
        'zipcode', 'location', 'latitude', 'longitude',
        'future_zipcode', 'future_location', 'future_latitude', 'future_longitude', 'future_datetime',
    ];

    protected $casts = [
        'availability' => 'boolean',
        'dnu' => 'boolean',
        'future_datetime' => 'datetime',
    ];
}
