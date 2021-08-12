<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        //app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::firstOrCreate(['title' => 'user_management_access']);
        Permission::firstOrCreate(['title' => 'permission_create']);
        Permission::firstOrCreate(['title' => 'permission_edit']);
        Permission::firstOrCreate(['title' => 'permission_show']);
        Permission::firstOrCreate(['title' => 'permission_delete']);
        Permission::firstOrCreate(['title' => 'permission_access']);
        Permission::firstOrCreate(['title' => 'role_create']);
        Permission::firstOrCreate(['title' => 'role_edit']);
        Permission::firstOrCreate(['title' => 'role_show']);
        Permission::firstOrCreate(['title' => 'role_delete']);
        Permission::firstOrCreate(['title' => 'role_access']);
        Permission::firstOrCreate(['title' => 'user_create']);
        Permission::firstOrCreate(['title' => 'user_edit']);
        Permission::firstOrCreate(['title' => 'user_show']);
        Permission::firstOrCreate(['title' => 'user_delete']);
        Permission::firstOrCreate(['title' => 'user_access']);
        Permission::firstOrCreate(['title' => 'team_create']);
        Permission::firstOrCreate(['title' => 'team_edit']);
        Permission::firstOrCreate(['title' => 'team_show']);
        Permission::firstOrCreate(['title' => 'team_delete']);
        Permission::firstOrCreate(['title' => 'team_access']);
        Permission::firstOrCreate(['title' => 'audit_log_show']);
        Permission::firstOrCreate(['title' => 'audit_log_access']);
        Permission::firstOrCreate(['title' => 'tracking_access']);
        Permission::firstOrCreate(['title' => 'strategic_plan_create']);
        Permission::firstOrCreate(['title' => 'strategic_plan_edit']);
        Permission::firstOrCreate(['title' => 'strategic_plan_show']);
        Permission::firstOrCreate(['title' => 'strategic_plan_delete']);
        Permission::firstOrCreate(['title' => 'strategic_plan_access']);
        Permission::firstOrCreate(['title' => 'goal_create']);
        Permission::firstOrCreate(['title' => 'goal_edit']);
        Permission::firstOrCreate(['title' => 'goal_show']);
        Permission::firstOrCreate(['title' => 'goal_delete']);
        Permission::firstOrCreate(['title' => 'goal_access']);
        Permission::firstOrCreate(['title' => 'project_create']);
        Permission::firstOrCreate(['title' => 'project_edit']);
        Permission::firstOrCreate(['title' => 'project_show']);
        Permission::firstOrCreate(['title' => 'project_delete']);
        Permission::firstOrCreate(['title' => 'project_access']);
        Permission::firstOrCreate(['title' => 'initiative_create']);
        Permission::firstOrCreate(['title' => 'initiative_edit']);
        Permission::firstOrCreate(['title' => 'initiative_show']);
        Permission::firstOrCreate(['title' => 'initiative_delete']);
        Permission::firstOrCreate(['title' => 'initiative_access']);
        Permission::firstOrCreate(['title' => 'action_plan_create']);
        Permission::firstOrCreate(['title' => 'action_plan_edit']);
        Permission::firstOrCreate(['title' => 'action_plan_show']);
        Permission::firstOrCreate(['title' => 'action_plan_delete']);
        Permission::firstOrCreate(['title' => 'action_plan_access']);
        Permission::firstOrCreate(['title' => 'risk_create']);
        Permission::firstOrCreate(['title' => 'risk_edit']);
        Permission::firstOrCreate(['title' => 'risk_show']);
        Permission::firstOrCreate(['title' => 'risk_delete']);
        Permission::firstOrCreate(['title' => 'risk_access']);
        Permission::firstOrCreate(['title' => 'profile_password_edit']);
        Permission::firstOrCreate(['title' => 'strategic_plan_archive']);

        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT permissions ON');
        }
        //Permission::insert($permissions);
        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT permissions OFF');
        }
    }
}
