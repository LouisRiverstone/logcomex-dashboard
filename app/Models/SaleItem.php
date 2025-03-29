<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var list<string>
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * The attributes that should be cast to native types.
     * 
     * @var array<string,string>
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];
    
    /**
     * The sale that the item belongs to.
     * 
     * @return BelongsTo<Sale,$this>
     */
    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * The product that the item belongs to.
     * 
     * @return BelongsTo<Product,$this>
     */
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
