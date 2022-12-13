<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  protected $guarded = ['id'];
    use HasFactory;
  public function building()
  {
    return $this->belongsTo('App\Models\Building');
  }
}
