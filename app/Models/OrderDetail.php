<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderDetail extends Model
{
    
    use HasFactory;
    protected $guarded = [];
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class); // Assuming `product_id` is foreign key
    }

    
    
}
