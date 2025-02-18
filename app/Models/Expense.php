<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'number', 'issue_date', 'issue_place', 'sale_date',
        'seller_type', 'seller_name', 'seller_nip', 'seller_street',
        'seller_postal_code', 'seller_city', 'seller_bank_account', 'seller_bank_name'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'sale_date' => 'date',
    ];

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    // Usuwanie powiązanych pozycji po usunięciu wydatku
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($expense) {
            foreach ($expense->items as $item) {
                $item->delete();
            }
        });
    }

    // Obliczanie sumy netto, brutto i VAT dla wydatku
    public function getTotalNetAttribute()
    {
        return $this->items->sum('net_value');
    }

    public function getTotalGrossAttribute()
    {
        return $this->items->sum('gross_value');
    }

    public function getTotalVatAttribute()
    {
        return $this->getTotalGrossAttribute() - $this->getTotalNetAttribute();
    }

    // Formatowanie NIP (np. usunięcie zbędnych spacji)
    public function getSellerNipAttribute($value)
    {
        return $value ? preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '$1-$2-$3-$4', $value) : null;
    }
}
