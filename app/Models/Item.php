<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'price'];

    public function offerItems()
    {
        return $this->hasMany(OfferItem::class);
    }
}
