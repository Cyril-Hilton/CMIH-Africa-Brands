<?php

namespace App\Support;

use App\Models\User;

final class MerchandiserPortalRole
{
    public const FIELD = 'field';
    public const SUPERVISOR = 'supervisor';
    public const ADMIN = 'admin';
    public const CLIENT = 'client';

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            self::FIELD => [
                'key' => self::FIELD,
                'label' => 'Field Agent',
                'short_label' => 'Field Agent',
                'icon' => '📱',
                'title' => 'Merchandiser Field Agent Portal',
                'description' => 'Execute PJP routes, clock into outlets, complete Perfect Store assessments, and submit evidence.',
                'button' => 'Access Field Dashboard',
                'allow_register' => true,
                'access_role' => User::MERCHANDISER_ROLE,
            ],
            self::SUPERVISOR => [
                'key' => self::SUPERVISOR,
                'label' => 'Supervisor',
                'short_label' => 'Supervisor',
                'icon' => '🧭',
                'title' => 'Supervisor Portal',
                'description' => 'Monitor field teams, coverage, attendance, live activity, exceptions, and coaching actions.',
                'button' => 'Access Supervisor Dashboard',
                'allow_register' => true,
                'access_role' => User::MERCHANDISER_SUPERVISOR_ROLE,
            ],
            self::ADMIN => [
                'key' => self::ADMIN,
                'label' => 'Admin Hub',
                'short_label' => 'Admin Hub',
                'icon' => '🛡️',
                'title' => 'Merchandiser Admin Hub',
                'description' => 'Brands team administrators manage tenants, approvals, routes, KPIs, outlets, reports, and settings.',
                'button' => 'Access Admin Hub',
                'allow_register' => false,
                'access_role' => null,
            ],
            self::CLIENT => [
                'key' => self::CLIENT,
                'label' => 'Client / TM',
                'short_label' => 'Client / TM',
                'icon' => '📊',
                'title' => 'Client / TM Portal',
                'description' => 'Review executive KPIs, TM/KD performance, Perfect Store trends, and exportable business reports.',
                'button' => 'Access Client Dashboard',
                'allow_register' => true,
                'access_role' => User::MERCHANDISER_CLIENT_ROLE,
            ],
        ];
    }

    public static function normalize(?string $role): string
    {
        $role = strtolower(trim((string) $role));

        return array_key_exists($role, self::all()) ? $role : self::FIELD;
    }

    public static function definition(?string $role): array
    {
        return self::all()[self::normalize($role)];
    }

    public static function accessRoleFor(?string $role): ?string
    {
        return self::definition($role)['access_role'];
    }

    public static function roleForAccessRole(?string $accessRole): string
    {
        return match ($accessRole) {
            User::MERCHANDISER_SUPERVISOR_ROLE => self::SUPERVISOR,
            User::MERCHANDISER_CLIENT_ROLE => self::CLIENT,
            default => self::FIELD,
        };
    }

    /**
     * @return array<string>
     */
    public static function registerableKeys(): array
    {
        return collect(self::all())
            ->filter(fn (array $role) => (bool) $role['allow_register'])
            ->keys()
            ->all();
    }
}
