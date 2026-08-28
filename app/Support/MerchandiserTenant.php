<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;

final class MerchandiserTenant
{
    public const UNILEVER = 'unilever';
    public const GGBL = 'ggbl';

    /**
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        return [
            self::UNILEVER => [
                'code' => self::UNILEVER,
                'name' => 'Unilever',
                'portal_name' => 'Unilever Merchandiser Portal',
                'primary' => '#0F0E9A',
                'primary_dark' => '#0D009D',
                'accent' => '#0F0E9A',
                'accent_light' => '#EEF2FF',
                'background' => '#FFFFFF',
                'surface' => '#FFFFFF',
                'ink' => '#333333',
                'muted' => '#4D4D4D',
                'sidebar' => '#0F0E9A',
                'sidebar_ink' => '#FFFFFF',
                'logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/DARK%20THEME/Unilever%20white.png',
                'workspace_logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/LIGHT%20THEME/Unilever%20black.png',
                'logo_filter' => 'none',
            ],
            self::GGBL => [
                'code' => self::GGBL,
                'name' => 'GGBL / Guinness',
                'portal_name' => 'GGBL Merchandiser Portal',
                'primary' => '#C5A059',
                'primary_dark' => '#9E7F35',
                'accent' => '#E2C57B',
                'accent_light' => '#FDF9F2',
                'background' => '#000000',
                'surface' => '#111111',
                'ink' => '#FDF9F2',
                'muted' => '#A6A6A6',
                'sidebar' => '#000000',
                'sidebar_ink' => '#FDF9F2',
                'logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/DARK%20THEME/Guinness%20light.png',
                'workspace_logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/LIGHT%20THEME/Guinness%20dark.png',
                'logo_filter' => 'none',
            ],
        ];
    }

    public static function normalize(?string $tenant): string
    {
        $tenant = strtolower(trim((string) $tenant));

        return array_key_exists($tenant, self::all()) ? $tenant : self::UNILEVER;
    }

    /**
     * @return array<string, string>
     */
    public static function theme(?string $tenant): array
    {
        $code = self::normalize($tenant);

        return self::all()[$code];
    }

    public static function forUser(?User $user, ?Request $request = null): string
    {
        if ($user?->isMerchandiserPortalAdmin() && $request?->filled('tenant')) {
            return self::normalize($request->query('tenant'));
        }

        if ($user?->merchandiser_tenant) {
            return self::normalize($user->merchandiser_tenant);
        }

        if ($user?->isMerchandiserPortalAdmin() && $request) {
            return self::normalize($request->query('tenant'));
        }

        return self::UNILEVER;
    }
}
