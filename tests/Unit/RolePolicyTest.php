<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Policies\RolePolicy;
use PHPUnit\Framework\TestCase;

class RolePolicyTest extends TestCase
{
    public function testOwnerHasAllPermissions(): void
    {
        $policy = new RolePolicy();
        $permissions = ['tickets.view', 'billing.manage'];
        self::assertTrue($policy->can('owner', 'tickets.view', $permissions));
        self::assertTrue($policy->can('owner', 'billing.manage', $permissions));
    }

    public function testSupportRoleRestricted(): void
    {
        $policy = new RolePolicy();
        $permissions = ['tickets.view', 'billing.manage'];
        self::assertTrue($policy->can('support', 'tickets.view', $permissions));
        self::assertFalse($policy->can('support', 'billing.manage', $permissions));
    }
}
