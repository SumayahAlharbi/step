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
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'team_create',
            ],
            [
                'id'    => 18,
                'title' => 'team_edit',
            ],
            [
                'id'    => 19,
                'title' => 'team_show',
            ],
            [
                'id'    => 20,
                'title' => 'team_delete',
            ],
            [
                'id'    => 21,
                'title' => 'team_access',
            ],
            [
                'id'    => 22,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 23,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 24,
                'title' => 'tracking_access',
            ],
            [
                'id'    => 25,
                'title' => 'strategic_plan_create',
            ],
            [
                'id'    => 26,
                'title' => 'strategic_plan_edit',
            ],
            [
                'id'    => 27,
                'title' => 'strategic_plan_show',
            ],
            [
                'id'    => 28,
                'title' => 'strategic_plan_delete',
            ],
            [
                'id'    => 29,
                'title' => 'strategic_plan_access',
            ],
            [
                'id'    => 30,
                'title' => 'goal_create',
            ],
            [
                'id'    => 31,
                'title' => 'goal_edit',
            ],
            [
                'id'    => 32,
                'title' => 'goal_show',
            ],
            [
                'id'    => 33,
                'title' => 'goal_delete',
            ],
            [
                'id'    => 34,
                'title' => 'goal_access',
            ],
            [
                'id'    => 35,
                'title' => 'project_create',
            ],
            [
                'id'    => 36,
                'title' => 'project_edit',
            ],
            [
                'id'    => 37,
                'title' => 'project_show',
            ],
            [
                'id'    => 38,
                'title' => 'project_delete',
            ],
            [
                'id'    => 39,
                'title' => 'project_access',
            ],
            [
                'id'    => 40,
                'title' => 'initiative_create',
            ],
            [
                'id'    => 41,
                'title' => 'initiative_edit',
            ],
            [
                'id'    => 42,
                'title' => 'initiative_show',
            ],
            [
                'id'    => 43,
                'title' => 'initiative_delete',
            ],
            [
                'id'    => 44,
                'title' => 'initiative_access',
            ],
            [
                'id'    => 45,
                'title' => 'action_plan_create',
            ],
            [
                'id'    => 46,
                'title' => 'action_plan_edit',
            ],
            [
                'id'    => 47,
                'title' => 'action_plan_show',
            ],
            [
                'id'    => 48,
                'title' => 'action_plan_delete',
            ],
            [
                'id'    => 49,
                'title' => 'action_plan_access',
            ],
            [
                'id'    => 50,
                'title' => 'risk_create',
            ],
            [
                'id'    => 51,
                'title' => 'risk_edit',
            ],
            [
                'id'    => 52,
                'title' => 'risk_show',
            ],
            [
                'id'    => 53,
                'title' => 'risk_delete',
            ],
            [
                'id'    => 54,
                'title' => 'risk_access',
            ],
            [
                'id'    => 55,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 56,
                'title' => 'strategic_plan_archive',
            ],
        ];
        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT permissions ON');
        }
        Permission::insert($permissions);
        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT permissions OFF');
        }
    }
}
