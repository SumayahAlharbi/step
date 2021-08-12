<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['title' => 'admin']);
        Role::firstOrCreate(['title' => 'responsible']);
        Role::firstOrCreate(['title' => 'owner']);

        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT roles ON');
        }
        //Role::insert($roles);
        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT roles OFF');
        }
    }
}
