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
        $now = Carbon::now();

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

            // Crear tipo de recomendación por área
            $typeId = DB::table('recommendations_type')->insertGetId([
                'name' => $area,
                'description' => "Recomendaciones para el área de $area",
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($subAreas as $subArea) {
                // Crear recomendación por subárea
                $recommendationId = DB::table('recommendations')->insertGetId([
                    'title' => "Recomendación para $subArea",
                    'description' => "Consejo útil y guía para el área de $subArea",
                    'content' => "Contenido detallado de la recomendación para $subArea...",
                    'image' => null, // puedes colocar una URL si quieres
                    'recommendation_type_id' => $typeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Asignar la recomendación al usuario con id = 2
                DB::table('user_recommendations')->insert([
                    'user_id' => 2,
                    'recommendation_id' => $recommendationId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('Seeder de recomendaciones completado');
    }
}
