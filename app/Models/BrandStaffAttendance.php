<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandStaffAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'brand_activation_id',
        'user_id',
        'staff_role',
        'assigned_location_name',
        'assigned_latitude',
        'assigned_longitude',
        'clock_in_time',
        'clock_in_latitude',
        'clock_in_longitude',
        'clock_in_distance_meters',
        'is_late',
        'lateness_minutes',
        'deduction_amount',
        'clock_out_time',
        'clock_out_latitude',
        'clock_out_longitude',
        'clock_out_distance_meters',
        'status',
        'notes',
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'is_late' => 'boolean',
        'deduction_amount' => 'decimal:2',
        'clock_in_latitude' => 'float',
        'clock_in_longitude' => 'float',
        'assigned_latitude' => 'float',
        'assigned_longitude' => 'float',
        'clock_in_distance_meters' => 'float',
        'clock_out_distance_meters' => 'float',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function activation(): BelongsTo
    {
        return $this->belongsTo(BrandActivation::class, 'brand_activation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
