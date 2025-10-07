<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\TempEmployee;
use App\Models\CompanyLocation;
use App\Models\CompanyDocument;
use App\Models\TechSupportConversation;

class CorporateInfoController extends Controller
{
    public function index()
    {
        return view('corporate-info.index');
    }

    public function searchEmployees(Request $request)
    {
        $search = $request->get('search', '');
        $department = $request->get('department', '');
        
        $query = TempEmployee::active();
        
        if (!empty($search)) {
            $query->search($search);
        }
        
        if (!empty($department)) {
            $query->byDepartment($department);
       
        $employees = $query->limit(20)->get();
        }
         
        return response()->json([
            'employees' => $employees->map(function($emp) {
                return [
                    'id' => $emp->id,
                    'employee_id' => $emp->employee_id,
                    'full_name' => $emp->full_name,
                    'email' => $emp->email,
                    'phone' => $emp->phone,
                    'extension' => $emp->extension,
                    'position' => $emp->position,
                    'department' => $emp->department,
                    'location' => $emp->location,
                    'manager_email' => $emp->manager_email,
                    'has_system_access' => $emp->hasSystemAccess()
                ];
            }),
            'count' => $employees->count()
        ]);
    } 

    public function searchLocations(Request $request)
    {
        $search = $request->get('search', '');
        $type = $request->get('type', '');
        
        $query = CompanyLocation::active();
        
        if (!empty($search)) {
            $query->search($search);
        }
        
        if (!empty($type)) {
            $query->byType($type);
        }
        
        $locations = $query->limit(20)->get();
        
        return response()->json([
            'locations' => $locations,
            'count' => $locations->count()
        ]);
    }

    public function searchDocuments(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category', '');
        $type = $request->get('type', '');
        
        $query = CompanyDocument::active();
        
        if (!empty($search)) {
            $query->search($search);
        }
        
        if (!empty($category)) {
            $query->byCategory($category);
        }
        
        if (!empty($type)) {
            $query->byType($type);
        }
        
        $documents = $query->limit(20)->get();
        
        return response()->json([
            'documents' => $documents,
            'count' => $documents->count()
        ]);
    }

    public function chatBot(Request $request)
    {
        try {
            $message = $request->get('message', '');
            $context = $request->get('context', []);
            
            // Validar que el mensaje no esté vacío
            if (empty(trim($message))) {
                return response()->json([
                    'message' => '🤔 No recibí ningún mensaje. ¿Podrías escribir algo?',
                    'context' => ['step' => 'initial'],
                    'suggestions' => ['Buscar empleado', 'Ver departamentos', 'Ayuda']
                ]);
            }
            
            // Procesar el mensaje del usuario
            $response = $this->processUserMessage($message, $context);
            
            return response()->json($response);
        } catch (\Exception $e) {
            // Log del error para debug
            Log::error('Error en chatBot: ' . $e->getMessage(), [
                'message' => $request->get('message', ''),
                'context' => $request->get('context', []),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => '😔 Lo siento, hubo un problema técnico. Por favor intenta de nuevo.',
                'context' => ['step' => 'initial'],
                'suggestions' => ['Buscar empleado', 'Ver departamentos', 'Menú principal']
            ]);
        }
    }

    private function processUserMessage($message, $context)
    {
        // Limpiar y normalizar el mensaje
        $message = strtolower(trim($message));
        
        // Manejar acciones de botones
        if (isset($context['action'])) {
            return $this->handleButtonAction($context['action'], $context['value'] ?? null, $message);
        }
        
        // Si no hay contexto, inicializar
        if (empty($context['step'])) {
            return $this->handleInitialMessage($message);
        }
        
        // Procesar según el paso actual
        switch ($context['step']) {
            case 'employee_search':
                return $this->handleEmployeeSearch($message, $context);
            case 'document_search':
                return $this->handleDocumentSearch($message, $context);
            case 'tech_support':
                return $this->handleTechSupport($message);
            default:
                return $this->handleInitialMessage($message);
        }
    }

    private function handleInitialMessage($message)
    {
        // Detectar intención basada en palabras clave
        if (str_contains($message, 'empleado') || str_contains($message, 'persona') || str_contains($message, 'contacto') || str_contains($message, 'staff') || str_contains($message, 'directorio')) {
            return [
                'message' => '👤 **Búsqueda de Empleados**

¿Qué tipo de búsqueda necesitas?',
                'context' => ['step' => 'employee_search', 'type' => 'employee'],
                'buttons' => $this->getEmployeeSearchButtons('general'),
                'suggestions' => ['Ver departamentos', 'Ver cargos', 'Tecnología', 'Gerente']
            ];
        }
        
        if (str_contains($message, 'documento') || str_contains($message, 'archivo') || str_contains($message, 'manual') || str_contains($message, 'política') || str_contains($message, 'procedimiento')) {
            return [
                'message' => '📄 **Documentos Corporativos**

¿Qué tipo de documento necesitas?',
                'context' => ['step' => 'document_search', 'type' => 'document'],
                'buttons' => $this->getDocumentSearchButtons('general'),
                'suggestions' => ['Procedimientos Operativos', 'Mejora Continua', 'Ver todos', 'Ver categorías']
            ];
        }
        
        // Soporte técnico keywords
        if (str_contains($message, 'soporte') || str_contains($message, 'ayuda') || str_contains($message, 'problema') || 
            str_contains($message, 'computadora') || str_contains($message, 'internet') || str_contains($message, 'correo') ||
            str_contains($message, 'impresora') || str_contains($message, 'wifi') || str_contains($message, 'software') ||
            str_contains($message, 'contraseña') || str_contains($message, 'no funciona') || str_contains($message, 'error') ||
            str_contains($message, 'gmail') || str_contains($message, 'outlook') || str_contains($message, 'word') ||
            str_contains($message, 'excel') || str_contains($message, 'google docs') || str_contains($message, 'no abre') ||
            str_contains($message, 'no enciende') || str_contains($message, 'lenta') || str_contains($message, 'lento') ||
            str_contains($message, 'no imprime') || str_contains($message, 'no puedo entrar') || str_contains($message, 'bloqueado') ||
            str_contains($message, 'no conecta') || str_contains($message, 'no carga') || str_contains($message, 'se cierra')) {
            return $this->handleTechSupport($message);
        }
        
        // Respuesta por defecto mejorada
        return [
            'message' => '🏢 **¡Hola! Soy tu Asistente Corporativo**

Puedo ayudarte con información interna de la empresa:

**👤 Empleados**
Buscar contactos, departamentos y cargos

**📄 Documentos**
Políticas, manuales y procedimientos

**🆘 Soporte Técnico**
Ayuda con computadoras, internet, correo y software

**¿Qué información necesitas?**',
            'context' => ['step' => 'initial'],
            'suggestions' => ['Buscar empleado', 'Soporte técnico', 'Encontrar documento', 'Ayuda']
        ];
    }

