<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoDownProductDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function goDownproduct(): BelongsTo
    {
        return $this->belongsTo(GoDownProduct::class,'go_down_product_id');
    }

         public function unit(): BelongsTo
        {
            return $this->belongsTo(Unit::class,'unit_id');
        }

}
