<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    const SOURCE_ORGANIC_SEARCH = 'Busca Orgânica';
    const SOURCE_SOCIAL_MEDIA = 'Redes Sociais';
    const SOURCE_EMAIL = 'Email';
    const SOURCE_DIRECT = 'Direto';
    const SOURCE_REFERRAL = 'Referência';

    /**
     * The sources of visitors.
     *
     * @var list<string>
     */
    const SOURCES = [
        self::SOURCE_ORGANIC_SEARCH,
        self::SOURCE_SOCIAL_MEDIA,
        self::SOURCE_EMAIL,
        self::SOURCE_DIRECT,
        self::SOURCE_REFERRAL,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ip',
        'source', // 'Busca Orgânica', 'Redes Sociais', 'Email', 'Direto', 'Referência'
        'country',
        'visited_at',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
