<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    // In Category model
    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'category');
    }
    protected $fillable = [
        'admin_or_user_id',
        'category'
    ];
}
