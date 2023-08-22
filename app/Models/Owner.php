<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'number', 'email', 'company',
    ];

    public function drivers() :hasMany
    {
        return $this->hasMany(Driver::class);
    }

}
