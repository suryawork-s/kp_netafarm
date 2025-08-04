<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Departments
        $departmentsData = [
            [
                'name' => 'Information Technology',
            ],
            [
                'name' => 'Human Resource',
            ],
            [
                'name' => 'Marketing',
            ],
            [
                'name' => 'General Affair',
            ],
        ];

        // Data Positions
        $positionsData = [
            [
                'name' => 'Manager',
            ],
            [
                'name' => 'Supervisor',
            ],
            [
                'name' => 'Staff',
            ],
        ];

        // Create Positions first
        $positions = [];
        foreach ($positionsData as $positionData) {
            $positions[] = Position::create($positionData);
        }

        // Create Departments and Sync with Positions
        foreach ($departmentsData as $departmentData) {
            $department = Department::create($departmentData);

            // Sync positions with department
            $department->positions()->sync(
                array_map(function ($position) {
                    return $position->id;
                }, $positions)
            );
        }
    }
}
