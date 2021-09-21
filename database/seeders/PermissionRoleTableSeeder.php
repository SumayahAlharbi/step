<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::firstOrCreate(['title' => 'admin'])->permissions()->sync($admin_permissions->pluck('id'));

        $responsible_permissions =  Permission::where('title','=','profile_password_edit')->get();

        $responsible_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_'
            && substr($permission->title, 0, 5) != 'role_'
            && substr($permission->title, 0, 11) != 'permission_'
            && substr($permission->title, 0, 5) != 'team_'
            && substr($permission->title, 0, 6) != 'audit_'
            && substr($permission->title, 0, 10) != 'strategic_'
            && $permission->title != 'goal_create'
            && $permission->title != 'goal_edit'
            && $permission->title != 'goal_delete'
            && $permission->title != 'project_create'
            && $permission->title != 'project_edit'
            && $permission->title != 'project_delete'
            && $permission->title != 'initiative_create'
            && $permission->title != 'initiative_delete'
            && substr($permission->title, 0, 7) != 'action_'
            && substr($permission->title, 0, 5) != 'risk_'
            && substr($permission->title, 0, 8) != 'profile_';
        });

        Role::firstOrCreate(['title' => 'responsible'])->permissions()->sync($responsible_permissions);

        $owner_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_'
            && substr($permission->title, 0, 5) != 'role_'
            && substr($permission->title, 0, 11) != 'permission_'
            && substr($permission->title, 0, 5) != 'team_'
            && substr($permission->title, 0, 6) != 'audit_'
            && substr($permission->title, 0, 10) != 'strategic_'
            && $permission->title != 'goal_create'
            && $permission->title != 'goal_edit'
            && $permission->title != 'goal_delete'
            && $permission->title != 'project_create'
            && $permission->title != 'project_edit'
            && $permission->title != 'project_delete'
            && $permission->title != 'initiative_create'
            && $permission->title != 'initiative_delete'
            && $permission->title != 'initiative_edit'
            && substr($permission->title, 0, 7) != 'action_'
            && substr($permission->title, 0, 5) != 'risk_'
            && substr($permission->title, 0, 8) != 'profile_';
        });

        Role::firstOrCreate(['title' => 'owner'])->permissions()->sync($owner_permissions);

    }
}
