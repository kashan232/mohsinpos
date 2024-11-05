<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'admin_or_user_id',
        'unit'
    ];

    // In Brand model
    public function products()
    {
        return $this->hasMany(Product::class, 'unit', 'unit');
    }
}
