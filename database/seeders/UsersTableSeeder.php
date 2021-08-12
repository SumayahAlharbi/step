<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // find or create the admin user
        User::firstOrCreate(['email' => 'admin@admin.com'],['name' => 'Admin','password' => bcrypt('password'),'remember_token' => null]);

        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT users ON');
        }
        //User::insert($users);
        if (App::environment('production')) {
        DB::unprepared('SET IDENTITY_INSERT users OFF');
        }
    }
}
