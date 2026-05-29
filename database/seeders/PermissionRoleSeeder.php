<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Crea los permisos granulares para administrar Recomendaciones y Noticias,
     * y el rol "admin" (que ya se usa en el código pero no se sembraba).
     */
    public function run(): void
    {
        $permissions = [
            'manage-recommendations' => 'Administrar recomendaciones',
            'manage-news'            => 'Administrar noticias',
            'manage-scraping'        => 'Administrar fuentes y ejecutar scraping',
        ];

        $permissionModels = [];
        foreach ($permissions as $name => $displayName) {
            $permissionModels[$name] = Permission::firstOrCreate(
                ['name' => $name],
                [
                    'display_name' => $displayName,
                    'description'  => $displayName,
                ]
            );
        }

        // Rol admin: acceso total a estos módulos.
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrador',
                'description'  => 'Acceso total al sistema',
            ]
        );

        $admin->syncPermissions(array_values($permissionModels));

        $this->command?->info('Permisos y rol admin sembrados correctamente.');
        $this->command?->line('Asigna a una persona designada con: php artisan user:grant-permission {email} manage-news');
    }
}
