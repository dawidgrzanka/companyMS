<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'notes'];

    public function items()
    {
        return $this->hasMany(OfferItem::class);
    }
}
