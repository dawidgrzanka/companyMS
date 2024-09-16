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
        'status',
    ];

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
