# Sistema de Web Scraping de Noticias

Este sistema automatiza la recolección de noticias de sitios web mexicanos para mantener actualizada la base de datos de noticias del Explorador IA.

## 🌐 Fuentes de Noticias

El sistema extrae noticias de los siguientes sitios:

### 📰 El Universal (eluniversal.com.mx)
- **Sección Economía**: `/cartera`
- **Sección Negocios**: `/cartera/negocios`

### 💼 El Financiero (elfinanciero.com.mx)
- **Sección Economía**: `/economia`
- **Sección Empresas**: `/empresas`

### 📈 Milenio (milenio.com)
- **Sección Negocios**: `/negocios`
- **Sección Economía**: `/temas/economia`

## 🏷️ Categorización Automática

Las noticias se categorizan automáticamente según los grupos de roles:

| Tipo de Noticia | Descripción |
|---|---|
| **Administración y Finanzas** | Noticias sobre administración, recursos humanos, finanzas y compras |
| **Contratos** | Noticias sobre proyectos, contratos comerciales y gestión contractual |
| **Dirección General** | Noticias corporativas, estrategia empresarial y marketing |
| **Ingeniería y Manufactura** | Noticias sobre ingeniería, manufactura y procesos productivos |
| **Operaciones** | Noticias sobre operaciones, soldadura, mantenimiento y procesos técnicos |
| **QHSE** | Noticias sobre calidad, seguridad, salud ocupacional y medio ambiente |
| **Servicios Generales y Almacén** | Noticias sobre logística, IT, almacén y servicios generales |
| **Energía y Tecnología** | Noticias sobre sector energético, tecnología e innovación |
| **Economía Nacional** | Noticias económicas, financieras y del sector empresarial mexicano |
| **Industria y Negocios** | Noticias de la industria, negocios y mercados |

## 🚀 Comandos Disponibles

### Ejecutar Scraping
```bash
# Scraping de todas las fuentes
php artisan news:scrape

# Ver estadísticas sin hacer scraping
php artisan news:scrape --stats

# Limpiar noticias antiguas (30+ días)
php artisan news:scrape --clean-old
```

### Crear Tipos de Noticias
```bash
# Ejecutar seeder para crear los tipos de noticias
php artisan db:seed --class=NewsTypeSeeder
```

## 🤖 Automatización

### Configuración de Tareas Programadas (Windows)

Para configurar el scraping automático en Windows:

1. **Configurar tareas** (ejecutar como Administrador):
   ```bash
   cronjobs\setup-news-scraping-tasks.bat
   ```

2. **Remover tareas**:
   ```bash
   cronjobs\remove-news-scraping-tasks.bat
   ```

### Programación Automática

- **Matutino**: 7:00 AM diario
- **Mediodía**: 12:30 PM diario  
- **Vespertino**: 6:00 PM diario
- **Limpieza**: Domingos 2:00 AM (elimina noticias +30 días)

## 📊 API Endpoints

### Noticias
```
GET /api/news                     - Obtener noticias con filtros
GET /api/news/{id}               - Obtener noticia específica
GET /api/news/search             - Buscar noticias
GET /api/news/types              - Obtener tipos de noticias
GET /api/news/type/{id}/recent   - Noticias recientes por tipo
```

### Scraping (Autenticado)
```
POST /api/news/scrape            - Ejecutar scraping manual
GET /api/news/scraping/stats     - Estadísticas de scraping
GET /api/news/scraping/health    - Estado del sistema
```

### Filtros Disponibles
- `news_type_id`: Filtrar por tipo de noticia
- `source`: Filtrar por fuente (eluniversal, elfinanciero, milenio)
- `is_scraped`: Solo noticias scrapeadas
- `date_from` / `date_to`: Rango de fechas
- `search`: Búsqueda en título y contenido

## 🔧 Configuración Técnica

### Dependencias
```json
{
  "guzzlehttp/guzzle": "^7.10"
}
```

### Estructura de Base de Datos

#### Tabla `news`
- `title`: Título de la noticia
- `content`: Contenido/resumen
- `external_link`: URL original
- `image_url`: URL de imagen
- `source`: Fuente (eluniversal, elfinanciero, milenio)
- `news_type_id`: Relación con tipo de noticia
- `is_scraped`: Booleano (scrapeada automáticamente)
- `scraped_at`: Timestamp del scraping

#### Tabla `news_type`
- `name`: Nombre del tipo
- `description`: Descripción del tipo

## 📋 Logs y Monitoreo

### Logs del Sistema
- **Info**: Artículos guardados exitosamente
- **Warning**: Tipos de noticia no encontrados, contenido excluido
- **Error**: Errores HTTP, problemas de guardado

### Métricas Importantes
- **Total Articles**: Artículos procesados
- **Successfully Saved**: Artículos guardados exitosamente
- **Success Rate**: Porcentaje de éxito
- **Duration**: Tiempo de ejecución

## 🛠️ Solución de Problemas

### Error: Column 'is_scraped' not found
```bash
php artisan migrate
```

### Sin noticias guardadas
1. Verificar que los tipos de noticias existan:
   ```bash
   php artisan db:seed --class=NewsTypeSeeder
   ```

2. Verificar el modelo News tenga los campos `fillable`

### Sitios web bloqueando requests
- El sistema usa User-Agent de navegador real
- Implementa delays entre requests
- Maneja errores de conexión automáticamente

## 🔄 Mantenimiento

### Limpieza Automática
- Se ejecuta automáticamente los domingos
- Elimina noticias de más de 30 días
- Mantiene la base de datos optimizada

### Monitoreo de Salud
```bash
# Verificar estado del sistema
curl http://localhost/api/news/scraping/health
```

## 🎯 Próximas Mejoras

- [ ] Soporte para más fuentes de noticias
- [ ] Análisis de sentimientos con IA
- [ ] Clasificación automática avanzada
- [ ] Notificaciones de noticias importantes
- [ ] Dashboard web para monitoreo

---

**Desarrollado para Explorador IA** | *Sistema de Gestión Inteligente de Contenido*