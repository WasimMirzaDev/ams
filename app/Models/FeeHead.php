<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeHead extends Model
{
    use HasFactory;
    protected $primaryKey = 'fh_id';
    protected $guarded = ['fh_id', 'is_rent', 'is_advance'];
}
