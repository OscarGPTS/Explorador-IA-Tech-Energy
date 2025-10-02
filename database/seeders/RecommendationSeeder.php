<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecommendationSeeder extends Seeder
{
    public function run()
    {
        $now = \Carbon\Carbon::now();

        $gruposRoles = [
            'Administración y Finanzas' => ['Recursos Humanos', 'Finanzas', 'Compras', 'Administración'],
            'Contratos' => ['Proyectos', 'Comercial', 'Contratos'],
            'Dirección General' => ['Dirección General', 'Marketing'],
            'Ingeniería y Manufactura' => ['Manufactura', 'Ingeniería'],
            'Operaciones' => ['Soldadura', 'HT & LS', 'Mantenimiento Especializado'],
            'QHSE' => ['QHSE', 'Calidad'],
            'Servicios Generales y Almacén' => ['Logística', 'Servicios Generales', 'IT', 'Almacén', 'Seguridad Patrimonial'],
        ];

        foreach ($gruposRoles as $area => $subAreas) {

            DB::table('recommendations_type')->insertGetId([
                'name' => $area,
                'description' => "Recomendaciones para el área de $area",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
           
        }
    }
}
