<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
     protected $guarded = [];
     public static function boot()
    {
        parent::boot();

        static::creating(function ($warehouse) {
            $warehouse->slug = \Illuminate\Support\Str::slug($warehouse->name, '-');
        });
        
    }
}
