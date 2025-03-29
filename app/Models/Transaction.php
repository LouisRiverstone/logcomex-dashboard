<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    const TYPE_SALE = 'sale';
    const TYPE_REFUND = 'refund';
    const TYPE_PAYMENT = 'payment';

    /**
     * The types of transactions.
     *
     * @var list<string>
     */
    const TYPES = [
        self::TYPE_SALE,
        self::TYPE_REFUND,
        self::TYPE_PAYMENT,
    ];

    const STATUS_SUCCESS = 'success';
    const STATUS_WARNING = 'warning';
    const STATUS_DANGER = 'danger';

    /**
     * The statuses of transactions.
     *
     * @var list<string>
     */
    const STATUSES = [
        self::STATUS_SUCCESS,
        self::STATUS_WARNING,
        self::STATUS_DANGER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type', // 'sale', 'refund', 'payment'
        'amount',
        'status', // 'success', 'warning', 'danger'
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * The user that made the transaction.
     * 
     * @return BelongsTo<User,$this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
