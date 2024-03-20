<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'wp_category_id',
        'name',
        'title',
        'content',
        'sku',
        'base_price',
        'status',
        'image',
        'publish_date'
    ];
}
