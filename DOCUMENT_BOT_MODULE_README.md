# 📚 Módulo de Buscador de Documentos Corporativos

## 🎯 Descripción

Este módulo integra la API externa de bots de documentos para proporcionar búsqueda inteligente y consultas con IA sobre documentos corporativos.

## 🚀 Características

### Bot Simple
- ✅ Consultas generales a documentos
- ✅ Análisis de documentos específicos
- ✅ Listado de todos los documentos
- ✅ Documentos recientes

### Bot Avanzado
- ✅ Consulta rápida (3 chunks, modelo rápido)
- ✅ Razonamiento profundo (hasta 20 chunks, análisis detallado)
- ✅ Búsqueda semántica sin generación
- ✅ Estadísticas del sistema
- ✅ Reindexación de documentos (admin)

## 📁 Estructura de Archivos

```
app/
├── Services/
│   └── DocumentBotService.php          # Servicio para comunicación con API
├── Http/
│   └── Controllers/
│       └── DocumentBotController.php   # Controlador principal

resources/
└── views/
    └── document-bot/
        └── index.blade.php              # Vista principal

routes/
└── web.php                              # Rutas del módulo
```

## 🔧 Configuración

### Variables de Entorno (.env)

```env
# Document Bot API Configuration
DOCUMENT_BOT_API_URL=https://bots.tech-energy.lat
DOCUMENT_BOT_TIMEOUT=60
```

Para desarrollo local, puedes cambiar la URL:
```env
DOCUMENT_BOT_API_URL=http://localhost:8000
```

## 🌐 Rutas Disponibles

### Rutas Web

| Ruta | Método | Descripción |
|------|--------|-------------|
| `/document-bot` | GET | Vista principal del buscador |
| `/document-bot/health` | GET | Health check del sistema |
| `/document-bot/query` | POST | Consulta simple |
| `/document-bot/analyze-document` | POST | Analizar documento específico |
| `/document-bot/documents` | GET | Listar todos los documentos |
| `/document-bot/recent-documents` | GET | Documentos recientes |
| `/document-bot/quick-query` | POST | Consulta rápida (bot avanzado) |
| `/document-bot/deep-reasoning` | POST | Razonamiento profundo |
| `/document-bot/semantic-search` | POST | Búsqueda semántica |
| `/document-bot/stats` | GET | Estadísticas del sistema |
| `/document-bot/reindex` | POST | Reindexar documentos (admin) |

## 📝 Ejemplos de Uso

### 1. Consulta Simple

```javascript
// POST /document-bot/query
{
    "pregunta": "¿Qué dice el código de ética sobre integridad?"
}
```

**Respuesta:**
```javascript
{
    "success": true,
    "data": {
        "respuesta": "El código de ética define la integridad como...",
        "tiempo_respuesta": 2.5
    }
}
```

### 2. Análisis de Documento

```javascript
// POST /document-bot/analyze-document
{
    "documento_id": 1,
    "pregunta": "¿Cuáles son los puntos clave?"  // Opcional
}
```

### 3. Consulta Rápida (Bot Avanzado)

```javascript
// POST /document-bot/quick-query
{
    "pregunta": "¿Cuál es el horario de trabajo?",
    "filtros": {
        "created": "2026"
    }
}
```

**Respuesta:**
```javascript
{
    "success": true,
    "data": {
        "respuesta": "El horario es de 8:00 AM a 5:00 PM...",
        "estadisticas": {
            "tokens_entrada": 150,
            "tokens_salida": 80,
            "costo_usd": 0.00015
        },
        "tiempo_respuesta": 3.2
    }
}
```

### 4. Razonamiento Profundo

```javascript
// POST /document-bot/deep-reasoning
{
    "pregunta": "Analiza las políticas de vacaciones",
    "k": 10,           // Número de chunks (1-20)
    "filtros": null
}
```

### 5. Búsqueda Semántica

```javascript
// POST /document-bot/semantic-search
{
    "query": "políticas de seguridad",
    "k": 5,
    "filtros": null
}
```

**Respuesta:**
```javascript
{
    "success": true,
    "data": {
        "resultados": [
            {
                "doc_id": "3",
                "title": "Código de Ética",
                "chunk_index": 5,
                "total_chunks": 20,
                "created": "2026-03-10",
                "preview": "La integridad se define como...",
                "score": 0.92
            }
        ],
        "total": 1,
        "tiempo_respuesta": 0.8
    }
}
```

