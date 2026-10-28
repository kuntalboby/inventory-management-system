<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TotalSticker extends Model
{
    use HasFactory;
    protected $guarded = [];

      public function sticker(): HasMany
        {
            return $this->HasMany(Sticker::class,'sticker_code','sticker_code');
        }
}
