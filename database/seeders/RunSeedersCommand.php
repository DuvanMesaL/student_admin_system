<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class RunSeedersCommand extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This is a helper class to run all seeders in the correct order.
     * You can run this by executing: php artisan db:seed --class=RunSeedersCommand
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Student Administration System Database Seeding...');

        // Clear existing data (optional - uncomment if needed)
        // $this->command->info('🗑️  Clearing existing data...');
        // Artisan::call('migrate:fresh');

        $this->command->info('👥 Creating Users...');
        $this->call(UserSeeder::class);

        $this->command->info('🎓 Creating Students...');
        $this->call(StudentSeeder::class);

        $this->command->info('👨‍🏫 Creating Teachers...');
        $this->call(TeacherSeeder::class);

        $this->command->info('📚 Creating Courses...');
        $this->call(CourseSeeder::class);

        $this->command->info('🔗 Assigning Teachers to Courses...');
        $this->call(CourseTeacherSeeder::class);

        $this->command->info('📝 Creating Student Enrollments...');
        $this->call(EnrollmentSeeder::class);

        $this->command->info('📊 Creating Grades...');
        $this->call(GradeSeeder::class);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📋 Summary:');
        $this->command->info('   • 1 Administrator');
        $this->command->info('   • 8 Teachers');
        $this->command->info('   • 20 Students');
        $this->command->info('   • 15 Courses');
        $this->command->info('   • Multiple Enrollments');
        $this->command->info('   • Realistic Grade Data');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   Admin: admin@school.edu / password123');
        $this->command->info('   Teachers: [teacher-email] / teacher123');
        $this->command->info('   Students: [student-email] / student123');
    }
}
