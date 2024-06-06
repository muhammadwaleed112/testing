<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billitem extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['product_id', 'price', 'qty', 'sub_total', 'pos_id'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function pos()
    {
        return $this->belongsTo(Pos::class);
    }
}
