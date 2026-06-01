<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'description', 'price', 'in_stock', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'in_stock' => 'boolean',
        'price' => 'decimal:2',
    ];
}