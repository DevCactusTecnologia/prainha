<?php

use Database\Seeders\MedicalInfoSeeder;
use Database\Seeders\NotificationTypeSeeder;
use Database\Seeders\DoctorAvailableDaySeeder;
use Database\Seeders\DoctorAvailableSlotSeeder;
use Database\Seeders\DoctorAvailableTimeSeeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Faker\Factory as faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            DoctorSeeder::class,
            PatientSeeder::class,
            ReceptionistSeeder::class,
            // BiomedicalSeeder::class,
            MedicalInfoSeeder::class,
            NotificationTypeSeeder::class,
            DoctorAvailableDaySeeder::class,
            DoctorAvailableTimeSeeder::class,
            DoctorAvailableSlotSeeder::class,
        ]);

        // create biomedical list 
        $faker = faker::create();
        $user = [
            'first_name' => 'Biomedical',
            'last_name' => 'Sislac',
            'mobile' => '5142323114',
            'profile_photo' => 'Male_receptionist.png',
            'email' => 'biomedical@themesbrand.website',
            'password' => 'biomedical@123456',
        ];
        $user = Sentinel::registerAndActivate($user);
        $role = Sentinel::findRoleBySlug('biomedical');
        $role->users()->attach($user);
        DB::table('biomedicalist')->insert([
            'user_id' => $user->id,
            'cpf' => rand(100000, 200000),
            'cns' =>  rand(1000000, 2000000),
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $i = 1;
        foreach (range(47, 60) as $item) {
            $fakerName = $faker->name;
            $user = [
                'first_name' => Str::before($fakerName, ' '),
                'last_name' => Str::after($fakerName, ' '),
                'mobile' => rand(1000000000, 2000000000),
                'profile_photo' => 're-avatar-1.jpg',
                'email' => $faker->safeEmail,
                'password' => 'biomedical@123456',
            ];
            $user = Sentinel::registerAndActivate($user);
            $role = Sentinel::findRoleBySlug('biomedical');
            $role->users()->attach($user);
            $i++;
        }
        foreach (range(47, 60) as $item) {
            DB::table('biomedicalist')->insert([
                'user_id' => $item,
                'cpf' => rand(100000, 200000),
                'cns' =>  rand(1000000, 2000000),
                'is_deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
