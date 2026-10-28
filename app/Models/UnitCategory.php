<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UnitCategory extends Model
{
    use HasFactory;
     protected $guarded = [];

     
     public function units():HasMany{
        return $this->HasMany(Unit::class);
    }
    public function standardUnit(): HasOne{
        return $this->hasOne(Unit::class)->where('showing_unit', '1')->select('conversion_factor','id','unit_category_id','name');
    }
}
