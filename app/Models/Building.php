<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'address', 'units'
    ];
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function total_units()
    {
        return $this->hasMany(Unit::class);
    }
}
