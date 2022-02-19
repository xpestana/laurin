<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table('roles')->upsert([
                [
                    'name' => 'Admin',
                    'guard_name' => 'web', 
                ],
                [
                    'name' => 'Driver',
                    'guard_name' => 'web', 
                ],
            ], ['name', 'guard_name'], ['created_at','updated_at']);
        });
    }
}
