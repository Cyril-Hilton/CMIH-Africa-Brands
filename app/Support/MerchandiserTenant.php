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
                'primary' => '#0F4C81',
                'primary_dark' => '#0A3357',
                'accent' => '#1D70B8',
                'accent_light' => '#4792D3',
                'background' => '#F1F5F9',
                'surface' => '#FFFFFF',
                'ink' => '#0F172A',
                'muted' => '#475569',
                'sidebar' => '#0A192F',
                'sidebar_ink' => '#FFFFFF',
                'logo' => 'storage/brands/unilever-light.png',
                'logo_filter' => 'none',
            ],
            self::GGBL => [
                'code' => self::GGBL,
                'name' => 'GGBL / Guinness',
                'portal_name' => 'GGBL Merchandiser Portal',
                'primary' => '#D4AF37',
                'primary_dark' => '#A38020',
                'accent' => '#E5C158',
                'accent_light' => '#F3D98A',
                'background' => '#090A0F',
                'surface' => '#12141D',
                'ink' => '#F8FAFC',
                'muted' => '#94A3B8',
                'sidebar' => '#050608',
                'sidebar_ink' => '#F8FAFC',
                'logo' => 'storage/brands/guinness-light.png',
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
