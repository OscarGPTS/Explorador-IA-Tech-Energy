<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $gruposRoles = [
            'Administración y Finanzas' => [
                'description' => 'Noticias sobre administración, recursos humanos, finanzas y compras'
            ],
            'Contratos' => [
                'description' => 'Noticias sobre proyectos, contratos comerciales y gestión contractual'
            ],
            'Dirección General' => [
                'description' => 'Noticias corporativas, estrategia empresarial y marketing'
            ],
            'Ingeniería y Manufactura' => [
                'description' => 'Noticias sobre ingeniería, manufactura y procesos productivos'
            ],
            'Operaciones' => [
                'description' => 'Noticias sobre operaciones, soldadura, mantenimiento y procesos técnicos'
            ],
            'QHSE' => [
                'description' => 'Noticias sobre calidad, seguridad, salud ocupacional y medio ambiente'
            ],
            'Servicios Generales y Almacén' => [
                'description' => 'Noticias sobre logística, IT, almacén y servicios generales'
            ],
            'Energía y Tecnología' => [
                'description' => 'Noticias sobre sector energético, tecnología y innovación'
            ],
            'Economía Nacional' => [
                'description' => 'Noticias económicas, financieras y del sector empresarial mexicano'
            ],
            'Industria y Negocios' => [
                'description' => 'Noticias de la industria, negocios y mercados'
            ]
        ];

        foreach ($gruposRoles as $name => $data) {
            DB::table('news_type')->insertOrIgnore([
                'name' => $name,
                'description' => $data['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('Tipos de noticias creados exitosamente.');
    }
}