<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function tenant()
    {
      return $this->belongsTo('App\Models\Tenant');
    }

    public function unit()
    {
      return $this->belongsTo('App\Models\Unit');
    }

    public function voucher_detail()
    {
      return $this->hasMany(ChallanDetail::class);
    }
}
