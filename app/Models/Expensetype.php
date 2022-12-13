<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expensetype extends Model
{
    use HasFactory;
    protected $primaryKey = 'et_id';
    protected $guarded = ['et_id'];
}
