<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchandiserOutletAssignment extends Model
{
    use HasFactory;

    public const STATUS_PLANNED = 'planned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_VISITED = 'visited';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_INCOMPLETE = 'incomplete';
    public const STATUS_CARRY_OVER = 'carry_over';
    public const STATUS_COLLAPSED = 'collapsed';

    protected $fillable = [
        'user_id',
        'outlet_id',
        'visit_id',
        'assigned_date',
        'assigned_start_at',
        'assigned_end_at',
        'sequence',
        'status',
        'collapse_reason_type',
        'collapse_reason',
        'source',
        'completed_at',
        'collapsed_at',
        'collapsed_by',
        'carry_over_marked_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'assigned_start_at' => 'datetime',
            'assigned_end_at' => 'datetime',
            'completed_at' => 'datetime',
            'collapsed_at' => 'datetime',
            'carry_over_marked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(MerchandiserVisit::class, 'visit_id');
    }

    public function collapsedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collapsed_by');
    }

    public function auditRecords(): HasMany
    {
        return $this->hasMany(MerchandiserPjpAudit::class, 'assignment_id');
    }
}
