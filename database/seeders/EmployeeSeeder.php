<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_id' => 'EMP001',
                'first_name' => 'María',
                'last_name' => 'González',
                'email' => 'maria.gonzalez@empresa.com',
                'phone' => '+1-555-0101',
                'extension' => '2001',
                'position' => 'Directora de Recursos Humanos',
                'department' => 'Recursos Humanos',
                'location' => 'Oficina Principal - Piso 3',
                'manager_email' => 'ceo@empresa.com',
                'hire_date' => '2020-03-15',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP002',
                'first_name' => 'Carlos',
                'last_name' => 'Rodríguez',
                'email' => 'carlos.rodriguez@empresa.com',
                'phone' => '+1-555-0102',
                'extension' => '3001',
                'position' => 'Gerente de Tecnología',
                'department' => 'Tecnología',
                'location' => 'Oficina Principal - Piso 2',
                'manager_email' => 'cto@empresa.com',
                'hire_date' => '2019-08-22',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP003',
                'first_name' => 'Ana',
                'last_name' => 'Martínez',
                'email' => 'ana.martinez@empresa.com',
                'phone' => '+1-555-0103',
                'extension' => '4001',
                'position' => 'Analista Financiera Senior',
                'department' => 'Finanzas',
                'location' => 'Oficina Principal - Piso 4',
                'manager_email' => 'cfo@empresa.com',
                'hire_date' => '2021-01-10',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP004',
                'first_name' => 'Luis',
                'last_name' => 'García',
                'email' => 'luis.garcia@empresa.com',
                'phone' => '+1-555-0104',
                'extension' => '3002',
                'position' => 'Desarrollador Senior',
                'department' => 'Tecnología',
                'location' => 'Oficina Principal - Piso 2',
                'manager_email' => 'carlos.rodriguez@empresa.com',
                'hire_date' => '2021-06-15',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP005',
                'first_name' => 'Carmen',
                'last_name' => 'López',
                'email' => 'carmen.lopez@empresa.com',
                'phone' => '+1-555-0105',
                'extension' => '2002',
                'position' => 'Especialista en Reclutamiento',
                'department' => 'Recursos Humanos',
                'location' => 'Oficina Principal - Piso 3',
                'manager_email' => 'maria.gonzalez@empresa.com',
                'hire_date' => '2022-02-28',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP006',
                'first_name' => 'Roberto',
                'last_name' => 'Jiménez',
                'email' => 'roberto.jimenez@empresa.com',
                'phone' => '+1-555-0106',
                'extension' => '5001',
                'position' => 'Gerente de Ventas',
                'department' => 'Ventas',
                'location' => 'Oficina Principal - Piso 1',
                'manager_email' => 'cmo@empresa.com',
                'hire_date' => '2020-11-05',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP007',
                'first_name' => 'Elena',
                'last_name' => 'Ruiz',
                'email' => 'elena.ruiz@empresa.com',
                'phone' => '+1-555-0107',
                'extension' => '3003',
                'position' => 'Administradora de Sistemas',
                'department' => 'Tecnología',
                'location' => 'Centro de Datos',
                'manager_email' => 'carlos.rodriguez@empresa.com',
                'hire_date' => '2019-12-01',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP008',
                'first_name' => 'Diego',
                'last_name' => 'Morales',
                'email' => 'diego.morales@empresa.com',
                'phone' => '+1-555-0108',
                'extension' => '6001',
                'position' => 'Coordinador de Operaciones',
                'department' => 'Operaciones',
                'location' => 'Almacén Principal',
                'manager_email' => 'coo@empresa.com',
                'hire_date' => '2021-09-13',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP009',
                'first_name' => 'Patricia',
                'last_name' => 'Vega',
                'email' => 'patricia.vega@empresa.com',
                'phone' => '+1-555-0109',
                'extension' => '7001',
                'position' => 'Asesora Legal',
                'department' => 'Legal',
                'location' => 'Oficina Principal - Piso 5',
                'manager_email' => 'legal@empresa.com',
                'hire_date' => '2020-07-20',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP010',
                'first_name' => 'Sebastián',
                'last_name' => 'Torres',
                'email' => 'sebastian.torres@empresa.com',
                'phone' => '+1-555-0110',
                'extension' => '3004',
                'position' => 'Técnico de Soporte',
                'department' => 'Tecnología',
                'location' => 'Mesa de Ayuda - Piso 1',
                'manager_email' => 'carlos.rodriguez@empresa.com',
                'hire_date' => '2022-08-15',
                'status' => 'active'
            ]
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['employee_id' => $employee['employee_id']],
                $employee
            );
        }
    }
}
