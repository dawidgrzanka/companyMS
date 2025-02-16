<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'number', 'issue_date', 'issue_place', 'sale_date', 'seller_type', 'seller_name', 'seller_nip', 'seller_street', 'seller_postal_code', 'seller_city', 'seller_bank_account', 'seller_bank_name'
    ];

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
