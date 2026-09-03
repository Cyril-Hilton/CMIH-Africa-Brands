<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchandiserPjpAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'supervisor_id',
        'kd_id',
        'outlet_id',
        'assigned_date',
        'action',
        'from_status',
        'to_status',
        'reason_type',
        'reason',
        'route_snapshot',
        'performed_by',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'route_snapshot' => 'array',
            'performed_at' => 'datetime',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(MerchandiserOutletAssignment::class, 'assignment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function keyDistributor(): BelongsTo
    {
        return $this->belongsTo(KeyDistributor::class, 'kd_id');
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
