<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'purchase_price_netto', 
        'purchase_price_brutto', 
        'sale_price_netto', 
        'sale_price_brutto', 
        'margin', 
        'stock'
    ];

    public function stockMovements()
    {
    return $this->hasMany(StockMovement::class);
}
}
