<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\profile;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*   Usuario Admin  */
        $userAdmin = User::updateOrCreate(
                ['email' => 'admin@email.com'],
                [
                    'name' => 'User Admin',
                    'password' =>  Hash::make('usuario12345'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );

            $userAdminProfile = $userAdmin->profile()->updateOrCreate([
                'firstname'  => 'Usuario Admin',
                'lastname'   => 'Administrador principal',
                'phone'   => '+532154875445',
                'birthdate' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $userAdmin->assignRole('Admin');

            /*Usuario Driver*/

            $userDriver = User::updateOrCreate(
                ['email' => 'driver@email.com'],
                [
                    'name' => 'User Driver',
                    'password' =>  Hash::make('usuario12345'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
            $userDriver->assignRole('Driver');
            $userDriverProfile = $userDriver->profile()->updateOrCreate([
                'firstname'  => 'Usuario',
                'lastname'   => 'Driver',
                'phone'   => '+532154875445',
                'birthdate' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

    }
}
