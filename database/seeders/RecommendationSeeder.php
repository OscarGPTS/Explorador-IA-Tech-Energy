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

            $typeId = DB::table('recommendations_type')->insertGetId([
                'name' => $area,
                'description' => "Recomendaciones para el área de $area",
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($subAreas as $subArea) {
                DB::table('recommendations')->insert([
                    'title' => "Recomendación para $subArea",
                    'description' => "Consejo útil y guía para el área de $subArea",
                    'content' => "Contenido detallado de la recomendación para $subArea...",
                    'image' => null, 
                    'recommendation_type_id' => $typeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Asignar el tipo de recomendación al usuario con id = 2
            DB::table('user_recommendations')->insert([
                'user_id' => 2,
                'recommendation_type_id' => $typeId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
