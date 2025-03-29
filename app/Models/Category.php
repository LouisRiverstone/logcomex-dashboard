<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The products that belong to the category.
     * 
     * @return HasMany<Product,covariant Category>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
