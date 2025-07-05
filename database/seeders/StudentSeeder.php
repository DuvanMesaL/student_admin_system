<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentUsers = User::where('role', 'student')->get();

        $studentData = [
            ['level' => 'freshman', 'birth_date' => '2004-03-15', 'phone' => '555-0101', 'address' => '123 Oak St, Springfield, IL 62701'],
            ['level' => 'sophomore', 'birth_date' => '2003-07-22', 'phone' => '555-0102', 'address' => '456 Pine Ave, Springfield, IL 62702'],
            ['level' => 'junior', 'birth_date' => '2002-11-08', 'phone' => '555-0103', 'address' => '789 Maple Dr, Springfield, IL 62703'],
            ['level' => 'senior', 'birth_date' => '2001-05-14', 'phone' => '555-0104', 'address' => '321 Elm St, Springfield, IL 62704'],
            ['level' => 'freshman', 'birth_date' => '2004-09-30', 'phone' => '555-0105', 'address' => '654 Cedar Ln, Springfield, IL 62705'],
            ['level' => 'sophomore', 'birth_date' => '2003-12-03', 'phone' => '555-0106', 'address' => '987 Birch Rd, Springfield, IL 62706'],
            ['level' => 'junior', 'birth_date' => '2002-04-18', 'phone' => '555-0107', 'address' => '147 Walnut Ave, Springfield, IL 62707'],
            ['level' => 'senior', 'birth_date' => '2001-08-25', 'phone' => '555-0108', 'address' => '258 Cherry St, Springfield, IL 62708'],
            ['level' => 'freshman', 'birth_date' => '2004-01-12', 'phone' => '555-0109', 'address' => '369 Ash Dr, Springfield, IL 62709'],
            ['level' => 'sophomore', 'birth_date' => '2003-06-07', 'phone' => '555-0110', 'address' => '741 Hickory Ln, Springfield, IL 62710'],
            ['level' => 'junior', 'birth_date' => '2002-10-21', 'phone' => '555-0111', 'address' => '852 Poplar Ave, Springfield, IL 62711'],
            ['level' => 'senior', 'birth_date' => '2001-02-28', 'phone' => '555-0112', 'address' => '963 Willow St, Springfield, IL 62712'],
            ['level' => 'freshman', 'birth_date' => '2004-07-16', 'phone' => '555-0113', 'address' => '159 Sycamore Dr, Springfield, IL 62713'],
            ['level' => 'sophomore', 'birth_date' => '2003-11-09', 'phone' => '555-0114', 'address' => '357 Magnolia Ln, Springfield, IL 62714'],
            ['level' => 'junior', 'birth_date' => '2002-03-26', 'phone' => '555-0115', 'address' => '468 Dogwood Ave, Springfield, IL 62715'],
            ['level' => 'senior', 'birth_date' => '2001-09-13', 'phone' => '555-0116', 'address' => '579 Redwood St, Springfield, IL 62716'],
            ['level' => 'freshman', 'birth_date' => '2004-05-04', 'phone' => '555-0117', 'address' => '681 Spruce Dr, Springfield, IL 62717'],
            ['level' => 'sophomore', 'birth_date' => '2003-08-19', 'phone' => '555-0118', 'address' => '792 Fir Ln, Springfield, IL 62718'],
            ['level' => 'junior', 'birth_date' => '2002-12-11', 'phone' => '555-0119', 'address' => '814 Cypress Ave, Springfield, IL 62719'],
            ['level' => 'senior', 'birth_date' => '2001-04-02', 'phone' => '555-0120', 'address' => '925 Juniper St, Springfield, IL 62720'],
        ];

        foreach ($studentUsers as $index => $user) {
            Student::create([
                'user_id' => $user->id,
                'student_code' => 'STU2024' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'document_number' => '12345' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'first_name' => explode(' ', $user->name)[0] ?? 'Nombre',
                'last_name' => explode(' ', $user->name)[1] ?? 'Apellido',
                'birth_date' => $studentData[$index]['birth_date'],
                'phone' => $studentData[$index]['phone'],
                'address' => $studentData[$index]['address'],
                'level' => $studentData[$index]['level'],
                'status' => 'active',
            ]);
        }
    }
}
