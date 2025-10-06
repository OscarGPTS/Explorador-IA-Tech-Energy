<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TempEmployee;

class TempEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_id' => 'EMP001',
                'first_name' => 'María',
                'last_name' => 'González',
                'email' => 'maria.gonzalez@techenergia.com',
                'phone' => '+52 55 1234 5678',
                'extension' => '101',
                'position' => 'Gerente de Tecnología',
                'department' => 'Tecnología',
                'location' => 'Ciudad de México - Oficina Principal',
                'manager_email' => 'director.general@techenergia.com',
                'hire_date' => '2020-03-15',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP002',
                'first_name' => 'Carlos',
                'last_name' => 'Rodríguez',
                'email' => 'carlos.rodriguez@techenergia.com',
                'phone' => '+52 55 1234 5679',
                'extension' => '102',
                'position' => 'Desarrollador Senior',
                'department' => 'Tecnología',
                'location' => 'Ciudad de México - Oficina Principal',
                'manager_email' => 'maria.gonzalez@techenergia.com',
                'hire_date' => '2021-01-10',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP003',
                'first_name' => 'Ana',
                'last_name' => 'Martínez',
                'email' => 'ana.martinez@techenergia.com',
                'phone' => '+52 55 1234 5680',
                'extension' => '103',
                'position' => 'Analista de Sistemas',
                'department' => 'Tecnología',
                'location' => 'Ciudad de México - Oficina Principal',
                'manager_email' => 'maria.gonzalez@techenergia.com',
                'hire_date' => '2021-06-01',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP004',
                'first_name' => 'Roberto',
                'last_name' => 'López',
                'email' => 'roberto.lopez@techenergia.com',
                'phone' => '+52 55 1234 5681',
                'extension' => '201',
                'position' => 'Gerente de Recursos Humanos',
                'department' => 'Recursos Humanos',
                'location' => 'Ciudad de México - Oficina Principal',
                'manager_email' => 'director.general@techenergia.com',
                'hire_date' => '2019-09-15',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP005',
                'first_name' => 'Lucía',
                'last_name' => 'Hernández',
                'email' => 'lucia.hernandez@techenergia.com',
                'phone' => '+52 55 1234 5682',
                'extension' => '202',
                'position' => 'Especialista en Reclutamiento',
                'department' => 'Recursos Humanos',
                'location' => 'Ciudad de México - Oficina Principal',
                'manager_email' => 'roberto.lopez@techenergia.com',
                'hire_date' => '2020-11-20',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP006',
                'first_name' => 'Diego',
                'last_name' => 'Ramírez',
                'email' => 'diego.ramirez@techenergia.com',
                'phone' => '+52 33 1234 5683',
                'extension' => '301',
                'position' => 'Gerente de Finanzas',
                'department' => 'Finanzas',
                'location' => 'Guadalajara - Sucursal',
                'manager_email' => 'director.general@techenergia.com',
                'hire_date' => '2018-05-10',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP007',
                'first_name' => 'Sofia',
                'last_name' => 'Torres',
                'email' => 'sofia.torres@techenergia.com',
                'phone' => '+52 33 1234 5684',
                'extension' => '302',
                'position' => 'Analista Financiero',
                'department' => 'Finanzas',
                'location' => 'Guadalajara - Sucursal',
                'manager_email' => 'diego.ramirez@techenergia.com',
                'hire_date' => '2021-03-01',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP008',
                'first_name' => 'Miguel',
                'last_name' => 'Vargas',
                'email' => 'miguel.vargas@techenergia.com',
                'phone' => '+52 81 1234 5685',
                'extension' => '401',
                'position' => 'Gerente de Ventas',
                'department' => 'Ventas',
                'location' => 'Monterrey - Sucursal',
                'manager_email' => 'director.general@techenergia.com',
                'hire_date' => '2019-02-20',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP009',
                'first_name' => 'Carmen',
                'last_name' => 'Jiménez',
                'email' => 'carmen.jimenez@techenergia.com',
                'phone' => '+52 81 1234 5686',
                'extension' => '402',
                'position' => 'Ejecutiva de Ventas',
                'department' => 'Ventas',
                'location' => 'Monterrey - Sucursal',
                'manager_email' => 'miguel.vargas@techenergia.com',
                'hire_date' => '2020-08-15',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ],
            [
                'employee_id' => 'EMP010',
                'first_name' => 'Alejandro',
                'last_name' => 'Morales',
                'email' => 'alejandro.morales@techenergia.com',
                'phone' => '+52 55 1234 5687',
                'extension' => '104',
                'position' => 'DevOps Engineer',
                'department' => 'Tecnología',
                'location' => 'Ciudad de México - Oficina Principal',
                'manager_email' => 'maria.gonzalez@techenergia.com',
                'hire_date' => '2022-01-15',
                'status' => 'active',
                'is_active' => true,
                'data_imported_at' => now(),
                'import_source' => 'Initial Seed'
            ]
        ];

        foreach ($employees as $employee) {
            TempEmployee::create($employee);
        }
    }
}