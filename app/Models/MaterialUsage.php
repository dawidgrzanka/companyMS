<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 
        'product_id', 
        'quantity'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
