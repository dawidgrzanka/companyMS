<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id', 'name', 'quantity', 'unit', 'net_price', 'vat_rate', 'net_value', 'gross_value'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
