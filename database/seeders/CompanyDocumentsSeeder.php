<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyDocument;

class CompanyDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tabla existente
        CompanyDocument::truncate();
        
        // 1. Procedimientos Operativos (Folder padre)
        $procedimientosOperativos = CompanyDocument::create([
            'title' => 'Procedimientos Operativos',
            'is_folder' => true,
            'type' => 'procedure',
            'category' => 'operations',
            'description' => 'Procedimientos operativos de la empresa',
            'owner_email' => 'operaciones@empresa.com',
            'department' => 'Operaciones',
            'access_level' => 'internal',
            'sort_order' => 1,
            'is_active' => true
        ]);
        
        // Hijos de Procedimientos Operativos
        CompanyDocument::create([
            'title' => 'Administración y Finanzas',
            'parent_id' => $procedimientosOperativos->id,
            'is_folder' => false,
            'type' => 'procedure',
            'category' => 'finance',
            'description' => 'Procedimientos de administración y finanzas',
            'external_url' => 'https://sharepoint.empresa.com/docs/admin-finanzas.pdf',
            'owner_email' => 'finanzas@empresa.com',
            'department' => 'Finanzas',
            'access_level' => 'internal',
            'sort_order' => 1,
            'is_active' => true
        ]);
        
        CompanyDocument::create([
            'title' => 'Almacén',
            'parent_id' => $procedimientosOperativos->id,
            'is_folder' => false,
            'type' => 'procedure',
            'category' => 'operations',
            'description' => 'Procedimientos de almacén y logística',
            'external_url' => 'https://sharepoint.empresa.com/docs/almacen.pdf',
            'owner_email' => 'almacen@empresa.com',
            'department' => 'Operaciones',
            'access_level' => 'internal',
            'sort_order' => 2,
            'is_active' => true
        ]);
        
        // 2. Mejora Continua (Folder padre)
        $mejoraContinua = CompanyDocument::create([
            'title' => 'Mejora Continua',
            'is_folder' => true,
            'type' => 'guide',
            'category' => 'operations',
            'description' => 'Documentos de mejora continua y calidad',
            'owner_email' => 'calidad@empresa.com',
            'department' => 'Calidad',
            'access_level' => 'internal',
            'sort_order' => 2,
            'is_active' => true
        ]);
        
        // Hijos de Mejora Continua
        CompanyDocument::create([
            'title' => 'Auditorías del SGI',
            'parent_id' => $mejoraContinua->id,
            'is_folder' => false,
            'type' => 'guide',
            'category' => 'operations',
            'description' => 'Procedimientos para auditorías del Sistema de Gestión Integrado',
            'external_url' => 'https://sharepoint.empresa.com/docs/auditorias-sgi.pdf',
            'owner_email' => 'calidad@empresa.com',
            'department' => 'Calidad',
            'access_level' => 'internal',
            'sort_order' => 1,
            'is_active' => true
        ]);
        
        CompanyDocument::create([
            'title' => 'Gestión de No Conformidades',
            'parent_id' => $mejoraContinua->id,
            'is_folder' => false,
            'type' => 'procedure',
            'category' => 'operations',
            'description' => 'Procedimiento para gestión de no conformidades',
            'external_url' => 'https://sharepoint.empresa.com/docs/no-conformidades.pdf',
            'owner_email' => 'calidad@empresa.com',
            'department' => 'Calidad',
            'access_level' => 'internal',
            'sort_order' => 2,
            'is_active' => true
        ]);
        
        // 3. Documentos independientes (sin padre)
        CompanyDocument::create([
            'title' => 'Manual de Empleado',
            'is_folder' => false,
            'type' => 'manual',
            'category' => 'hr',
            'description' => 'Manual completo para nuevos empleados',
            'external_url' => 'https://sharepoint.empresa.com/docs/manual-empleado.pdf',
            'owner_email' => 'rrhh@empresa.com',
            'department' => 'Recursos Humanos',
            'access_level' => 'internal',
            'sort_order' => 3,
            'is_active' => true
        ]);
        
        CompanyDocument::create([
            'title' => 'Política de Seguridad Informática',
            'is_folder' => false,
            'type' => 'policy',
            'category' => 'it',
            'description' => 'Políticas de seguridad informática de la empresa',
            'external_url' => 'https://sharepoint.empresa.com/docs/seguridad-informatica.pdf',
            'owner_email' => 'it@empresa.com',
            'department' => 'Tecnología',
            'access_level' => 'internal',
            'sort_order' => 4,
            'is_active' => true
        ]);
        
        CompanyDocument::create([
            'title' => 'Reglamento Interno de Trabajo',
            'is_folder' => false,
            'type' => 'policy',
            'category' => 'hr',
            'description' => 'Reglamento interno de trabajo y código de conducta',
            'external_url' => 'https://sharepoint.empresa.com/docs/reglamento-interno.pdf',
            'owner_email' => 'rrhh@empresa.com',
            'department' => 'Recursos Humanos',
            'access_level' => 'internal',
            'sort_order' => 5,
            'is_active' => true
        ]);
        
        CompanyDocument::create([
            'title' => 'Formulario de Solicitud de Vacaciones',
            'is_folder' => false,
            'type' => 'form',
            'category' => 'hr',
            'description' => 'Formulario para solicitar vacaciones',
            'external_url' => 'https://sharepoint.empresa.com/docs/solicitud-vacaciones.pdf',
            'owner_email' => 'rrhh@empresa.com',
            'department' => 'Recursos Humanos',
            'access_level' => 'internal',
            'sort_order' => 6,
            'is_active' => true
        ]);
        
        // 4. Manual de IT (Folder padre)
        $manualIT = CompanyDocument::create([
            'title' => 'Manual de IT',
            'is_folder' => true,
            'type' => 'manual',
            'category' => 'it',
            'description' => 'Manuales técnicos del departamento de IT',
            'owner_email' => 'it@empresa.com',
            'department' => 'Tecnología',
            'access_level' => 'internal',
            'sort_order' => 7,
            'is_active' => true
        ]);
        
        // Hijos de Manual de IT
        CompanyDocument::create([
            'title' => 'Configuración de Red',
            'parent_id' => $manualIT->id,
            'is_folder' => false,
            'type' => 'manual',
            'category' => 'it',
            'description' => 'Manual de configuración de red corporativa',
            'external_url' => 'https://sharepoint.empresa.com/docs/config-red.pdf',
            'owner_email' => 'it@empresa.com',
            'department' => 'Tecnología',
            'access_level' => 'confidential',
            'sort_order' => 1,
            'is_active' => true
        ]);
        
        CompanyDocument::create([
            'title' => 'Backup y Recuperación',
            'parent_id' => $manualIT->id,
            'is_folder' => false,
            'type' => 'manual',
            'category' => 'it',
            'description' => 'Procedimientos de backup y recuperación de datos',
            'external_url' => 'https://sharepoint.empresa.com/docs/backup-recovery.pdf',
            'owner_email' => 'it@empresa.com',
            'department' => 'Tecnología',
            'access_level' => 'confidential',
            'sort_order' => 2,
            'is_active' => true
        ]);
    }
}
