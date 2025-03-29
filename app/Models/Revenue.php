<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    const TYPE_SERVICE = 'service';
    const TYPE_PRODUCT = 'product';
    const TYPE_SUBSCRIPTION = 'subscription';

    /**
     * The types of revenue.
     *
     * @var list<string>
     */
    const TYPES = [
        self::TYPE_SERVICE,
        self::TYPE_PRODUCT,
        self::TYPE_SUBSCRIPTION,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type', // 'service', 'product', 'subscription'
        'amount',
        'date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];
}
