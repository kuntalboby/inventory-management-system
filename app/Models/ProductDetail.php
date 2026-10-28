<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    use HasFactory;
     protected $guarded = [];

     public function product(): BelongsTo
        {
            return $this->belongsTo(Product::class);
        }

         public function unit(): BelongsTo
        {
            return $this->belongsTo(Unit::class,'unit_id');
        }

         public function warehouse(): BelongsTo
        {
            return $this->belongsTo(Warehouse::class);
        }
}
