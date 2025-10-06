<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TechSupportCategory;
use App\Models\TechSupportProblem;

class TechSupportDataSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar restricciones de clave foránea temporalmente
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar datos existentes
        TechSupportProblem::truncate();
        TechSupportCategory::truncate();
        
        // Rehabilitar restricciones de clave foránea
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crear categorías
        $categories = [
            [
                'name' => 'computadora',
                'display_name' => 'Computadora',
                'icon' => '💻',
                'description' => 'Problemas relacionados con computadoras y hardware',
                'sort_order' => 1
            ],
            [
                'name' => 'internet',
                'display_name' => 'Internet',
                'icon' => '🌐',
                'description' => 'Problemas de conectividad y navegación web',
                'sort_order' => 2
            ],
            [
                'name' => 'correo',
                'display_name' => 'Correo Electrónico',
                'icon' => '📧',
                'description' => 'Problemas con el correo electrónico',
                'sort_order' => 3
            ],
            [
                'name' => 'impresora',
                'display_name' => 'Impresora',
                'icon' => '🖨️',
                'description' => 'Problemas con impresoras y documentos',
                'sort_order' => 4
            ],
            [
                'name' => 'software',
                'display_name' => 'Software',
                'icon' => '💾',
                'description' => 'Problemas con programas y aplicaciones',
                'sort_order' => 5
            ],
            [
                'name' => 'acceso',
                'display_name' => 'Acceso y Contraseñas',
                'icon' => '🔐',
                'description' => 'Problemas de acceso y autenticación',
                'sort_order' => 6
            ]
        ];

        foreach ($categories as $categoryData) {
            TechSupportCategory::create($categoryData);
        }

        // Obtener IDs de categorías
        $computadoraId = TechSupportCategory::where('name', 'computadora')->first()->id;
        $internetId = TechSupportCategory::where('name', 'internet')->first()->id;
        $correoId = TechSupportCategory::where('name', 'correo')->first()->id;
        $impresoraId = TechSupportCategory::where('name', 'impresora')->first()->id;
        $softwareId = TechSupportCategory::where('name', 'software')->first()->id;
        $accesoId = TechSupportCategory::where('name', 'acceso')->first()->id;

        // Crear problemas
        $problems = [
            // Categoría: Computadora
            [
                'tech_support_category_id' => $computadoraId,
                'problem_key' => 'computadora_lenta',
                'title' => '🐌 Mi computadora está muy lenta',
                'description' => 'Demora mucho en abrir programas o responder',
                'solution_title' => '💻 Tu computadora está lenta - Te ayudo paso a paso',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>🔄 Paso 1: Reiniciar (lo más importante)</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Cierra todos los programas que tengas abiertos</li>
                            <li>Click en el botón de Windows (esquina inferior izquierda)</li>
                            <li>Click en el ícono de encendido ⚡</li>
                            <li>Selecciona 'Reiniciar' y espera</li>
                        </ul>
                    </div>
                    <div class='bg-green-50 border-l-4 border-green-400 p-4 rounded'>
                        <h4 class='font-bold text-green-800 mb-2'>⚡ Paso 2: Si sigue lenta</h4>
                        <ul class='list-disc list-inside text-green-700 space-y-1'>
                            <li>No abras muchos programas al mismo tiempo</li>
                            <li>Cierra pestañas del navegador que no uses</li>
                            <li>Evita tener muchos archivos en el Escritorio</li>
                        </ul>
                    </div>
                    <div class='bg-yellow-50 border border-yellow-300 p-3 rounded text-center'>
                        <p class='text-yellow-800 font-semibold'>❓ ¿Sigues teniendo problemas?</p>
                        <p class='text-yellow-700'>Llama a IT y diles que tu computadora está lenta.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '5-10 minutos',
                'sort_order' => 1,
                'keywords' => ['lenta', 'despacio', 'demora', 'lento', 'tardado']
            ],
            [
                'tech_support_category_id' => $computadoraId,
                'problem_key' => 'computadora_no_enciende',
                'title' => '⚡ Mi computadora no enciende',
                'description' => 'No se enciende cuando presiono el botón de power',
                'solution_title' => '⚡ Tu computadora no enciende - Revisemos juntos',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-red-50 border-l-4 border-red-400 p-4 rounded'>
                        <h4 class='font-bold text-red-800 mb-2'>🔌 Paso 1: Revisar la electricidad</h4>
                        <ul class='list-disc list-inside text-red-700 space-y-1'>
                            <li>¿Está conectado el cable de la pared?</li>
                            <li>¿La luz del enchufe está funcionando?</li>
                            <li>Prueba conectar en otro enchufe</li>
                        </ul>
                    </div>
                    <div class='bg-orange-50 border-l-4 border-orange-400 p-4 rounded'>
                        <h4 class='font-bold text-orange-800 mb-2'>🖥️ Paso 2: Revisar la computadora</h4>
                        <ul class='list-disc list-inside text-orange-700 space-y-1'>
                            <li>Busca el botón de encendido (suele tener este símbolo ⚡)</li>
                            <li>Manténlo presionado por 10 segundos</li>
                            <li>¿Se enciende alguna luz?</li>
                        </ul>
                    </div>
                    <div class='bg-red-100 border border-red-400 p-3 rounded text-center'>
                        <p class='text-red-800 font-semibold'>🚨 Si nada funciona:</p>
                        <p class='text-red-700'>Llama inmediatamente a IT.</p>
                    </div>
                </div>",
                'priority' => 'high',
                'estimated_time' => '10-15 minutos',
                'sort_order' => 2,
                'keywords' => ['no enciende', 'apagada', 'muerta', 'power']
            ],
            [
                'tech_support_category_id' => $computadoraId,
                'problem_key' => 'computadora_pantalla',
                'title' => '🖥️ Problemas con la pantalla',
                'description' => 'Pantalla negra, sin imagen o parpadea',
                'solution_title' => '🖥️ Problemas de pantalla - Verificaciones básicas',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-purple-50 border-l-4 border-purple-400 p-4 rounded'>
                        <h4 class='font-bold text-purple-800 mb-2'>🔗 Paso 1: Revisar conexiones</h4>
                        <ul class='list-disc list-inside text-purple-700 space-y-1'>
                            <li>¿Está conectado el cable de la pantalla?</li>
                            <li>¿Está encendida la pantalla? (busca botón de power)</li>
                            <li>¿Hay alguna luz en la pantalla?</li>
                        </ul>
                    </div>
                    <div class='bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded'>
                        <h4 class='font-bold text-indigo-800 mb-2'>🔄 Paso 2: Reiniciar</h4>
                        <ul class='list-disc list-inside text-indigo-700 space-y-1'>
                            <li>Reinicia la computadora</li>
                            <li>Espera a que cargue completamente</li>
                        </ul>
                    </div>
                    <div class='bg-red-100 border border-red-400 p-3 rounded text-center'>
                        <p class='text-red-800 font-semibold'>⚫ Si la pantalla sigue en negro:</p>
                        <p class='text-red-700'>Llama a IT inmediatamente.</p>
                    </div>
                </div>",
                'priority' => 'high',
                'estimated_time' => '5-10 minutos',
                'sort_order' => 3,
                'keywords' => ['pantalla', 'negra', 'monitor', 'display', 'imagen']
            ],
            [
                'tech_support_category_id' => $computadoraId,
                'problem_key' => 'computadora_congela',
                'title' => '❄️ Se congela o se traba',
                'description' => 'La computadora se congela y no responde',
                'solution_title' => '❄️ Computadora se congela - Soluciones',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-cyan-50 border-l-4 border-cyan-400 p-4 rounded'>
                        <h4 class='font-bold text-cyan-800 mb-2'>🔄 Paso 1: Forzar reinicio</h4>
                        <ul class='list-disc list-inside text-cyan-700 space-y-1'>
                            <li>Mantén presionado el botón de encendido por 10 segundos</li>
                            <li>Espera 30 segundos</li>
                            <li>Vuelve a encender</li>
                        </ul>
                    </div>
                    <div class='bg-teal-50 border-l-4 border-teal-400 p-4 rounded'>
                        <h4 class='font-bold text-teal-800 mb-2'>🛡️ Paso 2: Prevenir congelamiento</h4>
                        <ul class='list-disc list-inside text-teal-700 space-y-1'>
                            <li>No abras muchos programas a la vez</li>
                            <li>Cierra pestañas del navegador que no uses</li>
                            <li>Guarda tu trabajo frecuentemente</li>
                        </ul>
                    </div>
                    <div class='bg-yellow-50 border border-yellow-300 p-3 rounded text-center'>
                        <p class='text-yellow-800 font-semibold'>🧊 Si se sigue congelando:</p>
                        <p class='text-yellow-700'>Llama a IT.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '5 minutos',
                'sort_order' => 4,
                'keywords' => ['congela', 'traba', 'cuelga', 'frozen', 'no responde']
            ],

            // Categoría: Internet
            [
                'tech_support_category_id' => $internetId,
                'problem_key' => 'internet_wifi',
                'title' => '📶 Problemas de WiFi',
                'description' => 'No puedo conectarme a la red WiFi',
                'solution_title' => '📶 Problemas de WiFi - Guía paso a paso',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>🔍 Paso 1: Revisar la conexión</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Mira la esquina inferior derecha de tu pantalla</li>
                            <li>¿Ves el símbolo del WiFi? 📶</li>
                            <li>Si tiene una X roja, haz click ahí</li>
                            <li>Busca el nombre de tu red WiFi y conecta</li>
                        </ul>
                    </div>
                    <div class='bg-green-50 border-l-4 border-green-400 p-4 rounded'>
                        <h4 class='font-bold text-green-800 mb-2'>🔄 Paso 2: Reiniciar WiFi</h4>
                        <ul class='list-disc list-inside text-green-700 space-y-1'>
                            <li>Busca la cajita del WiFi (router)</li>
                            <li>Desconecta el cable de la pared por 1 minuto</li>
                            <li>Vuelve a conectar y espera 5 minutos</li>
                        </ul>
                    </div>
                    <div class='bg-yellow-50 border border-yellow-300 p-3 rounded text-center'>
                        <p class='text-yellow-800 font-semibold'>📞 Si sigues sin WiFi:</p>
                        <p class='text-yellow-700'>Llama a tu proveedor de internet o a IT.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '10-15 minutos',
                'sort_order' => 1,
                'keywords' => ['wifi', 'red', 'conexión', 'internet', 'wireless']
            ],
            [
                'tech_support_category_id' => $internetId,
                'problem_key' => 'internet_lento',
                'title' => '🐌 Internet muy lento',
                'description' => 'Las páginas cargan muy despacio',
                'solution_title' => '🐌 Internet lento - Acelerar conexión',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-orange-50 border-l-4 border-orange-400 p-4 rounded'>
                        <h4 class='font-bold text-orange-800 mb-2'>🧹 Paso 1: Liberar ancho de banda</h4>
                        <ul class='list-disc list-inside text-orange-700 space-y-1'>
                            <li>Cierra pestañas del navegador que no uses</li>
                            <li>Pausa descargas de videos o música</li>
                            <li>Cierra programas que usen internet</li>
                        </ul>
                    </div>
                    <div class='bg-purple-50 border-l-4 border-purple-400 p-4 rounded'>
                        <h4 class='font-bold text-purple-800 mb-2'>🔄 Paso 2: Reiniciar conexión</h4>
                        <ul class='list-disc list-inside text-purple-700 space-y-1'>
                            <li>Desconecta y reconecta el WiFi</li>
                            <li>Reinicia el router (desenchúfalo 1 minuto)</li>
                            <li>Reinicia tu computadora</li>
                        </ul>
                    </div>
                    <div class='bg-blue-50 border border-blue-300 p-3 rounded text-center'>
                        <p class='text-blue-800 font-semibold'>📈 Para mejorar velocidad:</p>
                        <p class='text-blue-700'>Acércate más al router WiFi.</p>
                    </div>
                </div>",
                'priority' => 'low',
                'estimated_time' => '10 minutos',
                'sort_order' => 2,
                'keywords' => ['lento', 'despacio', 'velocidad', 'carga']
            ],
            [
                'tech_support_category_id' => $internetId,
                'problem_key' => 'internet_no_navega',
                'title' => '🚫 No puedo navegar',
                'description' => 'Tengo WiFi pero no abren las páginas',
                'solution_title' => '🚫 No puedo navegar - Solución paso a paso',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-red-50 border-l-4 border-red-400 p-4 rounded'>
                        <h4 class='font-bold text-red-800 mb-2'>🔧 Paso 1: Verificar navegador</h4>
                        <ul class='list-disc list-inside text-red-700 space-y-1'>
                            <li>Cierra completamente el navegador</li>
                            <li>Abre otro navegador (Chrome, Edge, Firefox)</li>
                            <li>Prueba ir a google.com</li>
                        </ul>
                    </div>
                    <div class='bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded'>
                        <h4 class='font-bold text-yellow-800 mb-2'>🌐 Paso 2: Verificar conexión</h4>
                        <ul class='list-disc list-inside text-yellow-700 space-y-1'>
                            <li>¿Ves el ícono de WiFi conectado? 📶</li>
                            <li>Desconecta y reconecta el WiFi</li>
                            <li>Reinicia la computadora</li>
                        </ul>
                    </div>
                    <div class='bg-gray-100 border border-gray-400 p-3 rounded text-center'>
                        <p class='text-gray-800 font-semibold'>🔧 Si nada funciona:</p>
                        <p class='text-gray-700'>Llama a IT para revisar configuración de red.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '15 minutos',
                'sort_order' => 3,
                'keywords' => ['navegar', 'páginas', 'sitios', 'web', 'browser']
            ],

            // Categoría: Correo
            [
                'tech_support_category_id' => $correoId,
                'problem_key' => 'correo_gmail',
                'title' => '📧 Problemas con Gmail',
                'description' => 'No puedo acceder o usar Gmail',
                'solution_title' => '📧 Problemas con Gmail - Guía de solución',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-red-50 border-l-4 border-red-400 p-4 rounded'>
                        <h4 class='font-bold text-red-800 mb-2'>🔑 Paso 1: Verificar acceso</h4>
                        <ul class='list-disc list-inside text-red-700 space-y-1'>
                            <li>Ve a gmail.com en tu navegador</li>
                            <li>¿Te pide usuario y contraseña?</li>
                            <li>Ingresa tu email completo (@empresa.com)</li>
                            <li>Ingresa tu contraseña cuidadosamente</li>
                        </ul>
                    </div>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>🔄 Paso 2: Si no puedes entrar</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Prueba en una ventana privada/incógnito</li>
                            <li>Borra caché del navegador</li>
                            <li>Prueba con otro navegador</li>
                        </ul>
                    </div>
                    <div class='bg-orange-100 border border-orange-400 p-3 rounded text-center'>
                        <p class='text-orange-800 font-semibold'>🔐 ¿Olvidaste tu contraseña?</p>
                        <p class='text-orange-700'>Llama a IT para restablecerla.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '10 minutos',
                'sort_order' => 1,
                'keywords' => ['gmail', 'google', 'correo', 'email']
            ],
            [
                'tech_support_category_id' => $correoId,
                'problem_key' => 'correo_outlook',
                'title' => '📮 Problemas con Outlook',
                'description' => 'Outlook no funciona o no sincroniza',
                'solution_title' => '📮 Problemas con Outlook - Solución',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>🔄 Paso 1: Reiniciar Outlook</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Cierra completamente Outlook</li>
                            <li>Espera 30 segundos</li>
                            <li>Vuelve a abrir Outlook</li>
                            <li>Espera a que sincronice</li>
                        </ul>
                    </div>
                    <div class='bg-green-50 border-l-4 border-green-400 p-4 rounded'>
                        <h4 class='font-bold text-green-800 mb-2'>📨 Paso 2: Verificar envío/recepción</h4>
                        <ul class='list-disc list-inside text-green-700 space-y-1'>
                            <li>Click en 'Enviar y recibir' en la barra superior</li>
                            <li>Verifica que tengas conexión a internet</li>
                            <li>Revisa la carpeta de 'Elementos enviados'</li>
                        </ul>
                    </div>
                    <div class='bg-yellow-100 border border-yellow-400 p-3 rounded text-center'>
                        <p class='text-yellow-800 font-semibold'>⚙️ Si sigue sin funcionar:</p>
                        <p class='text-yellow-700'>Llama a IT para revisar configuración.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '10 minutos',
                'sort_order' => 2,
                'keywords' => ['outlook', 'microsoft', 'correo', 'email', 'sincronizar']
            ],
            [
                'tech_support_category_id' => $correoId,
                'problem_key' => 'correo_acceso',
                'title' => '🔐 No puedo acceder al correo',
                'description' => 'Problemas de contraseña o acceso',
                'solution_title' => '🔐 Problemas de acceso al correo',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-red-50 border-l-4 border-red-400 p-4 rounded'>
                        <h4 class='font-bold text-red-800 mb-2'>🔑 Paso 1: Verificar credenciales</h4>
                        <ul class='list-disc list-inside text-red-700 space-y-1'>
                            <li>Asegúrate de escribir tu email completo</li>
                            <li>Revisa que no tengas Caps Lock activado</li>
                            <li>Escribe la contraseña lentamente</li>
                            <li>Copia y pega si es necesario</li>
                        </ul>
                    </div>
                    <div class='bg-orange-50 border-l-4 border-orange-400 p-4 rounded'>
                        <h4 class='font-bold text-orange-800 mb-2'>🔓 Paso 2: Restablecer acceso</h4>
                        <ul class='list-disc list-inside text-orange-700 space-y-1'>
                            <li>Busca 'Olvidé mi contraseña' en la página</li>
                            <li>O intenta desde otro dispositivo</li>
                            <li>Verifica si tu cuenta está bloqueada</li>
                        </ul>
                    </div>
                    <div class='bg-blue-100 border border-blue-400 p-3 rounded text-center'>
                        <p class='text-blue-800 font-semibold'>🆘 ¿Sigue sin funcionar?</p>
                        <p class='text-blue-700'>Llama a IT para restablecer tu contraseña.</p>
                    </div>
                </div>",
                'priority' => 'high',
                'estimated_time' => '15 minutos',
                'sort_order' => 3,
                'keywords' => ['contraseña', 'password', 'acceso', 'login', 'bloqueado']
            ],

            // Categoría: Impresora
            [
                'tech_support_category_id' => $impresoraId,
                'problem_key' => 'impresora_no_imprime',
                'title' => '🚫 La impresora no imprime',
                'description' => 'Envío trabajos pero no imprime nada',
                'solution_title' => '🚫 La impresora no imprime - Revisemos',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-red-50 border-l-4 border-red-400 p-4 rounded'>
                        <h4 class='font-bold text-red-800 mb-2'>🔌 Paso 1: Verificaciones básicas</h4>
                        <ul class='list-disc list-inside text-red-700 space-y-1'>
                            <li>¿Está encendida la impresora?</li>
                            <li>¿Está conectada a la pared?</li>
                            <li>¿Tiene papel en la bandeja?</li>
                            <li>¿Hay alguna luz roja parpadeando?</li>
                        </ul>
                    </div>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>📄 Paso 2: Revisar cola de impresión</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Ve a Configuración > Impresoras</li>
                            <li>Click en tu impresora</li>
                            <li>Click en 'Ver cola de impresión'</li>
                            <li>Si hay trabajos atascados, elimínalos</li>
                        </ul>
                    </div>
                    <div class='bg-green-100 border border-green-400 p-3 rounded text-center'>
                        <p class='text-green-800 font-semibold'>🔧 Si sigue sin imprimir:</p>
                        <p class='text-green-700'>Llama a IT para revisar la impresora.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '10-15 minutos',
                'sort_order' => 1,
                'keywords' => ['impresora', 'imprimir', 'print', 'documento']
            ],
            [
                'tech_support_category_id' => $impresoraId,
                'problem_key' => 'impresora_papel',
                'title' => '📄 Problemas con el papel',
                'description' => 'Papel atascado o problemas de alimentación',
                'solution_title' => '📄 Problemas con el papel - Solución',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-orange-50 border-l-4 border-orange-400 p-4 rounded'>
                        <h4 class='font-bold text-orange-800 mb-2'>🛑 Paso 1: Quitar papel atascado</h4>
                        <ul class='list-disc list-inside text-orange-700 space-y-1'>
                            <li>Apaga la impresora</li>
                            <li>Abre todas las tapas con cuidado</li>
                            <li>Quita el papel despacio, sin romperlo</li>
                            <li>Revisa que no queden pedazos</li>
                        </ul>
                    </div>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>📋 Paso 2: Cargar papel correctamente</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Usa papel del tamaño correcto (A4 / Carta)</li>
                            <li>No llenes demasiado la bandeja</li>
                            <li>Ajusta las guías del papel</li>
                            <li>Enciende la impresora</li>
                        </ul>
                    </div>
                    <div class='bg-yellow-100 border border-yellow-400 p-3 rounded text-center'>
                        <p class='text-yellow-800 font-semibold'>⚠️ Importante:</p>
                        <p class='text-yellow-700'>Nunca fuerces el papel. Si está muy atascado, llama a IT.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '10 minutos',
                'sort_order' => 2,
                'keywords' => ['papel', 'atascado', 'jam', 'bandeja']
            ],
            [
                'tech_support_category_id' => $impresoraId,
                'problem_key' => 'impresora_tinta',
                'title' => '🎨 Problemas de tinta o tóner',
                'description' => 'Sale muy claro o con rayas',
                'solution_title' => '🎨 Problemas de tinta o tóner',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-purple-50 border-l-4 border-purple-400 p-4 rounded'>
                        <h4 class='font-bold text-purple-800 mb-2'>🔍 Paso 1: Verificar niveles</h4>
                        <ul class='list-disc list-inside text-purple-700 space-y-1'>
                            <li>Ve a Configuración > Impresoras</li>
                            <li>Click en tu impresora</li>
                            <li>Busca 'Propiedades' o 'Preferencias'</li>
                            <li>Revisa los niveles de tinta/tóner</li>
                        </ul>
                    </div>
                    <div class='bg-cyan-50 border-l-4 border-cyan-400 p-4 rounded'>
                        <h4 class='font-bold text-cyan-800 mb-2'>🧹 Paso 2: Limpiar cabezales</h4>
                        <ul class='list-disc list-inside text-cyan-700 space-y-1'>
                            <li>En propiedades de impresora</li>
                            <li>Busca 'Mantenimiento' o 'Utilidades'</li>
                            <li>Ejecuta 'Limpiar cabezales'</li>
                            <li>Imprime una página de prueba</li>
                        </ul>
                    </div>
                    <div class='bg-red-100 border border-red-400 p-3 rounded text-center'>
                        <p class='text-red-800 font-semibold'>🔄 Si sigue imprimiendo mal:</p>
                        <p class='text-red-700'>Llama a IT para cambiar cartuchos.</p>
                    </div>
                </div>",
                'priority' => 'low',
                'estimated_time' => '15 minutos',
                'sort_order' => 3,
                'keywords' => ['tinta', 'tóner', 'cartuchos', 'calidad', 'claro']
            ],

            // Categoría: Software
            [
                'tech_support_category_id' => $softwareId,
                'problem_key' => 'software_office',
                'title' => '📋 Problemas con Microsoft Office',
                'description' => 'Word, Excel o PowerPoint no funcionan',
                'solution_title' => '📋 Problemas con Microsoft Office',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>🔄 Paso 1: Reiniciar programa</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Cierra completamente Word/Excel/PowerPoint</li>
                            <li>Espera 30 segundos</li>
                            <li>Vuelve a abrir el programa</li>
                            <li>Prueba abrir un documento</li>
                        </ul>
                    </div>
                    <div class='bg-green-50 border-l-4 border-green-400 p-4 rounded'>
                        <h4 class='font-bold text-green-800 mb-2'>🛠️ Paso 2: Modo seguro</h4>
                        <ul class='list-disc list-inside text-green-700 space-y-1'>
                            <li>Presiona Windows + R</li>
                            <li>Escribe: winword /safe (para Word)</li>
                            <li>O excel /safe (para Excel)</li>
                            <li>Presiona Enter</li>
                        </ul>
                    </div>
                    <div class='bg-orange-100 border border-orange-400 p-3 rounded text-center'>
                        <p class='text-orange-800 font-semibold'>⚙️ Si el problema persiste:</p>
                        <p class='text-orange-700'>Llama a IT para reparar Office.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '10 minutos',
                'sort_order' => 1,
                'keywords' => ['office', 'word', 'excel', 'powerpoint', 'microsoft']
            ],
            [
                'tech_support_category_id' => $softwareId,
                'problem_key' => 'software_google',
                'title' => '📄 Problemas con Google Workspace',
                'description' => 'Google Docs, Sheets o Drive no funcionan',
                'solution_title' => '📄 Problemas con Google Workspace',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-red-50 border-l-4 border-red-400 p-4 rounded'>
                        <h4 class='font-bold text-red-800 mb-2'>🌐 Paso 1: Verificar navegador</h4>
                        <ul class='list-disc list-inside text-red-700 space-y-1'>
                            <li>Actualiza la página (F5 o Ctrl+R)</li>
                            <li>Prueba en ventana incógnito</li>
                            <li>Borra caché del navegador</li>
                            <li>Prueba con otro navegador</li>
                        </ul>
                    </div>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>🔑 Paso 2: Verificar sesión</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>Ve a accounts.google.com</li>
                            <li>Verifica que estés logueado</li>
                            <li>Cierra sesión y vuelve a entrar</li>
                            <li>Usa tu email de trabajo</li>
                        </ul>
                    </div>
                    <div class='bg-green-100 border border-green-400 p-3 rounded text-center'>
                        <p class='text-green-800 font-semibold'>☁️ Para acceso offline:</p>
                        <p class='text-green-700'>Verifica que tengas Google Drive sincronizado.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '10 minutos',
                'sort_order' => 2,
                'keywords' => ['google', 'docs', 'sheets', 'drive', 'workspace']
            ],
            [
                'tech_support_category_id' => $softwareId,
                'problem_key' => 'software_otro',
                'title' => '⚠️ Otro programa no funciona',
                'description' => 'Un programa específico tiene problemas',
                'solution_title' => '⚠️ Solución general para programas',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded'>
                        <h4 class='font-bold text-yellow-800 mb-2'>🔄 Paso 1: Reinicio básico</h4>
                        <ul class='list-disc list-inside text-yellow-700 space-y-1'>
                            <li>Cierra completamente el programa</li>
                            <li>Revisa que no esté en la barra de tareas</li>
                            <li>Espera 1 minuto</li>
                            <li>Vuelve a abrir como administrador</li>
                        </ul>
                    </div>
                    <div class='bg-purple-50 border-l-4 border-purple-400 p-4 rounded'>
                        <h4 class='font-bold text-purple-800 mb-2'>🖥️ Paso 2: Reiniciar computadora</h4>
                        <ul class='list-disc list-inside text-purple-700 space-y-1'>
                            <li>Guarda todo tu trabajo</li>
                            <li>Cierra todos los programas</li>
                            <li>Reinicia la computadora</li>
                            <li>Prueba el programa nuevamente</li>
                        </ul>
                    </div>
                    <div class='bg-red-100 border border-red-400 p-3 rounded text-center'>
                        <p class='text-red-800 font-semibold'>🆘 Si sigue fallando:</p>
                        <p class='text-red-700'>Llama a IT y menciona qué programa específico es.</p>
                    </div>
                </div>",
                'priority' => 'medium',
                'estimated_time' => '15 minutos',
                'sort_order' => 3,
                'keywords' => ['programa', 'aplicación', 'software', 'error']
            ],

            // Categoría: Acceso
            [
                'tech_support_category_id' => $accesoId,
                'problem_key' => 'acceso_password',
                'title' => '🔑 Olvidé mi contraseña',
                'description' => 'No recuerdo mi contraseña de usuario',
                'solution_title' => '🔑 Recuperar contraseña olvidada',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-blue-50 border-l-4 border-blue-400 p-4 rounded'>
                        <h4 class='font-bold text-blue-800 mb-2'>🔍 Paso 1: Intentar recordar</h4>
                        <ul class='list-disc list-inside text-blue-700 space-y-1'>
                            <li>¿Usas la misma contraseña en otros sitios?</li>
                            <li>¿La tienes guardada en el navegador?</li>
                            <li>¿La escribiste en algún lugar seguro?</li>
                            <li>Revisa tus notas o agenda</li>
                        </ul>
                    </div>
                    <div class='bg-orange-50 border-l-4 border-orange-400 p-4 rounded'>
                        <h4 class='font-bold text-orange-800 mb-2'>🔓 Paso 2: Restablecer</h4>
                        <ul class='list-disc list-inside text-orange-700 space-y-1'>
                            <li>Busca 'Olvidé mi contraseña' en la página</li>
                            <li>Ingresa tu email o usuario</li>
                            <li>Revisa tu correo para instrucciones</li>
                            <li>Sigue los pasos del email</li>
                        </ul>
                    </div>
                    <div class='bg-red-100 border border-red-400 p-3 rounded text-center'>
                        <p class='text-red-800 font-semibold'>🆘 ¿No puedes restablecer?</p>
                        <p class='text-red-700'>Llama a IT inmediatamente para restablecer tu acceso.</p>
                    </div>
                </div>",
                'priority' => 'high',
                'estimated_time' => '15-20 minutos',
                'sort_order' => 1,
                'keywords' => ['contraseña', 'password', 'olvidé', 'acceso']
            ],
            [
                'tech_support_category_id' => $accesoId,
                'problem_key' => 'acceso_bloqueada',
                'title' => '🔒 Mi cuenta está bloqueada',
                'description' => 'No puedo entrar, dice que está bloqueada',
                'solution_title' => '🔒 Cuenta bloqueada - Desbloquear',
                'solution_content' => "<div class='space-y-4'>
                    <div class='bg-red-50 border-l-4 border-red-400 p-4 rounded'>
                        <h4 class='font-bold text-red-800 mb-2'>⏰ Paso 1: Esperar</h4>
                        <ul class='list-disc list-inside text-red-700 space-y-1'>
                            <li>Las cuentas se desbloquean automáticamente</li>
                            <li>Espera 15-30 minutos</li>
                            <li>No intentes más veces mientras tanto</li>
                            <li>Esto evita bloqueos más largos</li>
                        </ul>
                    </div>
                    <div class='bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded'>
                        <h4 class='font-bold text-yellow-800 mb-2'>✅ Paso 2: Intentar de nuevo</h4>
                        <ul class='list-disc list-inside text-yellow-700 space-y-1'>
                            <li>Asegúrate de escribir bien el usuario</li>
                            <li>Escribe la contraseña lentamente</li>
                            <li>Verifica que no tengas Caps Lock</li>
                            <li>Solo un intento, no varios seguidos</li>
                        </ul>
                    </div>
                    <div class='bg-blue-100 border border-blue-400 p-3 rounded text-center'>
                        <p class='text-blue-800 font-semibold'>🚨 Si sigue bloqueada:</p>
                        <p class='text-blue-700'>Llama a IT para desbloqueo manual.</p>
                    </div>
                </div>",
                'priority' => 'high',
                'estimated_time' => '30 minutos',
                'sort_order' => 2,
                'keywords' => ['bloqueada', 'blocked', 'lock', 'cuenta']
            ]
        ];

        foreach ($problems as $problemData) {
            TechSupportProblem::create($problemData);
        }

        $this->command->info('✅ Seeder ejecutado exitosamente!');
        $this->command->info('📊 Categorías creadas: ' . count($categories));
        $this->command->info('🎯 Problemas creados: ' . count($problems));
    }
}
