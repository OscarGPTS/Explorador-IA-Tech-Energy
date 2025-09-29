<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use App\Services\RecommendationAiService;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GenerateRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommendations:generate 
                            {--user= : ID del usuario específico}
                            {--role= : Generar solo para usuarios con este rol}
                            {--news : Generar recomendaciones basadas en noticias}
                            {--all : Generar para todos los usuarios}
                            {--force : Forzar generación incluso si ya existen recomendaciones recientes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera recomendaciones automáticas usando IA para usuarios basadas en sus roles y actividad';

    protected $recommendationService;

    /**
     * Create a new command instance.
     */
    public function __construct(/* RecommendationAiService $recommendationService */)
    {
        parent::__construct();
        // $this->recommendationService = $recommendationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🤖 Iniciando generación de recomendaciones IA...');
        
        try {
            // Generar recomendaciones basadas en noticias
            if ($this->option('news')) {
                return $this->generateFromNews();
            }

            // Generar para usuario específico
            if ($userId = $this->option('user')) {
                return $this->generateForUser($userId);
            }

            // Generar para rol específico
            if ($roleName = $this->option('role')) {
                return $this->generateForRole($roleName);
            }

            // Generar para todos los usuarios
            if ($this->option('all')) {
                return $this->generateForAllUsers();
            }

            // Si no se especifica ninguna opción, mostrar ayuda
            $this->showHelp();
            
        } catch (\Exception $e) {
            $this->error("Error durante la generación: {$e->getMessage()}");
            Log::error("Error en comando GenerateRecommendations: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Genera recomendaciones para todos los usuarios
     */
    private function generateForAllUsers()
    {
        $this->info('📊 Generando recomendaciones para todos los usuarios...');
        
        $users = User::with(['roles', 'groups'])->get();
        $this->info("👥 Usuarios encontrados: {$users->count()}");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $successful = 0;
        $failed = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                $recommendations = $this->recommendationService->generateRecommendationsForUser($user);
                $successful++;
                $this->newLine();
                $this->info("✅ {$user->name}: " . count($recommendations) . " recomendaciones generadas");
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "❌ {$user->name}: {$e->getMessage()}";
                Log::error("Error generando recomendaciones para {$user->name}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->displaySummary($successful, $failed, $errors);
        return 0;
    }

    /**
     * Genera recomendaciones para un usuario específico
     */
    private function generateForUser($userId)
    {
        $this->info("👤 Generando recomendaciones para usuario ID: {$userId}");
        
        $user = User::with(['roles', 'groups'])->find($userId);
        
        if (!$user) {
            $this->error("❌ Usuario con ID {$userId} no encontrado");
            return 1;
        }

        try {
            $recommendations = $this->recommendationService->generateRecommendationsForUser($user);
            $this->info("✅ Generadas " . count($recommendations) . " recomendaciones para {$user->name}");
            
            foreach ($recommendations as $rec) {
                $this->line("  📝 {$rec->title}");
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error generando recomendaciones para {$user->name}: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Genera recomendaciones para usuarios con un rol específico
     */
    private function generateForRole($roleName)
    {
        $this->info("🎭 Generando recomendaciones para usuarios con rol: {$roleName}");
        
        $users = User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName)->orWhere('display_name', $roleName);
        })->with(['roles', 'groups'])->get();

        if ($users->isEmpty()) {
            $this->warn("⚠️  No se encontraron usuarios con el rol '{$roleName}'");
            return 0;
        }

        $this->info("👥 Usuarios encontrados: {$users->count()}");

        $successful = 0;
        $failed = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                $recommendations = $this->recommendationService->generateRecommendationsForUser($user);
                $successful++;
                $this->info("✅ {$user->name}: " . count($recommendations) . " recomendaciones");
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "❌ {$user->name}: {$e->getMessage()}";
            }
        }

        $this->displaySummary($successful, $failed, $errors);
        return 0;
    }

    /**
     * Genera recomendaciones basadas en noticias
     */
    private function generateFromNews()
    {
        $this->info('📰 Generando recomendaciones basadas en noticias recientes...');
        
        try {
            $results = $this->recommendationService->generateNewsBasedRecommendations();
            
            $successful = count(array_filter($results, fn($r) => $r['success']));
            $failed = count(array_filter($results, fn($r) => !$r['success']));
            
            $this->info("✅ Recomendaciones generadas desde noticias:");
            $this->info("   📊 Total procesadas: " . count($results));
            $this->info("   ✅ Exitosas: {$successful}");
            $this->info("   ❌ Fallidas: {$failed}");
            
            if ($failed > 0) {
                $this->warn("⚠️  Revisa los logs para más detalles sobre los errores");
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error generando recomendaciones desde noticias: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Muestra el resumen de resultados
     */
    private function displaySummary($successful, $failed, $errors)
    {
        $this->newLine();
        $this->info("📊 RESUMEN DE GENERACIÓN:");
        $this->info("   ✅ Exitosos: {$successful}");
        $this->info("   ❌ Fallidos: {$failed}");
        $this->info("   📈 Tasa de éxito: " . round(($successful / ($successful + $failed)) * 100, 2) . "%");

        if (!empty($errors)) {
            $this->newLine();
            $this->warn("🚨 ERRORES ENCONTRADOS:");
            foreach (array_slice($errors, 0, 5) as $error) {
                $this->line("   {$error}");
            }
            
            if (count($errors) > 5) {
                $this->line("   ... y " . (count($errors) - 5) . " errores más (ver logs)");
            }
        }
    }

    /**
     * Muestra la ayuda del comando
     */
    private function showHelp()
    {
        $this->info('🤖 Generador de Recomendaciones IA');
        $this->newLine();
        $this->info('Opciones disponibles:');
        $this->line('  --all              Generar para todos los usuarios');
        $this->line('  --user=ID          Generar para usuario específico');
        $this->line('  --role=NOMBRE      Generar para usuarios con rol específico');
        $this->line('  --news             Generar basadas en noticias recientes');
        $this->line('  --force            Forzar generación (ignorar recomendaciones recientes)');
        $this->newLine();
        $this->info('Ejemplos:');
        $this->line('  php artisan recommendations:generate --all');
        $this->line('  php artisan recommendations:generate --user=2');
        $this->line('  php artisan recommendations:generate --role=admin');
        $this->line('  php artisan recommendations:generate --news');
    }
}
