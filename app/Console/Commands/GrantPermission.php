<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class GrantPermission extends Command
{
    protected $signature = 'user:grant-permission
                            {email : Correo del usuario}
                            {permission : Nombre del permiso (ej. manage-news) o "admin" para asignar el rol admin}
                            {--revoke : Revocar en lugar de asignar}';

    protected $description = 'Asigna (o revoca) un permiso/rol a un usuario por correo (Laratrust)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name  = $this->argument('permission');
        $revoke = (bool) $this->option('revoke');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("No existe un usuario con el correo {$email}");
            return self::FAILURE;
        }

        // Permitir asignar el rol admin directamente.
        if ($name === 'admin') {
            $role = Role::where('name', 'admin')->first();
            if (!$role) {
                $this->error('El rol admin no existe. Ejecuta primero: php artisan db:seed --class=PermissionRoleSeeder');
                return self::FAILURE;
            }
            $revoke ? $user->removeRole($role) : $user->addRole($role);
            $this->info(($revoke ? 'Revocado' : 'Asignado') . " rol admin a {$email}");
            return self::SUCCESS;
        }

        $permission = Permission::where('name', $name)->first();
        if (!$permission) {
            $this->error("El permiso '{$name}' no existe. Ejecuta primero: php artisan db:seed --class=PermissionRoleSeeder");
            return self::FAILURE;
        }

        $revoke ? $user->removePermission($permission) : $user->givePermission($permission);
        $this->info(($revoke ? 'Revocado' : 'Asignado') . " permiso '{$name}' a {$email}");

        return self::SUCCESS;
    }
}
