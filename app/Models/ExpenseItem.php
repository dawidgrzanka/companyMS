<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'name',
        'quantity',
        'unit',
        'net_price',
        'vat_rate',
        'net_value',
        'gross_value'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    // Mutatory dla wartości netto i brutto (zaokrąglenie do 2 miejsc po przecinku)
    public function setNetValueAttribute($value)
    {
        $this->attributes['net_value'] = round($value, 2);
    }

    public function setGrossValueAttribute($value)
    {
        $this->attributes['gross_value'] = round($value, 2);
    }

    // Upewnienie się, że VAT jest w zakresie 0-100
    public function setVatRateAttribute($value)
    {
        $this->attributes['vat_rate'] = max(0, min(100, $value));
    }

    // Automatyczne przeliczanie wartości na podstawie ceny jednostkowej i ilości
    public function calculateValues()
    {
        $this->net_value = round($this->quantity * $this->net_price, 2);
        $this->gross_value = round($this->net_value * (1 + ($this->vat_rate / 100)), 2);
    }
}
