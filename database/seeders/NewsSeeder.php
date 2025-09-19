<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
     public function run()
    {
        $now = Carbon::now();

        $newsTypes = [
            'Administración y Finanzas' => [
                'Políticas de vacaciones 2025',
                'Reformas en el sistema de nómina',
                'Actualización de beneficios para empleados',
                'Guía de administración de gastos internos'
            ],
            'Contratos' => [
                'Actualización de contratos de proveedores',
                'Nuevo acuerdo comercial con clientes clave',
                'Revisión de proyectos en curso'
            ],
            'Dirección General' => [
                'Comunicado corporativo sobre objetivos 2025',
                'Campaña de marketing interna',
                'Estrategias de crecimiento y expansión'
            ],
            'Ingeniería y Manufactura' => [
                'Implementación de nueva línea de producción',
                'Mejoras en procesos de manufactura',
                'Actualización de normas de ingeniería'
            ],
            'Operaciones' => [
                'Plan de mantenimiento especializado',
                'Optimización de procesos de soldadura y HT & LS',
                'Nuevas políticas de operaciones'
            ],
            'QHSE' => [
                'Auditoría de calidad 2025',
                'Actualización de normas de seguridad industrial',
                'Cumplimiento ambiental y regulatorio'
            ],
            'Servicios Generales y Almacén' => [
                'Actualización de logística interna',
                'Políticas de seguridad patrimonial',
                'Mejoras en gestión de IT y soporte',
                'Optimización de almacén y stock'
            ],
        ];

        foreach ($newsTypes as $typeName => $titles) {

            $typeId = DB::table('news_type')->insertGetId([
                'name' => $typeName,
                'description' => "Noticias y novedades para el área de $typeName",
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($titles as $title) {
                DB::table('news')->insert([
                    'title' => $title,
                    'description' => "Descripción breve de la noticia: $title",
                    'content' => "Contenido detallado de la noticia relacionada con $title. Información completa, ejemplos y recomendaciones para los colaboradores del área.",
                    'image' => null,
                    'news_type_id' => $typeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('user_news')->insert([
                'user_id' => 2,
                'news_type_id' => $typeId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

    }
}
