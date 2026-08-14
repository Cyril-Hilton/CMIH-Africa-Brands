<?php

namespace App\Support;

use App\Models\Brand;
use App\Models\BrandStaffAssignment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BrandNotificationScope
{
    public static function queryFor(User $user): Builder
    {
        $query = Notification::query()
            ->where('user_id', $user->id)
            ->where(fn (Builder $q) => self::whereBrandsUrl($q));

        if ($user->isCvoOrSuperAdmin()) {
            return $query;
        }

        $assignments = BrandStaffAssignment::with('brand')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->get();

        return $query->where(function (Builder $q) use ($assignments) {
            self::whereBrandsIndexUrl($q);

            foreach ($assignments as $assignment) {
                foreach (self::brandKeys($assignment->brand) as $key) {
                    self::orWhereAssignedBrandUrl($q, $assignment, $key);
                }
            }
        });
    }

    public static function userCanSee(User $user, Notification $notification): bool
    {
        return self::queryFor($user)->whereKey($notification->id)->exists();
    }

    public static function workspaceUrlForRole(Brand $brand, ?string $role): string
    {
        $brandKey = $brand->slug ?: $brand->id;

        if ($role === BrandStaffAssignment::ROLE_RETAIL) {
            return route('brands-platform.retail', $brandKey);
        }

        if (in_array($role, [
            BrandStaffAssignment::ROLE_PROMOTER,
            BrandStaffAssignment::ROLE_SUPPORT,
            BrandStaffAssignment::ROLE_SALES,
            BrandStaffAssignment::ROLE_MERCHANDISER,
        ], true)) {
            return route('brands-platform.support', $brandKey);
        }

        return route('brands-platform.agency', $brandKey);
    }

    public static function workspaceUrlForAssignment(Brand $brand, ?BrandStaffAssignment $assignment, ?string $fallbackUrl = null): ?string
    {
        if (! $assignment) {
            return $fallbackUrl ?: route('brands-platform.agency', $brand->slug ?: $brand->id);
        }

        return self::workspaceUrlForRole($brand, $assignment->role);
    }

    private static function whereBrandsUrl(Builder $q): void
    {
        $q->where('url', 'like', '%/brands%')
            ->orWhere('url', 'like', '%brands.cmih.africa%');
    }

    private static function whereBrandsIndexUrl(Builder $q): void
    {
        $q->where('url', 'like', '%/brands')
            ->orWhere('url', 'like', '%/brands?%')
            ->orWhere('url', 'like', '%brands.cmih.africa');
    }

    private static function orWhereAssignedBrandUrl(Builder $q, BrandStaffAssignment $assignment, string $brandKey): void
    {
        $patterns = self::allowedPatternsFor($assignment, $brandKey);

        $q->orWhere(function (Builder $brandQ) use ($patterns) {
            foreach ($patterns as $pattern) {
                $brandQ->orWhere('url', 'like', $pattern);
            }
        });
    }

    /**
     * @return list<string>
     */
    private static function allowedPatternsFor(BrandStaffAssignment $assignment, string $brandKey): array
    {
        $base = '%/brands/'.self::escapeLike($brandKey);

        $patterns = [
            $base,
            $base.'?%',
            $base.'#%',
            $base.'/activation%',
            $base.'/publications%',
        ];

        if ($assignment->role === BrandStaffAssignment::ROLE_RETAIL) {
            $patterns[] = $base.'/retail%';

            return $patterns;
        }

        if (in_array($assignment->role, [
            BrandStaffAssignment::ROLE_PROMOTER,
            BrandStaffAssignment::ROLE_SUPPORT,
            BrandStaffAssignment::ROLE_SALES,
            BrandStaffAssignment::ROLE_MERCHANDISER,
        ], true)) {
            $patterns[] = $base.'/support%';

            return $patterns;
        }

        $patterns[] = $base.'/agency%';
        $patterns[] = $base.'/export%';
        $patterns[] = $base.'/gallery%';

        return $patterns;
    }

    /**
     * @return Collection<int, string>
     */
    private static function brandKeys(?Brand $brand): Collection
    {
        if (! $brand) {
            return collect();
        }

        return collect([
            $brand->slug,
            $brand->id,
            Str::slug((string) $brand->name),
        ])
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values();
    }

    private static function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $value);
    }
}
