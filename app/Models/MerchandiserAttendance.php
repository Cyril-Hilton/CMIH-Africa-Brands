<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchandiserAttendance extends Model
{
    use HasFactory;

    protected $table = 'merchandiser_attendances';

    protected $fillable = [
        'user_id', 'outlet_id', 'clock_in_type', 'clock_in_time', 'clock_out_time',
        'client_recorded_at', 'sync_token', 'sync_source', 'synced_at',
        'latitude', 'longitude', 'distance_from_outlet',
        'clock_out_latitude', 'clock_out_longitude', 'clock_out_distance_from_outlet',
        'visit_duration_minutes', 'status'
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'client_recorded_at' => 'datetime',
        'synced_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'distance_from_outlet' => 'decimal:2',
        'clock_out_latitude' => 'decimal:8',
        'clock_out_longitude' => 'decimal:8',
        'clock_out_distance_from_outlet' => 'decimal:2',
        'visit_duration_minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
