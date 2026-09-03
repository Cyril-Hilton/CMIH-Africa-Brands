<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchandiserVisitCategoryImage extends Model
{
    use HasFactory;

    public const STATUS_CAPTURED_VALIDATED = 'captured_validated';
    public const STATUS_AI_PROCESSING = 'ai_processing';
    public const STATUS_NOT_CAPTURED = 'not_captured';
    public const STATUS_WRONG_CATEGORY = 'wrong_category';
    public const STATUS_NOT_APPLICABLE = 'not_applicable';
    public const STATUS_MANUAL_REVIEW = 'manual_review';

    protected $fillable = [
        'visit_id',
        'user_id',
        'supervisor_id',
        'kd_id',
        'region_id',
        'outlet_id',
        'category',
        'image_path',
        'status',
        'ai_provider',
        'ai_model',
        'ai_confidence',
        'ai_message',
        'ai_payload',
        'validated_at',
        'marked_not_applicable_by',
        'marked_not_applicable_at',
    ];

    protected function casts(): array
    {
        return [
            'ai_confidence' => 'decimal:2',
            'ai_payload' => 'array',
            'validated_at' => 'datetime',
            'marked_not_applicable_at' => 'datetime',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(MerchandiserVisit::class, 'visit_id');
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

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
