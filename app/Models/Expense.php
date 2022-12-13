<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function expense_type()
    {
      return $this->belongsTo('App\Models\Expensetype', 'expensetype_id', 'et_id');
    }
}
