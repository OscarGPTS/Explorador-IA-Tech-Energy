<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        
        $gruposRoles = [
            'Administración y Finanzas' => ['Recursos Humanos', 'Finanzas', 'Compras', 'Administración'],
            'Contratos' => ['Proyectos', 'Comercial', 'Contratos'],
            'Dirección General' => ['Dirección General', 'Marketing'],
            'Ingeniería y Manufactura' => ['Manufactura', 'Ingeniería'],
            'Operaciones' => ['Soldadura', 'HT & LS', 'Mantenimiento Especializado'],
            'QHSE' => ['QHSE', 'Calidad'],
            'Servicios Generales y Almacén' => ['Logística', 'Servicios Generales', 'IT', 'Almacén', 'Seguridad Patrimonial'],
        ];

        foreach ($gruposRoles as $grupoNombre => $roles) {
            $group = Group::firstOrCreate(
                ['name' => strtolower(str_replace(' ', '_', $grupoNombre))],
                [
                    'display_name' => $grupoNombre,
                    'description' => "Área de $grupoNombre"
                ]
            );

            foreach ($roles as $rolNombre) {
                $role = Role::firstOrCreate(
                    ['name' => strtolower(str_replace(' ', '_', $rolNombre))],
                    [
                        'display_name' => $rolNombre,
                        'description' => "Departamento de $rolNombre"
                    ]
                );

                $group->roles()->syncWithoutDetaching($role);
            }
        }



    }
}
