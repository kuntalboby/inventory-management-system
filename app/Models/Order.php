<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    
    use HasFactory;
    protected $guarded = [];
    
    
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

   public function orderDetails(): HasMany
{
    return $this->hasMany(OrderDetail::class);
}


       public function customer(): BelongsTo
        {
            return $this->belongsTo(Customer::class, 'customer_id');
        }
        public function customerAddress(): HasOne
        {
            return $this->hasOne(CustomerAddress::class, 'order_id', 'id');
        }
        
}
