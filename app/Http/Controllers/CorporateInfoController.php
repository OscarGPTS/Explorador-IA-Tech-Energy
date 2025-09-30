<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\CompanyLocation;
use App\Models\CompanyDocument;

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
        
        $query = Employee::active();
        
        if (!empty($search)) {
            $query->search($search);
        }
        
        if (!empty($department)) {
            $query->byDepartment($department);
        }
        
        $employees = $query->limit(20)->get();
        
        return response()->json([
            'employees' => $employees,
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
        $message = $request->get('message', '');
        $context = $request->get('context', []);
        
        // Procesar el mensaje del usuario
        $response = $this->processUserMessage($message, $context);
        
        return response()->json($response);
    }

    private function processUserMessage($message, $context)
    {
        // Limpiar y normalizar el mensaje
        $message = strtolower(trim($message));
        
        // Si no hay contexto, inicializar
        if (empty($context['step'])) {
            return $this->handleInitialMessage($message);
        }
        
        // Procesar según el paso actual
        switch ($context['step']) {
            case 'employee_search':
                return $this->handleEmployeeSearch($message, $context);
            case 'location_search':
                return $this->handleLocationSearch($message, $context);
            case 'document_search':
                return $this->handleDocumentSearch($message, $context);
            default:
                return $this->handleInitialMessage($message);
        }
    }

    private function handleInitialMessage($message)
    {
        // Detectar intención basada en palabras clave
        if (str_contains($message, 'empleado') || str_contains($message, 'persona') || str_contains($message, 'contacto') || str_contains($message, 'staff')) {
            return [
                'message' => '👤 **Búsqueda de Empleados**

¿Qué información específica necesitas?

• Buscar por **nombre** (ej: "María González")
• Buscar por **departamento** (ej: "Tecnología")
• Buscar por **cargo** (ej: "Gerente")
• Ver **directorio completo**

Escribe el nombre, departamento o cargo que buscas.',
                'context' => ['step' => 'employee_search', 'type' => 'employee'],
                'suggestions' => ['Tecnología', 'Recursos Humanos', 'Finanzas', 'Ver todos']
            ];
        }
        
        if (str_contains($message, 'oficina') || str_contains($message, 'ubicación') || str_contains($message, 'dirección') || str_contains($message, 'lugar')) {
            return [
                'message' => '📍 **Ubicaciones de la Empresa**

Puedo ayudarte con información sobre:

• **Oficina Principal** - Sede central
• **Sucursales** - Oficinas regionales  
• **Centros de Datos** - Infraestructura IT
• **Almacenes** - Centros logísticos

¿Qué ubicación específica te interesa o quieres ver todas?',
                'context' => ['step' => 'location_search', 'type' => 'location'],
                'suggestions' => ['Oficina Principal', 'Ver todas', 'Por ciudad']
            ];
        }
        
        if (str_contains($message, 'documento') || str_contains($message, 'archivo') || str_contains($message, 'manual') || str_contains($message, 'política') || str_contains($message, 'procedimiento')) {
            return [
                'message' => '📄 **Documentos Corporativos**

Categorías disponibles:

• **HR** - Políticas de recursos humanos
• **IT** - Manuales técnicos y procedimientos
• **Finanzas** - Políticas financieras
• **Legal** - Documentos legales
• **Operaciones** - Procedimientos operativos

¿Qué tipo de documento necesitas?',
                'context' => ['step' => 'document_search', 'type' => 'document'],
                'suggestions' => ['Políticas HR', 'Manuales IT', 'Procedimientos', 'Ver categorías']
            ];
        }
        
        // Respuesta por defecto mejorada
        return [
            'message' => '🏢 **¡Hola! Soy tu Asistente Corporativo**

Puedo ayudarte con información interna de la empresa:

**� Empleados**
Buscar contactos, departamentos y cargos

**📍 Ubicaciones**  
Oficinas, direcciones y horarios

**📄 Documentos**
Políticas, manuales y procedimientos

**¿Qué información necesitas?**',
            'context' => ['step' => 'initial'],
            'suggestions' => ['Buscar empleado', 'Ver ubicaciones', 'Encontrar documento', 'Ayuda']
        ];
    }

    private function handleEmployeeSearch($message, $context)
    {
        // Comandos especiales
        if (str_contains($message, 'menú') || str_contains($message, 'inicio')) {
            return $this->handleInitialMessage('');
        }
        
        if (str_contains($message, 'ver todos') || str_contains($message, 'todos los departamentos') || str_contains($message, 'departamentos')) {
            $departments = Employee::getDepartments();
            $deptList = implode('\n• ', $departments);
            
            return [
                'message' => "📋 **Departamentos Disponibles:**\n\n• " . $deptList . "\n\n¿Sobre cuál departamento quieres información?",
                'context' => ['step' => 'employee_search', 'type' => 'employee'],
                'suggestions' => array_slice($departments, 0, 3)
            ];
        }
        
        // Buscar empleados
        $employees = Employee::active()->search($message)->limit(8)->get();
        
        if ($employees->count() > 0) {
            $response = "✅ **Encontré {$employees->count()} empleado(s):**\n\n";
            
            foreach ($employees as $emp) {
                $response .= "👤 **{$emp->full_name}**\n";
                $response .= "   📧 {$emp->email}\n";
                $response .= "   📞 {$emp->phone}";
                if ($emp->extension) {
                    $response .= " ext. {$emp->extension}";
                }
                $response .= "\n   💼 {$emp->position}\n";
                $response .= "   🏢 {$emp->department}\n";
                $response .= "   📍 {$emp->location}\n\n";
            }
            
            if ($employees->count() >= 8) {
                $response .= "_Mostrando los primeros 8 resultados. Sé más específico para menos resultados._\n\n";
            }
            
            $response .= "¿Necesitas información de algún empleado específico o quieres hacer otra búsqueda?";
            
            $suggestions = ['Nueva búsqueda', 'Ver departamentos', 'Menú principal'];
            
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

    private function handleDocumentSearch($message, $context)
    {
        $documents = CompanyDocument::active()->search($message)->limit(5)->get();
        
        if ($documents->count() > 0) {
            $response = "Encontré " . $documents->count() . " documento(s):\n\n";
            foreach ($documents as $doc) {
                $response .= "📄 **{$doc->title}**\n";
                $response .= "   📂 {$doc->category_label} - {$doc->type_label}\n";
                $response .= "   🏢 {$doc->department}\n";
                if ($doc->description) {
                    $response .= "   📝 {$doc->description}\n";
                }
                $response .= "\n";
            }
        } else {
            $response = "No encontré documentos con esa información. ¿Podrías ser más específico sobre el tipo de documento?";
        }
        
        return [
            'message' => $response,
            'context' => ['step' => 'document_search', 'type' => 'document'],
            'suggestions' => ['Nueva búsqueda', 'Ver categorías', 'Menú principal']
        ];
    }
}
