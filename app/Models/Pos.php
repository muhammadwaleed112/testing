<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pos extends Model
{
    use HasFactory,SoftDeletes;
     
    public function billItems()
    {
        return $this->hasMany(Billitem::class);
    }
}
