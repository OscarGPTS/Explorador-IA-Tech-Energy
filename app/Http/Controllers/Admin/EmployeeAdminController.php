<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmployeeAdminController extends Controller
{
    /**
     * Mostrar lista de empleados
     */
    public function index(Request $request)
    {
        Log::info("Accediendo a index de empleados con parámetros: " . json_encode($request->all()));
        
        $query = TempEmployee::query();

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
            Log::info("Aplicando filtro de búsqueda: " . $request->search);
        }

        if ($request->filled('department')) {
            $query->byDepartment($request->department);
            Log::info("Aplicando filtro de departamento: " . $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            Log::info("Aplicando filtro de estado: " . $request->status);
        }

        if ($request->filled('access_filter')) {
            if ($request->access_filter === 'with_access') {
                $query->withSystemAccess();
                Log::info("Aplicando filtro: solo con acceso");
            } elseif ($request->access_filter === 'without_access') {
                $query->withoutSystemAccess();
                Log::info("Aplicando filtro: solo sin acceso");
            }
        }

        Log::info("SQL Query antes de paginar: " . $query->toSql());
        Log::info("Bindings: " . json_encode($query->getBindings()));

        $employees = $query->orderBy('first_name')
                          ->orderBy('last_name')
                          ->paginate(20);

        Log::info("Empleados encontrados: " . $employees->count() . " de un total de " . $employees->total());

        $departments = TempEmployee::getAllDepartments();
        $totalEmployees = TempEmployee::count();
        $withAccess = TempEmployee::withSystemAccess()->count();
        $withoutAccess = TempEmployee::withoutSystemAccess()->count();

        Log::info("Estadísticas - Total: $totalEmployees, Con acceso: $withAccess, Sin acceso: $withoutAccess");

        return view('admin.employees.index', compact(
            'employees', 
            'departments', 
            'totalEmployees', 
            'withAccess', 
            'withoutAccess'
        ));
    }

    /**
     * Mostrar formulario de importación
     */
    public function import()
    {
        return view('admin.employees.import');
    }

    /**
     * Procesar archivo de importación
     */
    public function processImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');
        $path = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();

        try {
            if (in_array($extension, ['csv', 'txt'])) {
                $result = $this->processCsvFile($path);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                $result = $this->processExcelFile($path);
            } else {
                throw new \Exception('Formato de archivo no soportado');
            }

            return back()->with('success', 
                "Importación completada. {$result['created']} empleados creados, {$result['updated']} actualizados, {$result['skipped']} omitidos."
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Procesar archivo CSV
     */
    private function processCsvFile($path)
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;
        
        if (($handle = fopen($path, 'r')) !== FALSE) {
            $header = null;
            $delimiter = $this->detectDelimiter($path);
            
            Log::info("Procesando archivo CSV con delimitador: " . ($delimiter === "\t" ? "tabulación" : $delimiter));
            
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header) {
                    $header = array_map('trim', $row);
                    Log::info("Encabezados detectados: " . json_encode($header));
                    continue;
                }
                
                // Limpiar espacios en blanco de cada celda
                $row = array_map('trim', $row);
                
                // Verificar que la fila tenga datos
                if (empty(array_filter($row))) {
                    continue;
                }
                
                $data = array_combine($header, $row);
                Log::info("Procesando fila: " . json_encode($data));
                
                $result = $this->processEmployeeRow($data);
                Log::info("Resultado del procesamiento: " . $result);
                
                switch ($result) {
                    case 'created':
                        $created++;
                        break;
                    case 'updated':
                        $updated++;
                        break;
                    case 'skipped':
                        $skipped++;
                        break;
                }
            }
            
            fclose($handle);
        }
        
        Log::info("Resultados finales - Creados: $created, Actualizados: $updated, Omitidos: $skipped");
        return compact('created', 'updated', 'skipped');
    }

    /**
     * Detectar el delimitador del archivo CSV
     */
    private function detectDelimiter($path)
    {
        $handle = fopen($path, 'r');
        $firstLine = fgets($handle);
        fclose($handle);
        
        $delimiters = [',', ';', "\t", '|'];
        $maxCount = 0;
        $bestDelimiter = ',';
        
        foreach ($delimiters as $delimiter) {
            $count = substr_count($firstLine, $delimiter);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delimiter;
            }
        }
        
        return $bestDelimiter;
    }

    /**
     * Procesar archivo Excel
     */
    private function processExcelFile($path)
    {
        // Para archivos Excel, convertir a CSV primero o usar una biblioteca alternativa
        // Por ahora, sugerir al usuario que convierta a CSV
        throw new \Exception('Para procesar archivos Excel, por favor guarda el archivo como CSV (separado por tabulaciones). Esto garantiza mejor compatibilidad.');
    }

    /**
     * Procesar una fila de datos de empleado
     */
    private function processEmployeeRow($data)
    {
        Log::info("Iniciando procesamiento de fila con datos: " . json_encode($data));
        
        // Mapear los nombres de columnas (tanto en español como inglés)
        $mapping = [
            'nombre_completo' => ['NOMBRE COMPLETO', 'NOMBRE_COMPLETO', 'nombre_completo', 'full_name', 'nombre', 'Nombre', 'Name'],
            'area' => ['ÁREA', 'AREA', 'área', 'area', 'Area'],
            'departamento' => ['DEPARTAMENTO', 'departamento', 'department', 'Department'],
            'correo' => ['CORREO', 'EMAIL', 'correo', 'email', 'e-mail', 'Email', 'E-mail']
        ];

        $mappedData = [];
        foreach ($mapping as $key => $variations) {
            foreach ($variations as $variation) {
                if (isset($data[$variation]) && !empty(trim($data[$variation]))) {
                    $mappedData[$key] = trim($data[$variation]);
                    Log::info("Mapeado $key = " . $mappedData[$key] . " desde columna '$variation'");
                    break;
                }
            }
        }

        Log::info("Datos mapeados: " . json_encode($mappedData));

        // Validar datos requeridos
        if (empty($mappedData['nombre_completo']) || empty($mappedData['correo'])) {
            Log::warning("Fila omitida - falta nombre completo o correo");
            return 'skipped';
        }

        // Validar email
        if (!filter_var($mappedData['correo'], FILTER_VALIDATE_EMAIL)) {
            Log::warning("Fila omitida - email inválido: " . $mappedData['correo']);
            return 'skipped';
        }

        // Separar nombre completo en nombre y apellido
        $nameParts = explode(' ', $mappedData['nombre_completo'], 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // Verificar si el empleado ya existe por email
        $existingEmployee = TempEmployee::where('email', $mappedData['correo'])->first();

        $employeeData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $mappedData['correo'],
            'position' => $mappedData['area'] ?? 'No especificado',
            'department' => $mappedData['departamento'] ?? 'No especificado',
            'status' => 'active',
            'is_active' => true,
            'data_imported_at' => now(),
            'import_source' => 'csv_upload'
        ];

        Log::info("Datos del empleado a guardar: " . json_encode($employeeData));

        if ($existingEmployee) {
            Log::info("Empleado existente encontrado con ID: " . $existingEmployee->id);
            // Verificar si hay cambios
            $hasChanges = false;
            foreach (['first_name', 'last_name', 'position', 'department'] as $field) {
                if ($existingEmployee->$field !== $employeeData[$field]) {
                    Log::info("Cambio detectado en $field: '{$existingEmployee->$field}' -> '{$employeeData[$field]}'");
                    $hasChanges = true;
                }
            }

            if ($hasChanges) {
                $employeeData['last_sync_at'] = now();
                $existingEmployee->update($employeeData);
                Log::info("Empleado actualizado con ID: " . $existingEmployee->id);
                return 'updated';
            } else {
                Log::info("Empleado sin cambios, omitido");
                return 'skipped';
            }
        } else {
            // Generar employee_id único
            $employeeData['employee_id'] = $this->generateEmployeeId();
            Log::info("Generado employee_id: " . $employeeData['employee_id']);
            
            try {
                $newEmployee = TempEmployee::create($employeeData);
                Log::info("Nuevo empleado creado con ID: " . $newEmployee->id);
                return 'created';
            } catch (\Exception $e) {
                Log::error("Error al crear empleado: " . $e->getMessage());
                Log::error("Datos que causaron el error: " . json_encode($employeeData));
                return 'skipped';
            }
        }
    }

    /**
     * Generar ID único de empleado
     */
    private function generateEmployeeId()
    {
        $lastEmployee = TempEmployee::whereRaw('employee_id REGEXP "^EMP[0-9]+$"')
                                   ->orderByRaw('CAST(SUBSTRING(employee_id, 4) AS UNSIGNED) DESC')
                                   ->first();

        if ($lastEmployee && preg_match('/^EMP(\d+)$/', $lastEmployee->employee_id, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'EMP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Mostrar detalles de un empleado
     */
    public function show(TempEmployee $employee)
    {
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Exportar empleados a CSV
     */
    public function export(Request $request)
    {
        $query = TempEmployee::query();

        // Aplicar los mismos filtros que en index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department')) {
            $query->byDepartment($request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('access_filter')) {
            if ($request->access_filter === 'with_access') {
                $query->withSystemAccess();
            } elseif ($request->access_filter === 'without_access') {
                $query->withoutSystemAccess();
            }
        }

        $employees = $query->orderBy('first_name')->orderBy('last_name')->get();

        $filename = 'empleados_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            
            // Escribir BOM para UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            // Escribir encabezados
            fputcsv($file, [
                'ID Empleado',
                'Nombre Completo',
                'Email',
                'Teléfono',
                'Extensión',
                'Posición',
                'Departamento',
                'Ubicación',
                'Email Supervisor',
                'Fecha Contratación',
                'Estado',
                'Tiene Acceso Sistema',
                'Fecha Importación'
            ]);
            
            // Escribir datos
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_id,
                    $employee->full_name,
                    $employee->email,
                    $employee->phone,
                    $employee->extension,
                    $employee->position,
                    $employee->department,
                    $employee->location,
                    $employee->manager_email,
                    $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '',
                    $employee->status,
                    $employee->hasSystemAccess() ? 'Sí' : 'No',
                    $employee->data_imported_at ? $employee->data_imported_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Descargar plantilla CSV
     */
    public function downloadTemplate()
    {
        $filename = 'plantilla_empleados.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Escribir BOM para UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            // Escribir encabezados en formato que el sistema espera
            fputcsv($file, [
                'NOMBRE COMPLETO',
                'ÁREA', 
                'DEPARTAMENTO',
                'CORREO'
            ]);
            
            // Escribir filas de ejemplo
            fputcsv($file, [
                'Juan Pérez García',
                'Sistemas',
                'Tecnología de la Información',
                'juan.perez@empresa.com'
            ]);
            
            fputcsv($file, [
                'María González López',
                'Recursos Humanos',
                'Administración',
                'maria.gonzalez@empresa.com'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
