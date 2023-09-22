<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Modules\Roles\Support\RoleType;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create([
            'name' => 'Admin',
            'display_name' => 'Administrator',
            'type' => RoleType::ADMIN,
            'description' => 'Administrator.'
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'display_name' => 'User',
            'type' => RoleType::USER,
            'description' => 'Default user.'
        ]);

        $this->assignAdminPermissions($adminRole);
        $this->assignUserPermissions($userRole);
    }

    /**
     * Assign admin permissions
     * 
     * @param \Spatie\Permission\Models\Role $role
     * 
     * @return void
     */
    protected function assignAdminPermissions($role) 
    {
        $permissions = Permission::pluck('name');

        $role->givePermissionTo($permissions);
    }

    /**
     * Assign user permissions
     * 
     * @param \Spatie\Permission\Models\Role $role
     * 
     * @return void
     */
    protected function assignUserPermissions($role) 
    {
        $permissions = Permission::where('name', 'LIKE', 'profile.%')
            ->orWhere('name', 'LIKE', 'dashboard.%')
            ->orWhere('name', 'LIKE', 'user.activities.%');

        if (Module::has('Affiliates')) {
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.affiliates.%')
                ->orWhere('name', 'LIKE', 'user.commissions.%');
        }

        if (Module::has('Subscriptions')) {
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.subscriptions.%')
                ->orWhere('name', 'LIKE', 'user.invoices.%');
        }

        if (Module::has('Deposits')) {
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.deposits.%');
        }

        if (Module::has('Transactions')) {
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.transactions.%');
        }

        if (Module::has('Wallet')) {
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.profile.%');
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.wallet.%');
        }

        if (Module::has('Withdrawals')) {
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.withdrawals.%');
        }

        if (Module::has('Tickets')) {
            $permissions = $permissions->orWhere('name', 'LIKE', 'user.tickets.%');
        }

        $permissions = $permissions->pluck('name');

        $role->givePermissionTo($permissions);
    }
}
