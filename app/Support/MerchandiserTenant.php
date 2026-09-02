<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;

final class MerchandiserTenant
{
    public const UNILEVER = 'unilever';
    public const GGBL = 'ggbl';
    private const ASSET_VERSION = '20260829';

    /**
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        $assetVersion = '?v='.self::ASSET_VERSION;

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
                'logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/DARK%20THEME/Unilever%20mark%20white.png'.$assetVersion,
                'workspace_logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/LIGHT%20THEME/Unilever%20mark%20black.png'.$assetVersion,
                'logo_filter' => 'none',
            ],
            self::GGBL => [
                'code' => self::GGBL,
                'name' => 'GGBL / Guinness',
                'portal_name' => 'GGBL Merchandiser Portal',
                'primary' => '#FECB00',
                'primary_dark' => '#C49A00',
                'accent' => '#FECB00',
                'accent_light' => '#F5F0DC',
                'background' => '#1A1A1A',
                'surface' => '#242424',
                'ink' => '#F5F0DC',
                'muted' => '#D1C8AD',
                'sidebar' => '#1A1A1A',
                'sidebar_ink' => '#FFFFFF',
                'logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/DARK%20THEME/Guinness%20mark%20light.png'.$assetVersion,
                'workspace_logo' => 'images/CMIH%20WEB%20ASSETS/BRAND%20LOGOS/LIGHT%20THEME/Guinness%20mark%20dark.png'.$assetVersion,
                'logo_filter' => 'brightness(0) invert(1)',
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
