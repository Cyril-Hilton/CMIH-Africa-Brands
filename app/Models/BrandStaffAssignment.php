<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandStaffAssignment extends Model
{
    use HasFactory;

    public const ROLE_AGENCY = 'agency_staff';
    public const ROLE_SUPPORT = 'supporting_staff';
    public const ROLE_ADMIN = 'brand_admin';
    public const ROLE_PROMOTER = 'promoter';
    public const ROLE_SALES = 'sales_personnel';
    public const ROLE_RETAIL = 'retail_staff';
    public const ROLE_SUPERVISOR = 'field_supervisor';
    public const ROLE_MERCHANDISER = 'merchandiser';

    public const ROLES = [
        self::ROLE_AGENCY,
        self::ROLE_SUPPORT,
        self::ROLE_ADMIN,
        self::ROLE_PROMOTER,
        self::ROLE_SALES,
        self::ROLE_RETAIL,
        self::ROLE_SUPERVISOR,
        self::ROLE_MERCHANDISER,
    ];

    protected $fillable = [
        'brand_id',
        'user_id',
        'role',
        'permissions',
        'is_active',
        'notes',
        'assigned_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'permissions' => 'array',
        ];
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === self::ROLE_ADMIN || $this->role === self::ROLE_AGENCY) {
            return true;
        }

        $perms = $this->permissions ?? [];
        return ! empty($perms[$permission]);
    }

    public function canManageTeam(): bool
    {
        return $this->hasPermission('can_manage_team');
    }

    public function canRecordActivity(): bool
    {
        return $this->hasPermission('can_record_activity');
    }

    public function canExport(): bool
    {
        return $this->hasPermission('can_export');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