    private function handleEmployeeSearch($message, $context)
    {
        // Comandos especiales
        if (str_contains($message, 'menú') || str_contains($message, 'inicio')) {
            return $this->handleInitialMessage('');
        }
        
                // Ver departamentos
        if (str_contains($message, 'ver departamentos') || str_contains($message, 'departamentos') || str_contains($message, 'todos los departamentos')) {
            try {
                $departments = TempEmployee::getAllDepartments();
                
                if ($departments && $departments->count() > 0) {
                    $deptArray = $departments->toArray();
                    $deptList = implode("\n• ", $deptArray);
                    
                    return [
                        'message' => "📋 Departamentos Disponibles:\n\n• " . $deptList . "\n\n¿Sobre cuál departamento quieres información?",
                        'context' => ['step' => 'employee_search', 'type' => 'employee'],
                        'suggestions' => array_slice($deptArray, 0, 4)
                    ];
                } else {
                    return [
                        'message' => "❌ No se encontraron departamentos en el sistema.\n\n¿Quieres hacer otra búsqueda?",
                        'context' => ['step' => 'employee_search', 'type' => 'employee'],
                        'suggestions' => ['Nueva búsqueda', 'Menú principal']
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error al obtener departamentos: ' . $e->getMessage());
                return [
                    'message' => "❌ Hubo un error al obtener los departamentos. Por favor intenta de nuevo.",
                    'context' => ['step' => 'employee_search', 'type' => 'employee'],
                    'suggestions' => ['Nueva búsqueda', 'Menú principal']
                ];
            }
        }
        
        // Ver cargos/posiciones
        if (str_contains($message, 'ver cargos') || str_contains($message, 'cargos') || str_contains($message, 'posiciones') || str_contains($message, 'ver posiciones')) {
            try {
                $positions = TempEmployee::getAllPositions();
                
                if ($positions && $positions->count() > 0) {
                    $positionsArray = $positions->take(15)->toArray(); // Mostrar solo los primeros 15
                    $positionsList = implode("\n• ", $positionsArray);
                    
                    return [
                        'message' => "💼 Cargos/Posiciones Disponibles:\n\n• " . $positionsList . 
                                   ($positions->count() > 15 ? "\n\n_Mostrando los primeros 15 cargos..._" : "") . 
                                   "\n\n¿Sobre cuál cargo quieres información?",
                        'context' => ['step' => 'employee_search', 'type' => 'employee'],
                        'suggestions' => array_slice($positionsArray, 0, 4)
                    ];
                } else {
                    return [
                        'message' => "❌ No se encontraron cargos en el sistema.\n\n¿Quieres hacer otra búsqueda?",
                        'context' => ['step' => 'employee_search', 'type' => 'employee'],
                        'suggestions' => ['Nueva búsqueda', 'Menú principal']
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error al obtener cargos: ' . $e->getMessage());
                return [
                    'message' => "❌ Hubo un error al obtener los cargos. Por favor intenta de nuevo.",
                    'context' => ['step' => 'employee_search', 'type' => 'employee'],
                    'suggestions' => ['Nueva búsqueda', 'Menú principal']
                ];
            }
        }
        
        // Buscar por manager/jefe
        if (str_contains($message, 'empleados de ') || str_contains($message, 'equipo de ') || str_contains($message, 'reportan a ')) {
            // Extraer email del manager
            preg_match('/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $message, $matches);
            if (isset($matches[0])) {
                $managerEmail = $matches[0];
                $employees = TempEmployee::active()->where('manager_email', $managerEmail)->limit(10)->get();
                
                if ($employees->count() > 0) {
                    $response = "👥 **Empleados que reportan a {$managerEmail}:**\n\n";
                    
                    foreach ($employees as $emp) {
                        $response .= "• **{$emp->full_name}** - {$emp->position} ({$emp->department})\n";
                    }
                    
                    $response .= "\n¿Necesitas información detallada de algún empleado específico?";
                    
                    return [
                        'message' => $response,
                        'context' => ['step' => 'employee_search', 'type' => 'employee'],
                        'suggestions' => ['Ver detalles', 'Nueva búsqueda', 'Menú principal']
                    ];
                } else {
                    return [
                        'message' => "❌ No encontré empleados que reporten a **{$managerEmail}**.\n\n¿Quieres hacer otra búsqueda?",
                        'context' => ['step' => 'employee_search', 'type' => 'employee'],
                        'suggestions' => ['Ver departamentos', 'Nueva búsqueda', 'Menú principal']
                    ];
                }
            }
        }
        
        // Buscar empleados general
        $employees = TempEmployee::active()->search($message)->limit(8)->get();
        
        if ($employees->count() > 0) {
            $response = "✅ **Encontré {$employees->count()} empleado(s):**\n\n";
            
            // Si todos los empleados son del mismo departamento, mostrar estadísticas
            $departments = $employees->pluck('department')->unique();
            if ($departments->count() == 1 && $employees->count() >= 3) {
                $department = $departments->first();
                $stats = TempEmployee::getDepartmentStats($department);
                
                $response .= "📊 **Estadísticas del Departamento de {$department}:**\n";
                $response .= "• Total empleados: {$stats['total_employees']}\n";
                $response .= "• Diferentes cargos: {$stats['positions']}\n";
                $response .= "• Ubicaciones: {$stats['locations']}\n";
                $response .= "• Con acceso al sistema: {$stats['with_system_access']}\n\n";
            }
            
            foreach ($employees as $emp) {
                $response .= "👤 **{$emp->full_name}**";
                
                // Mostrar ID de empleado si existe
                if ($emp->employee_id) {
                    $response .= " ({$emp->employee_id})";
                }
                
                $response .= "\n";
                
                $response .= "   📧 {$emp->email}\n";
                
                if ($emp->phone) {
                    $response .= "   📞 {$emp->phone}";
                    if ($emp->extension) {
                        $response .= " ext. {$emp->extension}";
                    }
                    $response .= "\n";
                }
                
                $response .= "   💼 {$emp->position}\n";
                $response .= "   🏢 {$emp->department}\n";
                
                if ($emp->location) {
                    $response .= "   📍 {$emp->location}\n";
                }
                
                if ($emp->manager_email) {
                    $response .= "   👥 Reporta a: {$emp->manager_email}\n";
                }
                
                $response .= "\n";
            }
            
            if ($employees->count() >= 8) {
                $response .= "Mostrando los primeros 8 resultados. Sé más específico para menos resultados._\n\n";
            }
            
            $response .= "¿Necesitas información de algún empleado específico o quieres hacer otra búsqueda?";
            
            $suggestions = ['Nueva búsqueda', 'Ver departamentos', 'Ver cargos', 'Menú principal'];
            
            // Agregar departamentos como sugerencias si hay varios empleados
            if ($employees->count() > 1) {
                $depts = $employees->pluck('department')->unique()->take(2)->toArray();
                $suggestions = array_merge($depts, $suggestions);
            }
            
        } else {
            $response = "❌ **No encontré empleados** con la información \"*{$message}*\".\n\n";
            $response .= "💡 **Sugerencias:**\n";
            $response .= "• Intenta con el **nombre completo** o solo el nombre\n";
            $response .= "• Busca por **departamento** (ej: Tecnología, RRHH)\n";
            $response .= "• Verifica la **ortografía**\n\n";
            $response .= "¿Quieres intentar otra búsqueda?";
            
            $suggestions = ['Ver departamentos', 'Tecnología', 'Recursos Humanos', 'Menú principal'];
        }
        
        return [
            'message' => $response,
            'context' => ['step' => 'employee_search', 'type' => 'employee'],
            'suggestions' => $suggestions
        ];
    }

    public function handleTechSupport($query)
    {
        $query = strtolower($query);
        
        // Comandos especiales - verificar menú principal
        if (str_contains($query, 'menú') || str_contains($query, 'inicio') || str_contains($query, 'menu')) {
            return $this->handleInitialMessage('');
        }
        
        $sessionId = Str::uuid();
        
        // Categorías principales de soporte técnico
        $categories = [
            'computadora' => [
                'keywords' => ['computadora', 'pc', 'laptop', 'ordenador', 'computador', 'lenta', 'lento', 'se cuelga', 'no enciende', 'pantalla', 'no funciona'],
                'subcategories' => [
                    'rendimiento' => ['lenta', 'lento', 'se cuelga', 'congelada'],
                    'encendido' => ['no enciende', 'no prende', 'no arranca'],
                    'pantalla' => ['pantalla', 'monitor', 'no se ve', 'negro']
                ]
            ],
            'internet' => [
                'keywords' => ['internet', 'wifi', 'conexión', 'red', 'no navega', 'no conecta', 'lento internet'],
                'subcategories' => [
                    'wifi' => ['wifi', 'inalámbrica', 'no conecta'],
                    'velocidad' => ['lento', 'lenta conexión'],
                    'navegación' => ['no navega', 'no carga']
                ]
            ],
            'correo' => [
                'keywords' => ['correo', 'email', 'outlook', 'gmail', 'no recibe', 'no envía', 'contraseña'],
                'subcategories' => [
                    'gmail' => ['gmail', 'google mail'],
                    'outlook' => ['outlook', 'microsoft mail'],
                    'acceso' => ['contraseña', 'no puedo entrar']
                ]
            ],
            'impresora' => [
                'keywords' => ['impresora', 'imprimir', 'no imprime', 'papel', 'tinta', 'cartuchos'],
                'subcategories' => [
                    'impresión' => ['no imprime', 'no sale'],
                    'papel' => ['papel', 'atascado'],
                    'tinta' => ['tinta', 'cartuchos', 'sin tinta']
                ]
            ],
            'software' => [
                'keywords' => ['word', 'excel', 'powerpoint', 'programa', 'aplicación', 'no abre', 'error', 'google docs', 'google sheets', 'google drive'],
                'subcategories' => [
                    'office' => ['word', 'excel', 'powerpoint', 'office'],
                    'google' => ['google docs', 'google sheets', 'google drive'],
                    'errores' => ['error', 'no abre', 'se cierra']
                ]
            ],
            'acceso' => [
                'keywords' => ['contraseña', 'usuario', 'no puedo entrar', 'bloqueado', 'acceso'],
                'subcategories' => [
                    'contraseñas' => ['contraseña', 'password', 'clave'],
                    'cuentas' => ['usuario', 'cuenta bloqueada'],
                    'permisos' => ['no tengo acceso', 'permisos']
                ]
            ]
        ];

        $response = '';
        $category = 'general';
        $subcategory = null;
        
        // Detectar categoría y subcategoría
        foreach ($categories as $cat => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (strpos($query, $keyword) !== false) {
                    $category = $cat;
                    
                    // Detectar subcategoría
                    if (isset($data['subcategories'])) {
                        foreach ($data['subcategories'] as $subcat => $subkeywords) {
                            foreach ($subkeywords as $subkeyword) {
                                if (strpos($query, $subkeyword) !== false) {
                                    $subcategory = $subcat;
                                    break 3;
                                }
                            }
                        }
                    }
                    break 2;
                }
            }
        }

        // Generar respuesta específica con lenguaje simple para usuarios no técnicos
        switch ($category) {
            case 'computadora':
                if (strpos($query, 'lenta') !== false || strpos($query, 'lento') !== false) {
                    $response = "💻 **Tu computadora está lenta - Te ayudo paso a paso:**\n\n" .
                               "**Paso 1: Reiniciar (lo más importante)**\n" .
                               "• Cierra todos los programas que tengas abiertos\n" .
                               "• Click en el botón de Windows (esquina inferior izquierda)\n" .
                               "• Click en el ícono de encendido ⚡\n" .
                               "• Selecciona 'Reiniciar' y espera\n\n" .
                               "**Paso 2: Si sigue lenta**\n" .
                               "• No abras muchos programas al mismo tiempo\n" .
                               "• Cierra pestañas del navegador que no uses\n" .
                               "• Evita tener muchos archivos en el Escritorio\n\n" .
                               "**¿Sigues teniendo problemas?** Llama a IT y diles que tu computadora está lenta.";
                } else if (strpos($query, 'no enciende') !== false) {
                    $response = "⚡ **Tu computadora no enciende - Revisemos juntos:**\n\n" .
                               "**Paso 1: Revisar la electricidad**\n" .
                               "• ¿Está conectado el cable de la pared?\n" .
                               "• ¿La luz del enchufe está funcionando?\n" .
                               "• Prueba conectar en otro enchufe\n\n" .
                               "**Paso 2: Revisar la computadora**\n" .
                               "• Busca el botón de encendido (suele tener este símbolo ⚡)\n" .
                               "• Manténlo presionado por 10 segundos\n" .
                               "• ¿Se enciende alguna luz?\n\n" .
                               "**Paso 3: Revisar la pantalla**\n" .
                               "• ¿Está conectada la pantalla?\n" .
                               "• ¿Está encendida la pantalla?\n\n" .
                               "**Si nada funciona:** Llama inmediatamente a IT.";
                } else {
                    $response = "💻 **Problemas con tu computadora:**\n\n" .
                               "Para ayudarte mejor, dime qué está pasando:\n\n" .
                               "• **\"Está muy lenta\"** - Demora mucho en abrir programas\n" .
                               "• **\"No enciende\"** - No pasa nada al presionar el botón\n" .
                               "• **\"Se congela\"** - Se queda trabada y no responde\n" .
                               "• **\"Pantalla en negro\"** - No se ve nada en la pantalla\n\n" .
                               "Mientras tanto, intenta reiniciarla:\n" .
                               "1. Cierra todo lo que tengas abierto\n" .
                               "2. Click en Windows → Reiniciar";
                }
                break;

            case 'internet':
                $response = "🌐 **Problemas de Internet - Solucionemos paso a paso:**\n\n" .
                           "**Paso 1: Revisar la conexión WiFi**\n" .
                           "• Mira la esquina inferior derecha de tu pantalla\n" .
                           "• ¿Ves el símbolo del WiFi? 📶\n" .
                           "• Si tiene una X roja, haz click ahí\n" .
                           "• Busca el nombre de tu red WiFi y conecta\n\n" .
                           "**Paso 2: Reiniciar el WiFi (muy efectivo)**\n" .
                           "• Busca la cajita del WiFi (router)\n" .
                           "• Desconecta el cable de la pared por 1 minuto\n" .
                           "• Vuelve a conectar y espera 3 minutos\n\n" .
                           "**Paso 3: Probar**\n" .
                           "• Abre tu navegador (Chrome, Edge, etc.)\n" .
                           "• Intenta entrar a google.com\n\n" .
                           "**Si no funciona:** Llama a IT y diles que no tienes internet.";
                break;

            case 'correo':
                if (strpos($query, 'gmail') !== false || strpos($query, 'google') !== false) {
                    $response = "📧 **Problemas con Gmail - Te guío paso a paso:**\n\n" .
                               "**No puedes entrar a Gmail:**\n" .
                               "• Abre tu navegador (Chrome, Edge, etc.)\n" .
                               "• Escribe: gmail.com\n" .
                               "• Usa tu correo completo: tunombre@empresa.com\n" .
                               "• Si no recuerdas la contraseña, click en '¿Olvidaste la contraseña?'\n\n" .
                               "**No te llegan correos:**\n" .
                               "• Revisa la carpeta 'Spam' (correo no deseado)\n" .
                               "• Revisa 'Promociones' (puede estar ahí)\n" .
                               "• Pide a alguien que te mande un correo de prueba\n\n" .
                               "**No puedes enviar correos:**\n" .
                               "• Verifica que escribiste bien el correo del destinatario\n" .
                               "• Si adjuntaste archivos, que no sean muy grandes\n\n" .
                               "**Para problemas de contraseña:** Llama a IT.";
                } else if (strpos($query, 'outlook') !== false) {
                    $response = "📧 **Problemas con Outlook - Soluciones simples:**\n\n" .
                               "**Outlook no abre:**\n" .
                               "• Cierra completamente el programa\n" .
                               "• Reinicia tu computadora\n" .
                               "• Busca 'Outlook' en el menú de Windows\n" .
                               "• Haz doble click para abrirlo\n\n" .
                               "**No te llegan correos nuevos:**\n" .
                               "• Busca un botón que diga 'Enviar y recibir'\n" .
                               "• Haz click ahí y espera unos minutos\n" .
                               "• Revisa si tienes internet\n\n" .
                               "**Outlook está muy lento:**\n" .
                               "• Cierra y abre Outlook nuevamente\n" .
                               "• Elimina correos muy antiguos\n" .
                               "• Vacía la papelera de Outlook\n\n" .
                               "**Si no funciona:** Llama a IT.";
                } else {
                    $response = "📧 **Problemas con el correo - ¿Cuál usas?**\n\n" .
                               "**Gmail (desde el navegador):**\n" .
                               "• Abre tu navegador\n" .
                               "• Ve a gmail.com\n" .
                               "• Usa tu correo completo para entrar\n\n" .
                               "**Outlook (programa en la computadora):**\n" .
                               "• Busca el ícono de Outlook en tu escritorio\n" .
                               "• O búscalo en el menú de Windows\n\n" .
                               "**Problemas comunes:**\n" .
                               "• **No puedo entrar** → Verifica tu correo y contraseña\n" .
                               "• **No recibo correos** → Revisa la carpeta de Spam\n" .
                               "• **No puedo enviar** → Verifica tu internet\n\n" .
                               "**Para cambiar contraseñas:** Llama a IT.";
                }
                break;

            case 'impresora':
                $response = "🖨️ **Problemas con la impresora - Te ayudo:**\n\n" .
                           "**La impresora no imprime nada:**\n" .
                           "• ¿Está encendida? Busca una luz verde\n" .
                           "• ¿Tiene papel? Revisa la bandeja de papel\n" .
                           "• ¿Está conectada? Revisa el cable USB\n" .
                           "• Apágala y enciéndela de nuevo\n\n" .
                           "**Papel atascado (muy común):**\n" .
                           "• Apaga la impresora primero\n" .
                           "• Abre todas las tapas que puedas\n" .
                           "• Saca el papel MUY DESPACIO para que no se rompa\n" .
                           "• Cierra todo y enciende de nuevo\n\n" .
                           "**Imprime muy clarito o con rayas:**\n" .
                           "• Probablemente se está acabando la tinta\n" .
                           "• Revisa si parpadea alguna luz\n\n" .
                           "**Para cambio de tintas o cartuchos:** Llama a IT.";
                break;

            case 'software':
                if (strpos($query, 'word') !== false || strpos($query, 'excel') !== false || strpos($query, 'powerpoint') !== false || strpos($query, 'office') !== false) {
                    $response = "📋 **Problemas con Word, Excel o PowerPoint:**\n\n" .
                               "**El programa no abre:**\n" .
                               "• Reinicia tu computadora\n" .
                               "• Busca el programa en el menú de Windows\n" .
                               "• Haz doble click en el ícono\n" .
                               "• Ten paciencia, a veces demora un poco\n\n" .
                               "**No puedes guardar tu trabajo:**\n" .
                               "• Presiona las teclas Ctrl + S al mismo tiempo\n" .
                               "• Elige dónde guardar (recomiendo 'Documentos')\n" .
                               "• Ponle un nombre que reconozcas\n" .
                               "• Click en 'Guardar'\n\n" .
                               "**No encuentras un archivo que guardaste:**\n" .
                               "• Abre el programa (Word, Excel, etc.)\n" .
                               "• Ve a 'Archivo' → 'Abrir' → 'Reciente'\n" .
                               "• O busca en la carpeta 'Documentos'\n\n" .
                               "**Para problemas de licencia:** Llama a IT.";
                } else if (strpos($query, 'google') !== false) {
                    $response = "📄 **Problemas con Google Docs, Sheets o Drive:**\n\n" .
                               "**No cargan los documentos:**\n" .
                               "• Abre tu navegador\n" .
                               "• Ve a docs.google.com (para documentos)\n" .
                               "• O sheets.google.com (para hojas de cálculo)\n" .
                               "• Asegúrate de usar tu correo de trabajo\n\n" .
                               "**No puedes editar un documento:**\n" .
                               "• Verifica que tengas permiso para editarlo\n" .
                               "• Si dice 'Solo lectura', pide permisos al dueño\n" .
                               "• Revisa tu conexión a internet\n\n" .
                               "**No encuentras un archivo:**\n" .
                               "• Ve a drive.google.com\n" .
                               "• Usa el cuadrito de búsqueda arriba\n" .
                               "• Revisa 'Compartido conmigo'\n" .
                               "• Pregunta a quien te compartió el archivo\n\n" .
                               "**Para permisos especiales:** Llama a IT.";
                } else {
                    $response = "💻 **Problemas con programas - ¿Cuál te da problemas?**\n\n" .
                               "**Microsoft Office (Word, Excel, PowerPoint):**\n" .
                               "• Son los programas instalados en tu computadora\n" .
                               "• Búscalos en el menú de Windows\n\n" .
                               "**Google Workspace (Docs, Sheets, Drive):**\n" .
                               "• Se usan desde el navegador\n" .
                               "• Ve a docs.google.com o drive.google.com\n\n" .
                               "**Solución básica para cualquier programa:**\n" .
                               "1. Cierra el programa completamente\n" .
                               "2. Reinicia tu computadora\n" .
                               "3. Abre el programa de nuevo\n" .
                               "4. Si no aparece, búscalo en el menú de Windows\n\n" .
                               "Dime exactamente qué programa y qué problema tienes.";
                }
                break;

            case 'acceso':
                $response = "🔐 **Problemas para entrar a cuentas o programas:**\n\n" .
                           "**Olvidé mi contraseña:**\n" .
                           "• **Para cuentas de la empresa:** Llama INMEDIATAMENTE a IT\n" .
                           "• **Para Gmail personal:** Click en '¿Olvidaste tu contraseña?'\n" .
                           "• **Para otras páginas:** Busca 'Recuperar contraseña'\n\n" .
                           "**Mi cuenta está bloqueada:**\n" .
                           "• Espera 15 minutos sin intentar entrar\n" .
                           "• Si sigue bloqueada, llama a IT\n" .
                           "• NO sigas intentando porque se bloquea más\n\n" .
                           "**No tengo acceso a un archivo o carpeta:**\n" .
                           "• Pregunta a tu jefe si deberías tener acceso\n" .
                           "• Llama a IT para pedir permisos\n" .
                           "• Diles exactamente qué archivo necesitas\n\n" .
                           "**🚨 MUY IMPORTANTE:** Nunca le des tu contraseña a nadie.";
                break;

            default:
                $response = "🆘 **Soporte Técnico - Te ayudo con cualquier problema:**\n\n" .
                           "**Selecciona tu problema haciendo click en una categoría:**\n\n" .
                           "💡 **Tip:** Siempre intenta reiniciar primero - resuelve el 70% de los problemas.";
        }

        // Guardar la conversación en la base de datos
        try {
            TechSupportConversation::create([
                'session_id' => $sessionId,
                'user_message' => $query,
                'problem_category' => $this->mapCategoryToEnum($category),
                'problem_type' => $subcategory,
                'bot_response' => $response,
                'context_data' => json_encode([
                    'timestamp' => now(),
                    'user_agent' => request()->userAgent(),
                    'ip_address' => request()->ip(),
                    'original_category' => $category
                ]),
                'user_ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving tech support conversation: ' . $e->getMessage());
        }

        return [
            'message' => $response,
            'type' => 'tech_support',
            'category' => $category,
            'session_id' => $sessionId,
            'context' => ['step' => 'tech_support'],
            'buttons' => $this->getTechSupportButtons($category),
            'suggestions' => ['Otro problema', 'Empleados', 'Documentos', 'Menú principal']
        ];
    }

    private function mapCategoryToEnum($category)
    {
        $mapping = [
            'computadora' => 'computer',
            'internet' => 'internet',
            'correo' => 'email',
            'impresora' => 'printer',
            'software' => 'software',
            'acceso' => 'access',
            'general' => 'other'
        ];
        
        return $mapping[$category] ?? 'other';
    }

    private function getTechSupportButtons($category)
    {
        if ($category === 'general') {
            // Botones principales de categorías
            return [
                [
                    'text' => '💻 Mi computadora',
                    'action' => 'tech_support_category',
                    'value' => 'computadora',
                    'description' => 'Lenta, no enciende, se congela'
                ],
                [
                    'text' => '🌐 Internet',
                    'action' => 'tech_support_category',
                    'value' => 'internet',
                    'description' => 'WiFi, no navega, conexión lenta'
                ],
                [
                    'text' => '📧 Correo electrónico',
                    'action' => 'tech_support_category',
                    'value' => 'correo',
                    'description' => 'Gmail, Outlook, no recibo correos'
                ],
                [
                    'text' => '🖨️ Impresora',
                    'action' => 'tech_support_category',
                    'value' => 'impresora',
                    'description' => 'No imprime, papel atascado, sin tinta'
                ],
                [
                    'text' => '📋 Programas',
                    'action' => 'tech_support_category',
                    'value' => 'software',
                    'description' => 'Word, Excel, Google Docs, no abren'
                ],
                [
                    'text' => '🔐 No puedo entrar',
                    'action' => 'tech_support_category',
                    'value' => 'acceso',
                    'description' => 'Contraseñas, cuentas bloqueadas'
                ]
            ];
        }

        // Botones específicos por categoría
        switch ($category) {
            case 'computadora':
                return [
                    [
                        'text' => '🐌 Mi computadora está lenta',
                        'action' => 'tech_support_specific',
                        'value' => 'computadora_lenta'
                    ],
                    [
                        'text' => '⚡ No enciende',
                        'action' => 'tech_support_specific',
                        'value' => 'computadora_no_enciende'
                    ],
                    [
                        'text' => '🖥️ Problemas de pantalla',
                        'action' => 'tech_support_specific',
                        'value' => 'computadora_pantalla'
                    ],
                    [
                        'text' => '❄️ Se congela/traba',
                        'action' => 'tech_support_specific',
                        'value' => 'computadora_congela'
                    ]
                ];

            case 'internet':
                return [
                    [
                        'text' => '📶 Problemas de WiFi',
                        'action' => 'tech_support_specific',
                        'value' => 'internet_wifi'
                    ],
                    [
                        'text' => '🐌 Internet muy lento',
                        'action' => 'tech_support_specific',
                        'value' => 'internet_lento'
                    ],
                    [
                        'text' => '🚫 No puedo navegar',
                        'action' => 'tech_support_specific',
                        'value' => 'internet_no_navega'
                    ]
                ];

            case 'correo':
                return [
                    [
                        'text' => '📬 Problemas con Gmail',
                        'action' => 'tech_support_specific',
                        'value' => 'correo_gmail'
                    ],
                    [
                        'text' => '📮 Problemas con Outlook',
                        'action' => 'tech_support_specific',
                        'value' => 'correo_outlook'
                    ],
                    [
                        'text' => '🔑 No puedo entrar al correo',
                        'action' => 'tech_support_specific',
                        'value' => 'correo_acceso'
                    ]
                ];

            case 'impresora':
                return [
                    [
                        'text' => '🚫 No imprime nada',
                        'action' => 'tech_support_specific',
                        'value' => 'impresora_no_imprime'
                    ],
                    [
                        'text' => '📄 Papel atascado',
                        'action' => 'tech_support_specific',
                        'value' => 'impresora_papel'
                    ],
                    [
                        'text' => '🖋️ Problemas de tinta',
                        'action' => 'tech_support_specific',
                        'value' => 'impresora_tinta'
                    ]
                ];

            case 'software':
                return [
                    [
                        'text' => '📋 Microsoft Office (Word, Excel, PowerPoint)',
                        'action' => 'tech_support_specific',
                        'value' => 'software_office'
                    ],
                    [
                        'text' => '📄 Google Workspace (Docs, Sheets, Drive)',
                        'action' => 'tech_support_specific',
                        'value' => 'software_google'
                    ],
                    [
                        'text' => '⚠️ Otro programa no funciona',
                        'action' => 'tech_support_specific',
                        'value' => 'software_otro'
                    ]
                ];

            case 'acceso':
                return [
                    [
                        'text' => '🔑 Olvidé mi contraseña',
                        'action' => 'tech_support_specific',
                        'value' => 'acceso_password'
                    ],
                    [
                        'text' => '🔒 Mi cuenta está bloqueada',
                        'action' => 'tech_support_specific',
                        'value' => 'acceso_bloqueada'
                    ],
                    [
                        'text' => '🚪 No tengo acceso a archivos',
                        'action' => 'tech_support_specific',
                        'value' => 'acceso_archivos'
                    ]
                ];

            default:
                return [];
        }
    }

    private function handleButtonAction($action, $value, $originalMessage = '')
    {
        $sessionId = Str::uuid();
        
        switch ($action) {
            case 'tech_support_category':
                return $this->handleTechSupportCategory($value, $sessionId);
                
            case 'tech_support_specific':
                return $this->handleTechSupportSpecific($value, $sessionId);
                
            case 'employee_search_type':
                return $this->handleEmployeeSearchType($value, $sessionId);
                
            case 'document_search_category':
                return $this->handleDocumentSearchCategory($value, $sessionId);
                
            case 'document_search_type':
                return $this->handleDocumentSearchType($value, $sessionId);
                
            default:
                return $this->handleInitialMessage($originalMessage);
        }
    }

    private function handleTechSupportCategory($category, $sessionId)
    {
        $responses = [
            'computadora' => "💻 **Problemas con tu computadora**\n\n¿Qué está pasando exactamente? Selecciona el problema más parecido:",
            'internet' => "🌐 **Problemas de Internet**\n\n¿Cuál es el problema específico con tu conexión?",
            'correo' => "📧 **Problemas con el correo electrónico**\n\n¿Qué servicio de correo usas y cuál es el problema?",
            'impresora' => "🖨️ **Problemas con la impresora**\n\n¿Qué problema específico tienes con la impresora?",
            'software' => "📋 **Problemas con programas**\n\n¿Con qué programa tienes problemas?",
            'acceso' => "🔐 **Problemas de acceso**\n\n¿Qué tipo de problema de acceso tienes?"
        ];

        $response = $responses[$category] ?? "Categoría no encontrada";

        // Guardar en base de datos
        try {
            TechSupportConversation::create([
                'session_id' => $sessionId,
                'user_message' => "Selección de categoría: " . $category,
                'problem_category' => $this->mapCategoryToEnum($category),
                'bot_response' => $response,
                'context_data' => json_encode([
                    'timestamp' => now(),
                    'interaction_type' => 'button_click',
                    'category_selected' => $category
                ]),
                'user_ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving tech support category selection: ' . $e->getMessage());
        }

        return [
            'message' => $response,
            'type' => 'tech_support',
            'category' => $category,
            'session_id' => $sessionId,
            'context' => ['step' => 'tech_support', 'category' => $category],
            'buttons' => $this->getTechSupportButtons($category),
            'suggestions' => ['Volver al menú', 'Otro problema', 'Llamar a IT']
        ];
    }

    private function handleTechSupportSpecific($problemType, $sessionId)
    {
        $specificResponses = [
            // Computadora
            'computadora_lenta' => "💻 **Tu computadora está lenta - Te ayudo paso a paso:**\n\n" .
                                  "**Paso 1: Reiniciar (lo más importante)**\n" .
                                  "• Cierra todos los programas que tengas abiertos\n" .
                                  "• Click en el botón de Windows (esquina inferior izquierda)\n" .
                                  "• Click en el ícono de encendido ⚡\n" .
                                  "• Selecciona 'Reiniciar' y espera\n\n" .
                                  "**Paso 2: Si sigue lenta**\n" .
                                  "• No abras muchos programas al mismo tiempo\n" .
                                  "• Cierra pestañas del navegador que no uses\n" .
                                  "• Evita tener muchos archivos en el Escritorio\n\n" .
                                  "**¿Sigues teniendo problemas?** Llama a IT y diles que tu computadora está lenta.",

            'computadora_no_enciende' => "⚡ **Tu computadora no enciende - Revisemos juntos:**\n\n" .
                                        "**Paso 1: Revisar la electricidad**\n" .
                                        "• ¿Está conectado el cable de la pared?\n" .
                                        "• ¿La luz del enchufe está funcionando?\n" .
                                        "• Prueba conectar en otro enchufe\n\n" .
                                        "**Paso 2: Revisar la computadora**\n" .
                                        "• Busca el botón de encendido (suele tener este símbolo ⚡)\n" .
                                        "• Manténlo presionado por 10 segundos\n" .
                                        "• ¿Se enciende alguna luz?\n\n" .
                                        "**Si nada funciona:** Llama inmediatamente a IT.",

            'computadora_pantalla' => "🖥️ **Problemas de pantalla - Verificaciones básicas:**\n\n" .
                                     "**Paso 1: Revisar conexiones**\n" .
                                     "• ¿Está conectado el cable de la pantalla?\n" .
                                     "• ¿Está encendida la pantalla? (busca botón de power)\n" .
                                     "• ¿Hay alguna luz en la pantalla?\n\n" .
                                     "**Paso 2: Reiniciar**\n" .
                                     "• Reinicia la computadora\n" .
                                     "• Espera a que cargue completamente\n\n" .
                                     "**Si la pantalla sigue en negro:** Llama a IT inmediatamente.",

            'computadora_congela' => "❄️ **Computadora se congela - Soluciones:**\n\n" .
                                    "**Paso 1: Forzar reinicio**\n" .
                                    "• Mantén presionado el botón de encendido por 10 segundos\n" .
                                    "• Espera 30 segundos\n" .
                                    "• Vuelve a encender\n\n" .
                                    "**Paso 2: Prevenir congelamiento**\n" .
                                    "• No abras muchos programas a la vez\n" .
                                    "• Cierra pestañas del navegador que no uses\n" .
                                    "• Guarda tu trabajo frecuentemente\n\n" .
                                    "**Si se sigue congelando:** Llama a IT.",

            // Internet
            'internet_wifi' => "📶 **Problemas de WiFi - Guía paso a paso:**\n\n" .
                              "**Paso 1: Revisar la conexión**\n" .
                              "• Mira la esquina inferior derecha de tu pantalla\n" .
                              "• ¿Ves el símbolo del WiFi? 📶\n" .
                              "• Si tiene una X roja, haz click ahí\n" .
                              "• Busca el nombre de tu red WiFi y conecta\n\n" .
                              "**Paso 2: Reiniciar WiFi**\n" .
                              "• Busca la cajita del WiFi (router)\n" .
                              "• Desconecta el cable de la pared por 1 minuto\n" .
                              "• Vuelve a conectar y espera 3 minutos\n\n" .
                              "**Si no funciona:** Llama a IT.",

            'internet_lento' => "🐌 **Internet lento - Mejora tu velocidad:**\n\n" .
                               "**Paso 1: Cerrar programas**\n" .
                               "• Cierra programas que no estés usando\n" .
                               "• Cierra pestañas del navegador\n" .
                               "• Pausa descargas grandes\n\n" .
                               "**Paso 2: Reiniciar conexión**\n" .
                               "• Desconecta y reconecta el WiFi\n" .
                               "• Reinicia tu computadora\n\n" .
                               "**Paso 3: Prueba**\n" .
                               "• Ve a google.com para probar\n" .
                               "• Si sigue lento, llama a IT.",

            'internet_no_navega' => "🚫 **No puedes navegar - Soluciones:**\n\n" .
                                   "**Paso 1: Verificar conexión**\n" .
                                   "• ¿Tienes WiFi conectado?\n" .
                                   "• ¿Otros dispositivos funcionan?\n\n" .
                                   "**Paso 2: Reiniciar navegador**\n" .
                                   "• Cierra completamente el navegador\n" .
                                   "• Abre nuevamente\n" .
                                   "• Intenta ir a google.com\n\n" .
                                   "**Si no funciona:** Llama a IT.",

            // Correo
            'correo_gmail' => "📬 **Problemas con Gmail - Te guío paso a paso:**\n\n" .
                             "**No puedes entrar a Gmail:**\n" .
                             "• Abre tu navegador (Chrome, Edge, etc.)\n" .
                             "• Escribe: gmail.com\n" .
                             "• Usa tu correo completo: tunombre@empresa.com\n" .
                             "• Si no recuerdas la contraseña, click en '¿Olvidaste la contraseña?'\n\n" .
                             "**No te llegan correos:**\n" .
                             "• Revisa la carpeta 'Spam' (correo no deseado)\n" .
                             "• Revisa 'Promociones' (puede estar ahí)\n\n" .
                             "**Para problemas de contraseña:** Llama a IT.",

            'correo_outlook' => "📮 **Problemas con Outlook - Soluciones simples:**\n\n" .
                               "**Outlook no abre:**\n" .
                               "• Cierra completamente el programa\n" .
                               "• Reinicia tu computadora\n" .
                               "• Busca 'Outlook' en el menú de Windows\n\n" .
                               "**No te llegan correos nuevos:**\n" .
                               "• Busca un botón que diga 'Enviar y recibir'\n" .
                               "• Haz click ahí y espera unos minutos\n\n" .
                               "**Si no funciona:** Llama a IT.",

            'correo_acceso' => "🔑 **No puedes entrar al correo:**\n\n" .
                              "**Para Gmail:**\n" .
                              "• Ve a gmail.com\n" .
                              "• Usa tu correo completo\n" .
                              "• Si olvidaste la contraseña, click en 'Recuperar'\n\n" .
                              "**Para Outlook:**\n" .
                              "• Abre el programa Outlook\n" .
                              "• Si pide contraseña, úsala\n\n" .
                              "**Para cuentas de empresa:** Llama inmediatamente a IT.",

            // Impresora
            'impresora_no_imprime' => "🚫 **La impresora no imprime - Revisemos:**\n\n" .
                                     "**Paso 1: Verificaciones básicas**\n" .
                                     "• ¿Está encendida? Busca una luz verde\n" .
                                     "• ¿Tiene papel? Revisa la bandeja\n" .
                                     "• ¿Está conectada? Revisa el cable USB\n\n" .
                                     "**Paso 2: Reiniciar**\n" .
                                     "• Apaga la impresora\n" .
                                     "• Espera 30 segundos\n" .
                                     "• Enciende nuevamente\n\n" .
                                     "**Si no imprime:** Llama a IT.",

            'impresora_papel' => "📄 **Papel atascado - Cómo sacarlo:**\n\n" .
                                "**Paso 1: Apagar impresora**\n" .
                                "• Apaga la impresora primero\n" .
                                "• Nunca saques papel con la impresora encendida\n\n" .
                                "**Paso 2: Sacar papel**\n" .
                                "• Abre todas las tapas que puedas\n" .
                                "• Saca el papel MUY DESPACIO\n" .
                                "• No lo rompas, tira suavemente\n\n" .
                                "**Paso 3: Encender**\n" .
                                "• Cierra todas las tapas\n" .
                                "• Enciende la impresora\n\n" .
                                "**Si no puedes sacarlo:** Llama a IT.",

            'impresora_tinta' => "🖋️ **Problemas de tinta - Qué revisar:**\n\n" .
                                "**Síntomas de poca tinta:**\n" .
                                "• Imprime muy clarito\n" .
                                "• Salen rayas en el papel\n" .
                                "• Parpadea alguna luz\n\n" .
                                "**Qué hacer:**\n" .
                                "• Revisa si hay mensajes en la pantalla\n" .
                                "• Anota qué color falta\n" .
                                "• NO toques los cartuchos\n\n" .
                                "**Para cambio de tintas:** Llama a IT inmediatamente.",

            // Software
            'software_office' => "📋 **Problemas con Microsoft Office:**\n\n" .
                                "**El programa no abre:**\n" .
                                "• Reinicia tu computadora\n" .
                                "• Busca el programa en el menú de Windows\n" .
                                "• Haz doble click en el ícono\n\n" .
                                "**No puedes guardar:**\n" .
                                "• Presiona Ctrl + S\n" .
                                "• Elige 'Documentos' como ubicación\n" .
                                "• Ponle un nombre que reconozcas\n\n" .
                                "**No encuentras archivos:**\n" .
                                "• Ve a 'Archivo' → 'Abrir' → 'Reciente'\n" .
                                "• O busca en 'Documentos'\n\n" .
                                "**Para problemas de licencia:** Llama a IT.",

            'software_google' => "📄 **Problemas con Google Workspace:**\n\n" .
                                "**No cargan documentos:**\n" .
                                "• Abre tu navegador\n" .
                                "• Ve a docs.google.com\n" .
                                "• Usa tu correo de trabajo\n\n" .
                                "**No puedes editar:**\n" .
                                "• Verifica que tengas permisos\n" .
                                "• Si dice 'Solo lectura', pide permisos\n\n" .
                                "**No encuentras archivos:**\n" .
                                "• Ve a drive.google.com\n" .
                                "• Usa el buscador\n" .
                                "• Revisa 'Compartido conmigo'\n\n" .
                                "**Para permisos:** Llama a IT.",

            'software_otro' => "⚠️ **Problema con otro programa:**\n\n" .
                              "**Solución universal:**\n" .
                              "1. Cierra el programa completamente\n" .
                              "2. Reinicia tu computadora\n" .
                              "3. Abre el programa nuevamente\n" .
                              "4. Si no aparece, búscalo en Windows\n\n" .
                              "**Si el problema persiste:**\n" .
                              "• Anota exactamente qué programa es\n" .
                              "• Anota qué error aparece\n" .
                              "• Llama a IT con esa información.",

            // Acceso
            'acceso_password' => "🔑 **Olvidé mi contraseña:**\n\n" .
                                "**Para cuentas de la empresa:**\n" .
                                "• Llama INMEDIATAMENTE a IT\n" .
                                "• NO intentes adivinar la contraseña\n" .
                                "• Ellos pueden resetearla\n\n" .
                                "**Para Gmail personal:**\n" .
                                "• Ve a gmail.com\n" .
                                "• Click en '¿Olvidaste tu contraseña?'\n" .
                                "• Sigue las instrucciones\n\n" .
                                "**🚨 IMPORTANTE:** Nunca le des tu contraseña a nadie.",

            'acceso_bloqueada' => "🔒 **Mi cuenta está bloqueada:**\n\n" .
                                 "**Qué hacer:**\n" .
                                 "• Para 15 minutos sin intentar entrar\n" .
                                 "• NO sigas intentando\n" .
                                 "• Cada intento la bloquea más tiempo\n\n" .
                                 "**Si sigue bloqueada:**\n" .
                                 "• Llama a IT inmediatamente\n" .
                                 "• Diles tu nombre de usuario\n" .
                                 "• Ellos pueden desbloquearla\n\n" .
                                 "**Para prevenir bloqueos:** Anota tu contraseña en lugar seguro.",

            'acceso_archivos' => "🚪 **No tengo acceso a archivos:**\n\n" .
                                "**Paso 1: Verificar**\n" .
                                "• ¿Deberías tener acceso a este archivo?\n" .
                                "• Pregunta a tu jefe o compañeros\n\n" .
                                "**Paso 2: Solicitar acceso**\n" .
                                "• Llama a IT\n" .
                                "• Diles exactamente qué archivo necesitas\n" .
                                "• Diles quién te autorizó\n\n" .
                                "**Paso 3: Esperar**\n" .
                                "• Los permisos pueden tardar unos minutos\n" .
                                "• Cierra y abre el archivo después"
        ];

        $response = $specificResponses[$problemType] ?? "Tipo de problema no encontrado.";
        
        // Determinar categoría del problema
        $category = 'general';
        if (str_starts_with($problemType, 'computadora_')) $category = 'computadora';
        elseif (str_starts_with($problemType, 'internet_')) $category = 'internet';
        elseif (str_starts_with($problemType, 'correo_')) $category = 'correo';
        elseif (str_starts_with($problemType, 'impresora_')) $category = 'impresora';
        elseif (str_starts_with($problemType, 'software_')) $category = 'software';
        elseif (str_starts_with($problemType, 'acceso_')) $category = 'acceso';

        // Guardar en base de datos
        try {
            TechSupportConversation::create([
                'session_id' => $sessionId,
                'user_message' => "Problema específico: " . $problemType,
                'problem_category' => $this->mapCategoryToEnum($category),
                'problem_type' => $problemType,
                'bot_response' => $response,
                'context_data' => json_encode([
                    'timestamp' => now(),
                    'interaction_type' => 'specific_problem',
                    'problem_type' => $problemType
                ]),
                'user_ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving specific tech support response: ' . $e->getMessage());
        }

        return [
            'message' => $response,
            'type' => 'tech_support',
            'category' => $category,
            'problem_type' => $problemType,
            'session_id' => $sessionId,
            'context' => ['step' => 'tech_support_complete'],
            'buttons' => [
                [
                    'text' => '✅ Esto resolvió mi problema',
                    'action' => 'problem_resolved',
                    'value' => $sessionId
                ],
                [
                    'text' => '❌ Sigo teniendo problemas',
                    'action' => 'problem_not_resolved',
                    'value' => $sessionId
                ],
                [
                    'text' => '📞 Llamar a IT',
                    'action' => 'call_it',
                    'value' => $problemType
                ],
                [
                    'text' => '🔄 Otro problema',
                    'action' => 'tech_support_category',
                    'value' => 'general'
                ]
            ],
            'suggestions' => ['Problema resuelto', 'Llamar a IT', 'Nuevo problema']
        ];
    }

    private function handleLocationSearch($message, $context)
    {
        $locations = CompanyLocation::active()->search($message)->limit(5)->get();
        
        if ($locations->count() > 0) {
            $response = "Encontré " . $locations->count() . " ubicación(es):\n\n";
            foreach ($locations as $loc) {
                $response .= "📍 **{$loc->name}** ({$loc->type_label})\n";
                $response .= "   🏠 {$loc->full_address}\n";
                $response .= "   📞 {$loc->phone}\n";
                if ($loc->contact_person) {
                    $response .= "   👤 Contacto: {$loc->contact_person}\n";
                }
                $response .= "\n";
            }
        } else {
            $response = "No encontré ubicaciones con esa información. ¿Podrías ser más específico?";
        }
        
        return [
            'message' => $response,
            'context' => ['step' => 'location_search', 'type' => 'location'],
            'suggestions' => ['Nueva búsqueda', 'Ver todas', 'Menú principal']
        ];
    }

    private function handleEmployeeSearchType($searchType, $sessionId)
    {
        switch ($searchType) {
            case 'name':
                return [
                    'message' => '👤 **Búsqueda por nombre**

Escribe el nombre de la persona que buscas. Puedes usar:
• Nombre completo: "María González"
• Solo nombre: "María"
• Solo apellido: "González"

¿A quién buscas?',
                    'context' => ['step' => 'employee_search', 'search_type' => 'name'],
                    'suggestions' => ['María', 'González', 'Juan', 'Menú principal']
                ];

            case 'department':
                try {
                    $departments = TempEmployee::getAllDepartments();
                    if ($departments && $departments->count() > 0) {
                        $deptArray = $departments->toArray();
                        return [
                            'message' => '🏢 **Buscar por departamento**

Selecciona un departamento o escribe su nombre:

• ' . implode("\n• ", $deptArray),
                            'context' => ['step' => 'employee_search', 'search_type' => 'department'],
                            'suggestions' => array_slice($deptArray, 0, 4)
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Error getting departments: ' . $e->getMessage());
                }
                
                return [
                    'message' => '🏢 **Buscar por departamento**

Escribe el nombre del departamento (ej: Tecnología, Recursos Humanos, Ventas)',
                    'context' => ['step' => 'employee_search', 'search_type' => 'department'],
                    'suggestions' => ['Tecnología', 'Recursos Humanos', 'Ventas', 'Menú principal']
                ];

            case 'position':
                try {
                    $positions = TempEmployee::getAllPositions();
                    if ($positions && $positions->count() > 0) {
                        $positionsArray = $positions->take(10)->toArray();
                        return [
                            'message' => '💼 **Buscar por cargo**

Selecciona un cargo o escribe el nombre:

• ' . implode("\n• ", $positionsArray),
                            'context' => ['step' => 'employee_search', 'search_type' => 'position'],
                            'suggestions' => array_slice($positionsArray, 0, 4)
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Error getting positions: ' . $e->getMessage());
                }
                
                return [
                    'message' => '💼 **Buscar por cargo**

Escribe el nombre del cargo (ej: Gerente, Desarrollador, Analista)',
                    'context' => ['step' => 'employee_search', 'search_type' => 'position'],
                    'suggestions' => ['Gerente', 'Desarrollador', 'Analista', 'Menú principal']
                ];

            case 'list_departments':
                try {
                    $departments = TempEmployee::getAllDepartments();
                    if ($departments && $departments->count() > 0) {
                        $deptArray = $departments->toArray();
                        return [
                            'message' => '📋 **Departamentos Disponibles:**

• ' . implode("\n• ", $deptArray) . '

Haz clic en uno para ver sus empleados o escribe el nombre.',
                            'context' => ['step' => 'employee_search', 'search_type' => 'department'],
                            'suggestions' => array_slice($deptArray, 0, 4)
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Error getting departments: ' . $e->getMessage());
                }
                
                return [
                    'message' => '❌ No se pudieron obtener los departamentos.',
                    'context' => ['step' => 'employee_search'],
                    'suggestions' => ['Nueva búsqueda', 'Menú principal']
                ];

            case 'list_positions':
                try {
                    $positions = TempEmployee::getAllPositions();
                    if ($positions && $positions->count() > 0) {
                        $positionsArray = $positions->take(15)->toArray();
                        return [
                            'message' => '💼 **Cargos Disponibles:**

• ' . implode("\n• ", $positionsArray) . '

Haz clic en uno para ver quién tiene ese cargo o escribe el nombre.',
                            'context' => ['step' => 'employee_search', 'search_type' => 'position'],
                            'suggestions' => array_slice($positionsArray, 0, 4)
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Error getting positions: ' . $e->getMessage());
                }
                
                return [
                    'message' => '❌ No se pudieron obtener los cargos.',
                    'context' => ['step' => 'employee_search'],
                    'suggestions' => ['Nueva búsqueda', 'Menú principal']
                ];

            case 'team':
                return [
                    'message' => '👥 **Buscar equipo de trabajo**

Escribe el email del manager para ver su equipo:
Ejemplo: "empleados de juan.perez@empresa.com"

¿De quién quieres ver el equipo?',
                    'context' => ['step' => 'employee_search', 'search_type' => 'team'],
                    'suggestions' => ['juan.perez@empresa.com', 'maria.gonzalez@empresa.com', 'Menú principal']
                ];

            default:
                return $this->handleInitialMessage('');
        }
    }

    private function handleDocumentSearchCategory($category, $sessionId)
    {
        $categoryNames = [
            'contexto_planificacion' => 'Contexto de Planificación',
            'procedimientos_normativos' => 'Procedimientos Normativos',
            'procedimientos_operativos' => 'Procedimientos Operativos',
            'mejora_continua' => 'Mejora Continua',
            'general' => 'General'
        ];

        $categoryName = $categoryNames[$category] ?? 'Categoría desconocida';

        try {
            $documents = CompanyDocument::active()->where('category', $category)->limit(15)->get();
            
            if ($documents->count() > 0) {
                $response = "📂 **{$categoryName}** ({$documents->count()} documentos):\n\n";
                
                foreach ($documents as $doc) {
                    $response .= "📄 **{$doc->title}**\n";
                    if ($doc->description) {
                        $response .= "   ℹ️ {$doc->description}\n";
                    }
                    if ($doc->external_url) {
                        $response .= "   🔗 [Ver documento]({$doc->external_url})\n";
                    }
                    $response .= "\n";
                }
                
                $response .= "¿Necesitas información específica de algún documento?";
                
                return [
                    'message' => $response,
                    'context' => ['step' => 'document_search', 'category' => $category],
                    'suggestions' => ['Ver otro', 'Buscar específico', 'Ver categorías', 'Menú principal']
                ];
            } else {
                return [
                    'message' => "📂 **{$categoryName}**\n\n❌ No hay documentos disponibles en esta categoría.\n\n¿Quieres ver otra categoría?",
                    'context' => ['step' => 'document_search'],
                    'buttons' => $this->getDocumentSearchButtons('general'),
                    'suggestions' => ['Ver categorías', 'Menú principal']
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error getting documents by category: ' . $e->getMessage());
            return [
                'message' => "❌ Hubo un error al obtener los documentos de {$categoryName}.",
                'context' => ['step' => 'document_search'],
                'suggestions' => ['Nueva búsqueda', 'Ver categorías', 'Menú principal']
            ];
        }
    }

    private function handleDocumentSearchType($searchType, $sessionId)
    {
        switch ($searchType) {
            case 'specific_search':
                return [
                    'message' => '🔍 **Búsqueda específica de documentos**

Escribe palabras clave del documento que buscas:
• Nombre del documento
• Tema específico
• Palabras que recuerdes

¿Qué documento necesitas?',
                    'context' => ['step' => 'document_search', 'search_type' => 'specific'],
                    'suggestions' => ['Manual', 'Política', 'Procedimiento', 'Menú principal']
                ];

            default:
                return $this->handleInitialMessage('');
        }
    }

    private function handleDocumentSearch($message, $context)
    {
        // Comandos especiales
        if (str_contains($message, 'menú') || str_contains($message, 'inicio')) {
            return $this->handleInitialMessage('');
        }
        
        // Ver todas las categorías
        if (str_contains($message, 'ver categorías') || str_contains($message, 'categorías') || str_contains($message, 'todas las categorías')) {
            return [
                'message' => "📋 **Categorías de Documentos Disponibles:**

• **Contexto de Planificación** - Documentos de planificación estratégica
• **Procedimientos Normativos** - Normas y políticas corporativas  
• **Procedimientos Operativos** - Procesos y procedimientos operacionales
• **Mejora Continua** - Documentos de calidad y mejora continua
• **General** - Documentos generales de la empresa

¿Sobre qué categoría quieres buscar documentos?",
                'context' => ['step' => 'document_search', 'type' => 'document'],
                'suggestions' => ['Procedimientos Operativos', 'Mejora Continua', 'Contexto de Planificación', 'General']
            ];
        }
        
        // Ver todos los documentos
        if (str_contains($message, 'ver todos') || str_contains($message, 'todos los documentos') || str_contains($message, 'lista completa')) {
            try {
                $documents = CompanyDocument::active()->limit(15)->get();
                
                if ($documents->count() > 0) {
                    $response = "📋 **Lista de Documentos Corporativos** (mostrando {$documents->count()}):\n\n";
                    
                    $categorizedDocs = $documents->groupBy('category');
                    
                    foreach ($categorizedDocs as $category => $categoryDocs) {
                        $categoryName = match($category) {
                            'contexto_planificacion' => 'Contexto de Planificación',
                            'procedimientos_normativos' => 'Procedimientos Normativos',
                            'procedimientos_operativos' => 'Procedimientos Operativos',
                            'mejora_continua' => 'Mejora Continua',
                            'general' => 'General',
                            default => 'Sin Categoría'
                        };
                        
                        $response .= "**📂 {$categoryName}:**\n";
                        foreach ($categoryDocs as $doc) {
                            $response .= "   📄 {$doc->title}";
                            if ($doc->external_url) {
                                $response .= " - [Ver documento]({$doc->external_url})";
                            }
                            $response .= "\n";
                        }
                        $response .= "\n";
                    }
                    
                    $response .= "¿Necesitas información específica de algún documento?";
                    
                    return [
                        'message' => $response,
                        'context' => ['step' => 'document_search', 'type' => 'document'],
                        'suggestions' => ['Buscar específico', 'Ver categorías', 'Menú principal']
                    ];
                } else {
                    return [
                        'message' => "❌ No hay documentos disponibles en el sistema.\n\n¿Quieres hacer otra consulta?",
                        'context' => ['step' => 'document_search', 'type' => 'document'],
                        'suggestions' => ['Nueva búsqueda', 'Menú principal']
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error al obtener documentos: ' . $e->getMessage());
                return [
                    'message' => "❌ Hubo un error al obtener los documentos. Por favor intenta de nuevo.",
                    'context' => ['step' => 'document_search', 'type' => 'document'],
                    'suggestions' => ['Nueva búsqueda', 'Menú principal']
                ];
            }
        }
        
        // Buscar por categoría específica
        $categoryMappings = [
            'contexto_planificacion' => ['contexto', 'planificación', 'planificacion', 'estratégica', 'estrategica', 'planificar'],
            'procedimientos_normativos' => ['normativos', 'normativo', 'políticas', 'politicas', 'normas', 'reglamentos'],
            'procedimientos_operativos' => ['operativos', 'operativo', 'procedimientos', 'procesos', 'operaciones'],
            'mejora_continua' => ['mejora', 'continua', 'calidad', 'mejoramiento', 'optimización'],
            'general' => ['general', 'generales', 'varios', 'otros']
        ];
        
        $searchCategory = null;
        foreach ($categoryMappings as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    $searchCategory = $category;
                    break 2;
                }
            }
        }
        
        if ($searchCategory) {
            try {
                $documents = CompanyDocument::active()->where('category', $searchCategory)->limit(10)->get();
                
                if ($documents->count() > 0) {
                    $categoryName = match($searchCategory) {
                        'contexto_planificacion' => 'Contexto de Planificación',
                        'procedimientos_normativos' => 'Procedimientos Normativos',
                        'procedimientos_operativos' => 'Procedimientos Operativos',
                        'mejora_continua' => 'Mejora Continua',
                        'general' => 'General',
                        default => 'Sin Categoría'
                    };
                    
                    $response = "📂 **Documentos de {$categoryName}** ({$documents->count()} encontrados):\n\n";
                    
                    foreach ($documents as $doc) {
                        $response .= "📄 **{$doc->title}**\n";
                        $response .= "   📂 Tipo: " . ucfirst($doc->type) . "\n";
                        $response .= "   🏢 Departamento: {$doc->department}\n";
                        
                        if ($doc->description) {
                            $response .= "   📝 " . substr($doc->description, 0, 100) . (strlen($doc->description) > 100 ? '...' : '') . "\n";
                        }
                        
                        if ($doc->external_url) {
                            $response .= "   🔗 [Ver documento]({$doc->external_url})\n";
                        }
                        
                        if ($doc->owner_email) {
                            $response .= "   👤 Responsable: {$doc->owner_email}\n";
                        }
                        
                        $response .= "\n";
                    }
                    
                    $response .= "¿Necesitas información específica de algún documento?";
                    
                    $suggestions = ['Ver detalles', 'Otra categoría', 'Ver todas', 'Menú principal'];
                    
                    return [
                        'message' => $response,
                        'context' => ['step' => 'document_search', 'type' => 'document'],
                        'suggestions' => $suggestions
                    ];
                } else {
                    $categoryName = match($searchCategory) {
                        'contexto_planificacion' => 'Contexto de Planificación',
                        'procedimientos_normativos' => 'Procedimientos Normativos',
                        'procedimientos_operativos' => 'Procedimientos Operativos',
                        'mejora_continua' => 'Mejora Continua',
                        'general' => 'General',
                        default => 'Sin Categoría'
                    };
                    
                    return [
                        'message' => "❌ No encontré documentos en la categoría **{$categoryName}**.\n\n¿Quieres buscar en otra categoría?",
                        'context' => ['step' => 'document_search', 'type' => 'document'],
                        'suggestions' => ['Ver categorías', 'IT', 'HR', 'Menú principal']
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error al buscar documentos por categoría: ' . $e->getMessage());
                return [
                    'message' => "❌ Hubo un error al buscar documentos. Por favor intenta de nuevo.",
                    'context' => ['step' => 'document_search', 'type' => 'document'],
                    'suggestions' => ['Nueva búsqueda', 'Menú principal']
                ];
            }
        }
        
        // Búsqueda general por texto
        try {
            $documents = CompanyDocument::active()->search($message)->limit(8)->get();
            
            if ($documents->count() > 0) {
                $response = "✅ **Encontré {$documents->count()} documento(s):**\n\n";
                
                foreach ($documents as $doc) {
                    $response .= "📄 **{$doc->title}**\n";
                    
                    $categoryName = match($doc->category) {
                        'contexto_planificacion' => 'Contexto de Planificación',
                        'procedimientos_normativos' => 'Procedimientos Normativos',
                        'procedimientos_operativos' => 'Procedimientos Operativos',
                        'mejora_continua' => 'Mejora Continua',
                        'general' => 'General',
                        default => 'Sin Categoría'
                    };
                    
                    $response .= "   📂 {$categoryName} - " . ucfirst($doc->type) . "\n";
                    $response .= "   🏢 {$doc->department}\n";
                    
                    if ($doc->description) {
                        $response .= "   📝 " . substr($doc->description, 0, 80) . (strlen($doc->description) > 80 ? '...' : '') . "\n";
                    }
                    
                    if ($doc->external_url) {
                        $response .= "   🔗 [Ver documento]({$doc->external_url})\n";
                    }
                    
                    if ($doc->effective_date) {
                        $response .= "   📅 Vigente desde: " . date('d/m/Y', strtotime($doc->effective_date)) . "\n";
                    }
                    
                    $response .= "\n";
                }
                
                if ($documents->count() >= 8) {
                    $response .= "_Mostrando los primeros 8 resultados. Sé más específico para menos resultados._\n\n";
                }
                
                $response .= "¿Necesitas información específica de algún documento o quieres hacer otra búsqueda?";
                
                $suggestions = ['Ver detalles', 'Nueva búsqueda', 'Ver categorías', 'Menú principal'];
                
                // Agregar categorías como sugerencias si hay varios documentos
                if ($documents->count() > 1) {
                    $categories = $documents->pluck('category')->unique()->take(2);
                    foreach ($categories as $cat) {
                        $categoryName = match($cat) {
                            'contexto_planificacion' => 'Contexto Planificación',
                            'procedimientos_normativos' => 'Proc. Normativos',
                            'procedimientos_operativos' => 'Proc. Operativos',
                            'mejora_continua' => 'Mejora Continua',
                            'general' => 'General',
                            default => ucfirst($cat)
                        };
                        $suggestions = array_merge([$categoryName], $suggestions);
                    }
                }
                
            } else {
                $response = "❌ **No encontré documentos** con la información \"*{$message}*\".\n\n";
                $response .= "💡 **Sugerencias:**\n";
                $response .= "• Intenta con el **nombre del documento** o **palabras clave**\n";
                $response .= "• Busca por **categoría** (ej: HR, IT, Finanzas)\n";
                $response .= "• Busca por **tipo** (ej: manual, política, procedimiento)\n";
                $response .= "• Verifica la **ortografía**\n\n";
                $response .= "¿Quieres intentar otra búsqueda?";
                
                $suggestions = ['Ver categorías', 'Procedimientos', 'Mejora Continua', 'General', 'Menú principal'];
            }
            
            return [
                'message' => $response,
                'context' => ['step' => 'document_search', 'type' => 'document'],
                'suggestions' => $suggestions
            ];
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de documentos: ' . $e->getMessage());
            return [
                'message' => "❌ Hubo un error al buscar documentos. Por favor intenta de nuevo.",
                'context' => ['step' => 'document_search', 'type' => 'document'],
                'suggestions' => ['Nueva búsqueda', 'Ver categorías', 'Menú principal']
            ];
        }
    }

    private function getEmployeeSearchButtons($category)
    {
        if ($category === 'general') {
            return [
                [
                    'text' => '👤 Buscar por nombre',
                    'action' => 'employee_search_type',
                    'value' => 'name',
                    'description' => 'Encuentra un empleado específico'
                ],
                [
                    'text' => '🏢 Buscar por departamento',
                    'action' => 'employee_search_type',
                    'value' => 'department',
                    'description' => 'Ver empleados de un área'
                ],
                [
                    'text' => '💼 Buscar por cargo',
                    'action' => 'employee_search_type',
                    'value' => 'position',
                    'description' => 'Encontrar personas por puesto'
                ],
                [
                    'text' => '📋 Ver departamentos',
                    'action' => 'employee_search_type',
                    'value' => 'list_departments',
                    'description' => 'Lista completa de departamentos'
                ],
                [
                    'text' => '📊 Ver cargos',
                    'action' => 'employee_search_type',
                    'value' => 'list_positions',
                    'description' => 'Lista completa de posiciones'
                ],
                [
                    'text' => '👥 Buscar equipo',
                    'action' => 'employee_search_type',
                    'value' => 'team',
                    'description' => 'Empleados de un manager'
                ]
            ];
        }
        return [];
    }

    private function getDocumentSearchButtons($category)
    {
        if ($category === 'general') {
            return [
                [
                    'text' => '📊 Contexto de Planificación',
                    'action' => 'document_search_category',
                    'value' => 'contexto_planificacion',
                    'description' => 'Documentos de planificación estratégica'
                ],
                [
                    'text' => '📋 Procedimientos Normativos',
                    'action' => 'document_search_category',
                    'value' => 'procedimientos_normativos',
                    'description' => 'Normas y políticas corporativas'
                ],
                [
                    'text' => '⚙️ Procedimientos Operativos',
                    'action' => 'document_search_category',
                    'value' => 'procedimientos_operativos',
                    'description' => 'Procesos y procedimientos operacionales'
                ],
                [
                    'text' => '📈 Mejora Continua',
                    'action' => 'document_search_category',
                    'value' => 'mejora_continua',
                    'description' => 'Documentos de calidad y mejora'
                ],
                [
                    'text' => '📄 General',
                    'action' => 'document_search_category',
                    'value' => 'general',
                    'description' => 'Documentos generales de la empresa'
                ],
                [
                    'text' => '🔍 Buscar específico',
                    'action' => 'document_search_type',
                    'value' => 'specific_search',
                    'description' => 'Buscar por palabra clave'
                ]
            ];
        }
        return [];
    }
}
