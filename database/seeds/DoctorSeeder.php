<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Faker\Factory as faker;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = faker::create();
        $user = [
            'first_name' => 'Doctor',
            'last_name' => 'Sislac',
            'mobile' => '5142323114',
            'profile_photo' => 'Female_doctor.png',
            'email' => 'doctor@themesbrand.website',
            'password' => 'doctor@123456',
        ];
        $user = Sentinel::registerAndActivate($user);
        $role = Sentinel::findRoleBySlug('doctor');
        $role->users()->attach($user);

        DB::table('doctors')->insert([
            'user_id' => 2,
            'slot_time' => 25,
            'doctor_cpf' => rand(10000000, 20000000),
            'doctor_cns' => rand(10000000, 20000000),
            'class_council' => 'Health Board',
            'issuing_state' => 'Gujarat',
            'counsil_number' => rand(10000000, 20000000),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        foreach (range(3, 15) as $item) {
            $fakerName = $faker->name;
            $user = [
                'first_name' => Str::before($fakerName, ' '),
                'last_name' => Str::after($fakerName, ' '),
                'mobile' => rand(1000000000, 2000000000),
                'profile_photo' => 'dr-avatar-' . $item . '.jpg',
                'email' => $faker->safeEmail,
                'password' => 'doctor@123456',
            ];
            $user = Sentinel::registerAndActivate($user);
            $role = Sentinel::findRoleBySlug('doctor');
            $role->users()->attach($user);
        }
        foreach (range(3, 15) as $item) {
            DB::table('doctors')->insert([
                'user_id' => $item,
                'slot_time' => 25,
                'doctor_cpf' => rand(10000000, 20000000),
                'doctor_cns' => rand(10000000, 20000000),
                'class_council' => 'Health Board',
                'issuing_state' => 'Gujarat',
                'counsil_number' => rand(10000000, 20000000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
