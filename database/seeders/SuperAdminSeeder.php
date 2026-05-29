<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Crea roles de sistema (super-admin, admin), permisos por módulo
     * y asigna super-admin a ochavez@gptservices.com.
     */
    public function run(): void
    {
        // ── 1. Roles de sistema ───────────────────────────────────────────
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'display_name' => 'Super Administrador',
                'description'  => 'Acceso total al sistema sin restricciones.',
            ]
        );

        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrador',
                'description'  => 'Administración general de módulos y contenido.',
            ]
        );

        // ── 2. Permisos por módulo ────────────────────────────────────────
        $permissions = [
            // Estadísticas / dashboard admin
            ['name' => 'view-admin-stats',       'display_name' => 'Ver estadísticas admin',        'description' => 'Acceso al panel de estadísticas administrativas'],
            ['name' => 'export-admin-stats',      'display_name' => 'Exportar estadísticas',         'description' => 'Exportar reportes del panel admin'],

            // Empleados
            ['name' => 'view-employees',          'display_name' => 'Ver empleados',                 'description' => 'Listar y consultar empleados'],
            ['name' => 'manage-employees',        'display_name' => 'Gestionar empleados',           'description' => 'Crear, editar, importar y eliminar empleados'],

            // Soporte técnico (admin)
            ['name' => 'view-tech-support-admin', 'display_name' => 'Ver gestión de soporte técnico','description' => 'Ver el panel de administración de soporte'],
            ['name' => 'manage-tech-support',     'display_name' => 'Gestionar soporte técnico',     'description' => 'CRUD de categorías y problemas de soporte'],

            // Documentos
            ['name' => 'reindex-documents',       'display_name' => 'Reindexar documentos',         'description' => 'Ejecutar reindexado del buscador de documentos'],
            ['name' => 'manage-documents',        'display_name' => 'Gestionar documentos',          'description' => 'Subir, editar y eliminar documentos corporativos'],

            // Usuarios
            ['name' => 'view-users',              'display_name' => 'Ver usuarios',                  'description' => 'Listar usuarios del sistema'],
            ['name' => 'manage-users',            'display_name' => 'Gestionar usuarios',            'description' => 'Administrar cuentas de usuario y sus roles'],

            // Noticias
            ['name' => 'manage-news',             'display_name' => 'Gestionar noticias',            'description' => 'Crear, editar y publicar noticias corporativas'],

            // Configuración del sistema
            ['name' => 'manage-system-config',    'display_name' => 'Configuración del sistema',     'description' => 'Ajustar parámetros globales del hub'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $data) {
            $createdPermissions[] = Permission::firstOrCreate(
                ['name' => $data['name']],
                ['display_name' => $data['display_name'], 'description' => $data['description']]
            );
        }

        // ── 3. Asignar TODOS los permisos a super-admin ───────────────────
        $superAdmin->syncPermissions($createdPermissions);

        // Permisos de admin (todo excepto manage-users y manage-system-config)
        $adminPermissionNames = [
            'view-admin-stats', 'export-admin-stats',
            'view-employees', 'manage-employees',
            'view-tech-support-admin', 'manage-tech-support',
            'reindex-documents', 'manage-documents',
            'view-users',
            'manage-news',
        ];
        $adminPermissions = Permission::whereIn('name', $adminPermissionNames)->get();
        $admin->syncPermissions($adminPermissions);

        // ── 4. Asignar rol super-admin a ochavez@gptservices.com ──────────
        $user = User::where('email', 'ochavez@gptservices.com')->first();

        if ($user) {
            // Evitar duplicados: solo asignar si aún no lo tiene
            if (!$user->hasRole('super-admin')) {
                $user->addRole($superAdmin);
            }
            $this->command->info("✅ Rol super-admin asignado a {$user->name} ({$user->email})");
        } else {
            $this->command->warn('⚠️  Usuario ochavez@gptservices.com no encontrado. El rol no fue asignado.');
        }

        $this->command->info('✅ Roles: super-admin, admin creados.');
        $this->command->info('✅ ' . count($createdPermissions) . ' permisos creados y asignados.');
    }
}
