<?php

declare(strict_types=1);

namespace App\Policies;

class RolePolicy
{
    /**
     * @param array<int, string> $permissions
     */
    public function can(string $role, string $permission, array $permissions): bool
    {
        $matrix = [
            'owner' => $permissions,
            'admin' => array_filter($permissions, static fn (string $perm): bool => $perm !== 'billing.manage'),
            'support' => ['tickets.view', 'tickets.respond', 'orders.view'],
            'finance' => ['billing.view', 'billing.manage', 'orders.view'],
            'readonly' => ['orders.view', 'reports.view'],
            'reseller' => ['orders.view', 'orders.create'],
        ];

        return in_array($permission, $matrix[$role] ?? [], true);
    }
}
