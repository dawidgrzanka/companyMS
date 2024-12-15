<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'client_id',
        'planned_material_budget',
        'status',
    ];

    // Relacja z plikami zadania
    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    // Relacja z klientem
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relacja z użyciem materiałów (możemy mieć wiele materiałów przypisanych do zadania)
    public function materialUsages()
    {
        return $this->hasMany(MaterialUsage::class);
    }

    // Relacja z produktami (możemy mieć wiele produktów przypisanych do zadania)
    public function products()
    {
        return $this->hasMany(Product::class); // Jeśli jedno zadanie może mieć wiele produktów
    }
}
