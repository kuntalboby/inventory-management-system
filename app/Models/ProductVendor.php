<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVendor extends Model
{
    use HasFactory;
         protected $guarded = [];
        public function product() {
            return $this->belongsTo(Product::class);
        }
        public function goDownProduct() {
            return $this->belongsTo(GoDownProduct::class);
        }
         public function unit(): BelongsTo
        {
            return $this->belongsTo(Unit::class);
        }
        public function vendor(): BelongsTo
        {
            return $this->belongsTo(Vendor::class);
        }



}
