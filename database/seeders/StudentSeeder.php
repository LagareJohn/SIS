<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = [
            'Adrian', 'Angelo', 'Andrei', 'Bianca', 'Christian', 'Christine', 'Daniel', 'Diana', 'Emmanuel', 'Erika',
            'Francis', 'Gabriel', 'Hannah', 'Isabella', 'John', 'Jasmine', 'Kevin', 'Katherine', 'Lance', 'Louise',
            'Marco', 'Michelle', 'Nathan', 'Nicole', 'Oliver', 'Patricia', 'Paolo', 'Rachel', 'Ryan', 'Sarah',
            'Samuel', 'Sofia', 'Timothy', 'Trisha', 'Vincent', 'Victoria', 'William', 'Ysabel', 'Zachary', 'Zoe'
        ];

        $lastNames = [
            'Aguilar', 'Bautista', 'Chua', 'De Leon', 'Evangelista', 'Fernandez', 'Guevarra', 'Hernandez', 'Ignacio', 'Jimenez',
            'Kapunan', 'Lim', 'Mendoza', 'Natividad', 'Ocampo', 'Pascual', 'Quiambao', 'Ramos', 'Santos', 'Tan',
            'Uy', 'Valencia', 'Wong', 'Xavier', 'Yap', 'Zamora', 'Alcantara', 'Bonifacio', 'Castillo', 'Delos Santos',
            'Enriquez', 'Francisco', 'Garcia', 'Herrera', 'Ilagan', 'Jacinto', 'Kho', 'Laurel', 'Macaraeg', 'Navarro'
        ];

        $courses = ['BSCS', 'BSIT', 'BSIS', 'BSBA', 'BSEd', 'BSN'];
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        
        // Generate 50 students with BukSU format IDs
        for ($i = 1; $i <= 50; $i++) {
            // Generate student ID in format YYNN##### (YY=year, NN=department, #####=sequence)
            $year = rand(19, 23); // Years 2019-2023
            $dept = str_pad(rand(1, 5), 2, '0', STR_PAD_LEFT);
            $sequence = str_pad($i, 5, '0', STR_PAD_LEFT);
            
            $studentId = $year . $dept . $sequence;
            
            // Generate a random name
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            
            // Select a random course and year level
            $course = $courses[array_rand($courses)];
            $yearLevel = $yearLevels[array_rand($yearLevels)];
            
            User::create([
                'student_id' => $studentId,
                'name' => $name,
                'email' => $studentId . '@student.buksu.edu.ph',
                'password' => Hash::make('123123123'),
                'role' => 'student',
                'course' => $course,
                'year_level' => $yearLevel,
                'email_verified_at' => now(),
            ]);
        }
    }
}
