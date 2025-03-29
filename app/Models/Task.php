<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const PRIORITY_DANGER = 'danger';
    const PRIORITY_WARNING = 'warning';
    const PRIORITY_INFO = 'info';
    const PRIORITY_SUCCESS = 'success';

    /**
     * The priorities of the task.
     *
     * @var list<string>
     */
    const PRIORITIES = [
        self::PRIORITY_DANGER,
        self::PRIORITY_WARNING,
        self::PRIORITY_INFO,
        self::PRIORITY_SUCCESS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'due_date',
        'priority', // 'danger', 'warning', 'info', 'success'
        'completed',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'completed' => 'boolean',
    ];
}
