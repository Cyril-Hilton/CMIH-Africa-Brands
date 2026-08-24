<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiserKpiAlertEvent extends Model
{
    protected $fillable = [
        'dedupe_key',
        'alert_type',
        'metric',
        'scope_type',
        'scope_id',
        'period_date',
        'payload',
        'notified_at',
    ];

    protected function casts(): array
    {
        return [
            'period_date' => 'date',
            'payload' => 'array',
            'notified_at' => 'datetime',
        ];
    }
}