## 🎨 Interfaz de Usuario

La vista principal (`/document-bot`) incluye:

1. **Panel de Documentos** (izquierda)
   - Lista de todos los documentos
   - Documentos recientes
   - Búsqueda semántica

2. **Panel de Consultas** (centro/derecha)
   - Selector de tipo de consulta:
     - 🔵 Simple: Consulta básica rápida
     - 🟢 Rápida: Con IA - 3 chunks
     - 🟣 Profunda: Análisis detallado - hasta 20 chunks
   - Formulario de consulta
   - Visualización de resultados
   - Estadísticas (tokens, costos)

3. **Controles Superiores**
   - Health Check: Verificar estado del sistema
   - Estadísticas: Ver métricas globales

## 🔐 Permisos

- **Usuarios autenticados**: Acceso completo a consultas y búsquedas
- **Administradores**: Acceso adicional a reindexación de documentos

## 🛠️ Desarrollo

### Agregar Nuevos Endpoints

1. Agregar método en `DocumentBotService.php`:
```php
public function nuevoMetodo($parametros): array
{
    try {
        $response = Http::timeout($this->timeout)
            ->post("{$this->baseUrl}/api/v1/nuevo-endpoint", $parametros);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        return ['success' => false, 'error' => 'Error'];
    } catch (Exception $e) {
        Log::error('Error', ['error' => $e->getMessage()]);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
```

2. Agregar controlador en `DocumentBotController.php`:
```php
public function nuevoMetodo(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'campo' => 'required|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $result = $this->documentBotService->nuevoMetodo($request->all());
    return response()->json($result);
}
```

3. Agregar ruta en `routes/web.php`:
```php
Route::post('/nuevo-endpoint', [DocumentBotController::class, 'nuevoMetodo'])
    ->name('nuevo-endpoint');
```

## 📊 Monitoreo y Logs

Todos los errores y operaciones se registran en:
- Laravel Log: `storage/logs/laravel.log`
- Categoría: `'Error en [operación]'`

Ejemplo de log:
```php
Log::error('Error en consulta simple', [
    'pregunta' => $pregunta,
    'error' => $e->getMessage()
]);
```

## 🧪 Testing

Para probar el módulo:

1. Verificar health check:
```bash
curl http://localhost:8000/document-bot/health
```

2. Hacer una consulta simple:
```bash
curl -X POST http://localhost:8000/document-bot/query \
  -H "Content-Type: application/json" \
  -d '{"pregunta": "¿Qué documentos hay disponibles?"}'
```

## 🔄 Próximas Mejoras

- [ ] Caché de respuestas frecuentes
- [ ] Historial de consultas por usuario
- [ ] Exportación de resultados (PDF, Word)
- [ ] Filtros avanzados por fecha, tipo, tags
- [ ] Comparación de documentos
- [ ] Resúmenes automáticos de documentos nuevos
- [ ] Notificaciones de documentos relevantes
- [ ] Integración con sistema de permisos por documento

## 📚 Documentación de la API Externa

Ver archivo adjunto: `API_DOCUMENTATION.md`

## 💡 Tips

1. **Tipo de consulta adecuado**:
   - Simple: Preguntas rápidas, búsquedas básicas
   - Rápida: Consultas que requieren comprensión de contexto
   - Profunda: Análisis complejos, comparaciones, síntesis

2. **Optimización de costos**:
   - Usar "Simple" cuando no se necesite IA
   - Usar "Rápida" para consultas moderadas
   - Reservar "Profunda" para análisis críticos

3. **Búsqueda semántica**:
   - Ideal para encontrar documentos relevantes sin generar respuesta
   - Más rápido y económico que consultas con IA
   - Útil para exploración inicial

## 🐛 Solución de Problemas

### Error: "No autorizado"
- Verificar que el usuario esté autenticado
- Para reindexación, verificar rol de administrador

### Error: "Error de conexión"
- Verificar que la API externa esté disponible
- Revisar URL en `.env`
- Hacer health check

### Error: "Timeout"
- Aumentar `DOCUMENT_BOT_TIMEOUT` en `.env`
- Verificar red y firewall

## 📞 Soporte

Para problemas o preguntas:
- Revisar logs en `storage/logs/laravel.log`
- Verificar documentación de la API externa
- Contactar al equipo de desarrollo

---

**Última actualización:** 17 de marzo de 2026  
**Versión:** 1.0.0
