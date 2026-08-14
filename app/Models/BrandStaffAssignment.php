<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandStaffAssignment extends Model
{
    use HasFactory;

    // ── Role constants ────────────────────────────────────────────────────────
    public const ROLE_AGENCY       = 'agency_staff';
    public const ROLE_SUPPORT      = 'supporting_staff';
    public const ROLE_ADMIN        = 'brand_admin';
    public const ROLE_PROMOTER     = 'promoter';
    public const ROLE_SALES        = 'sales_personnel';
    public const ROLE_RETAIL       = 'retail_staff';
    public const ROLE_SUPERVISOR   = 'field_supervisor';
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

    // ── Enrollment sources ────────────────────────────────────────────────────
    public const SOURCE_CMIH_API = 'cmih_api';
    public const SOURCE_MANUAL   = 'manual';

    // ── Enrollment types ──────────────────────────────────────────────────────
    public const TYPE_AGENCY_STAFF    = 'agency_staff';
    public const TYPE_PROMOTER        = 'promoter';
    public const TYPE_RETAIL_TERMINAL = 'retail_terminal';

    public const ENROLLMENT_TYPES = [
        self::TYPE_AGENCY_STAFF,
        self::TYPE_PROMOTER,
        self::TYPE_RETAIL_TERMINAL,
    ];

    // ── ID document types ─────────────────────────────────────────────────────
    public const ID_TYPES = [
        'Ghana Card',
        'Passport',
        'Voter ID',
        'NHIS Card',
        "Driver's License",
        'SSNIT Card',
        'Birth Certificate',
    ];

    protected $fillable = [
        'brand_id',
        'user_id',
        'role',
        'enrollment_source',
        'enrollment_type',
        'external_name',
        'external_phone',
        'external_email',
        'external_id_type',
        'external_id_number',
        'photo_path',
        'permissions',
        'assigned_location',
        'assigned_address',
        'assigned_latitude',
        'assigned_longitude',
        'shift_start_time',
        'shift_end_time',
        'grace_period_minutes',
        'lateness_deduction_amount',
        'is_active',
        'is_current_venue',
        'venue_changed_reason',
        'venue_assigned_at',
        'notes',
        'assigned_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active'                 => 'boolean',
            'is_current_venue'          => 'boolean',
            'permissions'               => 'array',
            'assigned_latitude'         => 'float',
            'assigned_longitude'        => 'float',
            'grace_period_minutes'      => 'integer',
            'lateness_deduction_amount' => 'decimal:2',
            'venue_assigned_at'         => 'datetime',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current_venue', true);
    }

    public function scopeVenueHistory(Builder $query): Builder
    {
        return $query->where('is_current_venue', false);
    }

    public function scopeFromCmih(Builder $query): Builder
    {
        return $query->where('enrollment_source', self::SOURCE_CMIH_API);
    }

    public function scopeManuallyEnrolled(Builder $query): Builder
    {
        return $query->where('enrollment_source', self::SOURCE_MANUAL);
    }

    public function scopePromoters(Builder $query): Builder
    {
        return $query->where('enrollment_type', self::TYPE_PROMOTER);
    }

    public function scopeRetailTerminal(Builder $query): Builder
    {
        return $query->where('enrollment_type', self::TYPE_RETAIL_TERMINAL);
    }

    // ── Computed Attributes ───────────────────────────────────────────────────

    public function getDisplayNameAttribute(): string
    {
        return $this->user?->name ?: $this->external_name ?: 'Unknown Staff';
    }

    public function getDisplayEmailAttribute(): ?string
    {
        return $this->user?->email ?: $this->external_email;
    }

    public function getDisplayPhoneAttribute(): ?string
    {
        return $this->external_phone ?: null;
    }

    public function isManualEnrollment(): bool
    {
        return $this->enrollment_source === self::SOURCE_MANUAL;
    }

    public function getEnrollmentTypeLabelAttribute(): string
    {
        return match ($this->enrollment_type) {
            self::TYPE_PROMOTER        => 'Promoter',
            self::TYPE_RETAIL_TERMINAL => 'Retail Terminal',
            default                    => 'Agency Staff',
        };
    }

    // ── Permission Helpers ────────────────────────────────────────────────────

    public function hasPermission(string $permission): bool
    {
        if ($this->role === self::ROLE_ADMIN) {
            return true;
        }

        $perms = $this->permissions ?? [];

        if (($perms['access_level'] ?? null) === 'brand_account_manager') {
            return true;
        }

        if ($this->role === self::ROLE_AGENCY && empty($perms)) {
            return true;
        }

        return ! empty($perms[$permission]);
    }

    public function canManageTeam(): bool    { return $this->hasPermission('can_manage_team'); }
    public function canRecordActivity(): bool { return $this->hasPermission('can_record_activity'); }
    public function canExport(): bool         { return $this->hasPermission('can_export'); }

    // ── Venue History ─────────────────────────────────────────────────────────

    /**
     * Archive this venue row and create a new active one.
     * Full venue history is preserved — old row is never deleted.
     */
    public function changeVenueTo(array $venueData, ?string $reason = null, ?int $changedByUserId = null): self
    {
        $this->update([
            'is_current_venue'     => false,
            'venue_changed_reason' => $reason ?? 'Venue updated',
            'is_active'            => false,
        ]);

        return static::create(array_merge([
            'brand_id'                  => $this->brand_id,
            'user_id'                   => $this->user_id,
            'role'                      => $this->role,
            'enrollment_source'         => $this->enrollment_source,
            'enrollment_type'           => $this->enrollment_type,
            'external_name'             => $this->external_name,
            'external_phone'            => $this->external_phone,
            'external_email'            => $this->external_email,
            'external_id_type'          => $this->external_id_type,
            'external_id_number'        => $this->external_id_number,
            'photo_path'                => $this->photo_path,
            'permissions'               => $this->permissions,
            'shift_start_time'          => $this->shift_start_time,
            'shift_end_time'            => $this->shift_end_time,
            'grace_period_minutes'      => $this->grace_period_minutes,
            'lateness_deduction_amount' => $this->lateness_deduction_amount,
            'notes'                     => $this->notes,
            'assigned_by'               => $changedByUserId ?? $this->assigned_by,
            'is_active'                 => true,
            'is_current_venue'          => true,
            'venue_assigned_at'         => now(),
        ], $venueData));
    }

    // ── Relationships ─────────────────────────────────────────────────────────

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
