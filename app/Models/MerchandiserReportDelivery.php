<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchandiserReportDelivery extends Model
{
    protected $fillable = [
        'report_id',
        'frequency',
        'period_start',
        'period_end',
        'sent_to',
        'format',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'sent_to' => 'array',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(MerchandiserReport::class, 'report_id');
    }
}
