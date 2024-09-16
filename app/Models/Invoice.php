<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'title', 'status', 'notes', 'vat_rate'];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $currentMonthYear = date('m/Y');
            $lastInvoice = Invoice::where('number', 'like', 'F/%/' . $currentMonthYear)->orderBy('id', 'desc')->first();
            $lastNumber = $lastInvoice ? intval(explode('/', $lastInvoice->number)[1]) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $model->number = 'F/' . $newNumber . '/' . $currentMonthYear;
        });
    }
}
