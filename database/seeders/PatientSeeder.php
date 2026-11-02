<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $patient = Patient::create([
                'name' =>fake()->name(),
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber()
            ]);
            $patient->detail()->create([
                'occupation' =>fake()->jobTitle(),
                'date_of_birth' =>fake()->date(),
                'parent_name' =>fake()->name(),
                'parent_occupation' =>fake()->jobTitle(),
                'parent_address' =>fake()->address()
            ]);
        }
    }
}
