<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TechSupportConversation;
use App\Models\TechSupportCategory;
use App\Models\TechSupportProblem;

class MigrateTechSupportConversations extends Command
{
    protected $signature = 'tech-support:migrate-conversations';
    protected $description = 'Migrar conversaciones existentes a la nueva estructura dinámica';

    public function handle()
    {
        $this->info('Iniciando migración de conversaciones de soporte técnico...');

        // Mapeo de categorías antiguas a nuevas
        $categoryMapping = [
            'computer' => 'computadora',
            'internet' => 'internet',
            'email' => 'correo',
            'printer' => 'impresora',
            'software' => 'software',
            'access' => 'acceso',
            'google' => 'software', // Google Suite -> Software
            'office' => 'software', // Microsoft Office -> Software
            'other' => null // Se dejará sin categoría dinámica
        ];

        $conversations = TechSupportConversation::whereNull('tech_support_category_id')->get();
        $this->info("Encontradas {$conversations->count()} conversaciones para migrar.");

        $migrated = 0;
        $errors = 0;

        foreach ($conversations as $conversation) {
            try {
                $oldCategory = $conversation->problem_category;
                
                if (isset($categoryMapping[$oldCategory])) {
                    $newCategoryName = $categoryMapping[$oldCategory];
                    
                    if ($newCategoryName) {
                        $category = TechSupportCategory::where('name', $newCategoryName)->first();
                        
                        if ($category) {
                            $conversation->update([
                                'tech_support_category_id' => $category->id,
                                'problem_category_dynamic' => $newCategoryName
                            ]);
                            
                            $migrated++;
                        } else {
                            $this->warn("Categoría no encontrada: {$newCategoryName}");
                            $errors++;
                        }
                    } else {
                        // Categoría "other" - solo actualizar el campo dinámico
                        $conversation->update([
                            'problem_category_dynamic' => 'otros'
                        ]);
                        $migrated++;
                    }
                } else {
                    $this->warn("Categoría no mapeada: {$oldCategory}");
                    $errors++;
                }
            } catch (\Exception $e) {
                $this->error("Error migrando conversación {$conversation->id}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("Migración completada:");
        $this->info("- Conversaciones migradas: {$migrated}");
        $this->info("- Errores: {$errors}");

        return 0;
    }
}
