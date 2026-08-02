<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'image',
        'is_kan',
    ];

    protected $casts = [
        'is_kan' => 'boolean',
        'price'  => 'decimal:2',
    ];
}