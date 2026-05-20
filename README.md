# Explorador IA

Plataforma corporativa de inteligencia artificial desarrollada en **Laravel 12** que centraliza múltiples herramientas basadas en IA para empleados: chat inteligente, búsqueda de documentos, noticias personalizadas, recomendaciones técnicas y soporte automatizado.

---

## Tabla de Contenidos

- [Descripción General](#descripción-general)
- [Stack Tecnológico](#stack-tecnológico)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Módulos](#módulos)
  - [Chat con IA](#chat-con-ia)
  - [Buscador de Documentos Corporativos](#buscador-de-documentos-corporativos)
  - [Noticias Corporativas](#noticias-corporativas)
  - [Recomendaciones](#recomendaciones)
  - [Soporte Técnico](#soporte-técnico)
  - [Chat Corporativo Flotante](#chat-corporativo-flotante)
  - [Panel Administrativo](#panel-administrativo)
- [Rutas Principales](#rutas-principales)
- [API REST](#api-rest)
- [Automatización y Cronjobs](#automatización-y-cronjobs)
- [Roles y Permisos](#roles-y-permisos)
- [Estructura del Proyecto](#estructura-del-proyecto)

---

## Descripción General

**Explorador IA** es una aplicación web corporativa que integra diversas capacidades de inteligencia artificial en una sola plataforma. Permite a los empleados acceder a noticias personalizadas según su rol, recibir recomendaciones técnicas especializadas, consultar documentos corporativos mediante bots de IA, obtener soporte técnico automatizado y chatear con agentes configurables basados en OpenAI.

La autenticación soporta tanto credenciales tradicionales como inicio de sesión con **Google OAuth**.

---

## Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend reactivo | Livewire 3 |
| UI / CSS | Tailwind CSS + Vite |
| IA / LLM | OpenAI API, Ollama (local) |
| Autenticación | Laravel Breeze + Google OAuth (Socialite) |
| Roles y permisos | Laratrust |
| Base de datos | MySQL / MariaDB |
| Scraping HTTP | Guzzle HTTP |
| Exportaciones | Maatwebsite Excel, PhpSpreadsheet, PhpWord |
| Parseo de PDF | smalot/pdfparser |
| Testing | PestPHP |

---

## Requisitos

- PHP >= 8.2
- Composer
- Node.js >= 18 y npm
- MySQL / MariaDB (XAMPP recomendado en desarrollo)
- Clave de API de OpenAI
- (Opcional) Ollama corriendo localmente para el bot simple de documentos

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd Explorador-IA

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Copiar variables de entorno
cp .env.example .env

# 5. Generar clave de aplicación
php artisan key:generate

# 6. Ejecutar migraciones y seeders
php artisan migrate --seed

# 7. Crear enlace de almacenamiento
php artisan storage:link

# 8. Compilar assets
npm run build
```

---

## Configuración

Edita el archivo `.env` con los valores correspondientes a tu entorno:

```env
# Aplicación
APP_NAME="Explorador IA"
APP_URL=http://localhost

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=explorador_ia
DB_USERNAME=root
DB_PASSWORD=

# OpenAI
OPENAI_API_KEY=sk-...

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# Buscador de Documentos (API externa)
DOCUMENT_BOT_API_URL=https://bots.tech-energy.lat
DOCUMENT_BOT_TIMEOUT=60
```

---

## Módulos

### Chat con IA

Interfaz de chat interactiva construida con **Livewire** que permite a los empleados conversar con agentes de IA configurables. Los chats se organizan en grupos y se almacenan para historial y auditoría.

- Grupos de chat con configuración de agente por grupo
- Configuración de roles y modelos de OpenAI por usuario
- Historial completo de conversaciones en base de datos

**Rutas:**
```
GET  /chat                      → Vista principal del chat
GET  /agent-config              → Configuración de agentes
```

---

### Buscador de Documentos Corporativos

Sistema de búsqueda inteligente sobre documentos corporativos con dos modos de operación:

#### Bot Simple (Ollama — Local)
- Consultas generales a documentos
- Análisis de documento específico
- Listado y documentos recientes

#### Bot Avanzado (OpenAI — Cloud)
- Consulta rápida (3 chunks, modelo rápido)
- Razonamiento profundo (hasta 20 chunks, análisis detallado)
- Búsqueda semántica sin generación de texto
- Estadísticas del sistema
- Reindexación de documentos (solo administradores)

**Rutas:**
```
GET  /document-bot                           → Vista principal
GET  /document-bot/health                    → Health check combinado
POST /document-bot/simple/query              → Consulta simple
POST /document-bot/simple/analyze-document   → Analizar documento
GET  /document-bot/simple/documents          → Listar documentos
GET  /document-bot/advanced/quick-query      → Consulta rápida
POST /document-bot/advanced/deep-reasoning   → Razonamiento profundo
POST /document-bot/advanced/semantic-search  → Búsqueda semántica
GET  /document-bot/advanced/stats            → Estadísticas
POST /document-bot/advanced/reindex          → Reindexar (admin)
```

> Documentación detallada: [DOCUMENT_BOT_MODULE_README.md](DOCUMENT_BOT_MODULE_README.md)

---

### Noticias Corporativas

Sistema de scraping automático de noticias de medios mexicanos especializados, con categorización automática según el rol del empleado.

**Fuentes:**
- El Universal (economía y negocios)
- El Financiero (economía y empresas)
- Milenio (negocios y economía)

**Categorías de noticias:** Administración y Finanzas, Contratos, Dirección General, Ingeniería y Manufactura, Operaciones, QHSE, Servicios Generales, Energía y Tecnología, Economía Nacional, Industria y Negocios.

**Comandos:**
```bash
php artisan news:scrape                  # Scraping de todas las fuentes
php artisan news:scrape --stats          # Ver estadísticas
php artisan news:scrape --clean-old      # Limpiar noticias con 30+ días
php artisan db:seed --class=NewsTypeSeeder
```

**Rutas:**
```
GET  /news   → Vista de noticias personalizadas
POST /news   → Actualizar preferencias de noticias
```

> Documentación detallada: [NEWS_SCRAPING_README.md](NEWS_SCRAPING_README.md)

---

### Recomendaciones

Sistema de scraping de recomendaciones técnicas y de negocio desde más de 60 fuentes internacionales especializadas, categorizadas automáticamente por departamento.

**Fuentes principales:** McKinsey, Harvard Business Review, MIT Technology Review, Forbes, Deloitte, BCG, PwC, EY, KPMG, Accenture, Reuters, Bloomberg, Financial Times, y 48+ sitios especializados en QHSE, Operaciones, Ingeniería, RRHH y Legal.

**Estrategias de scraping:**
| Estrategia | Fuentes | Tasa de éxito | Tiempo estimado |
|---|---|---|---|
| `working_only` | 13 verificadas | 92.3% | 45-60s |
| `extended` | 48+ especializadas | 92.9% | 80-90s |
| `mixed` | Combinada | 90.5% | 70-80s |
| `priority` | Extendidas primero | 91.8% | 100-120s |

**Comandos:**
```bash
php artisan recommendations:scrape --strategy=extended
php artisan recommendations:scrape --strategy=working_only
php artisan recommendations:scrape --stats
php artisan recommendations:scrape --clean-old
php artisan db:seed --class=RecommendationTypeSeeder
```

**Rutas:**
```
GET  /recommendations   → Vista de recomendaciones personalizadas
POST /recommendations   → Actualizar preferencias
```

> Documentación detallada: [RECOMMENDATIONS_README.md](RECOMMENDATIONS_README.md)

---

### Soporte Técnico

Bot conversacional de soporte técnico de primer nivel orientado a usuarios de oficina. Guía paso a paso sin tecnicismos, con escalación automática a IT cuando es necesario.

**Categorías soportadas:**
1. **Computadora** — rendimiento, encendido, pantalla, congelamiento
2. **Internet** — WiFi, velocidad, navegación
3. **Correo electrónico** — Gmail, Outlook, acceso
4. **Impresora** — no imprime, papel atascado, tinta
5. **Software** — Microsoft Office, Google Workspace, errores de apps
6. **Acceso** — contraseñas, cuentas bloqueadas, permisos

**Características:**
- 29 soluciones específicas para problemas comunes
- Generación automática de tickets de soporte (formato `IT-YYYYMMDD-####`)
- Logging completo de conversaciones con métricas de efectividad
- Dashboard de analytics con gráficos (Chart.js)

**Rutas:**
```
GET  /tech-support             → Interfaz de chat de soporte
GET  /tech-support/dashboard   → Dashboard de analytics
POST /tech-support/interact    → Endpoint de interacción del bot
```

**Administración:**
```
GET    /admin/tech-support                          → Gestión del módulo
POST   /admin/tech-support/categories               → Crear categoría
PUT    /admin/tech-support/categories/{id}          → Editar categoría
DELETE /admin/tech-support/categories/{id}          → Eliminar categoría
POST   /admin/tech-support/problems                 → Crear problema
PUT    /admin/tech-support/problems/{id}            → Editar problema
DELETE /admin/tech-support/problems/{id}            → Eliminar problema
```

> Documentación detallada: [TECH_SUPPORT_MODULE_SUMMARY.md](TECH_SUPPORT_MODULE_SUMMARY.md)

---

### Chat Corporativo Flotante

Widget de chat flotante disponible en toda la aplicación que permite consultar información corporativa en tiempo real: directorio de empleados, documentos, ubicaciones y conocimiento corporativo.

**Rutas:**
```
POST /corporate-chat/message          → Enviar mensaje al chatbot
GET  /corporate-chat/employees/search → Buscar empleados
GET  /corporate-chat/employees/tags   → Tags de empleados
GET  /corporate-chat/documents/tags   → Tags de documentos
GET  /corporate-chat/locations/search → Buscar ubicaciones
GET  /corporate-chat/documents/search → Buscar documentos
```

---

### Panel Administrativo

Dashboard centralizado para administradores con métricas del sistema, gestión de usuarios, empleados y estadísticas de uso de todos los módulos.

**Rutas:**
```
GET /admin/stats/dashboard   → Dashboard principal
GET /admin/stats/users       → Estadísticas de usuarios
GET /admin/stats/chats       → Estadísticas de chats
GET /admin/stats/modules     → Uso de módulos
GET /admin/stats/errors      → Registro de errores
GET /admin/stats/export      → Exportar datos

GET    /admin/employees            → Listado de empleados
GET    /admin/employees/import     → Importar empleados (Excel)
POST   /admin/employees/import     → Procesar importación
GET    /admin/employees/template   → Descargar plantilla
GET    /admin/employees/export     → Exportar empleados
DELETE /admin/employees/bulk/delete → Eliminar en lote
```

---

## Rutas Principales

| Ruta | Descripción |
|------|-------------|
| `/` | Dashboard principal (requiere autenticación) |
| `/chat` | Chat con agentes de IA |
| `/news` | Noticias personalizadas por rol |
| `/recommendations` | Recomendaciones técnicas por departamento |
| `/document-bot` | Buscador de documentos corporativos |
| `/tech-support` | Soporte técnico automatizado |
| `/agent-config` | Configuración de agentes de IA |
| `/profile` | Perfil del usuario |
| `/auth/google` | Inicio de sesión con Google |

---

## API REST

### Noticias

```
GET  /api/news                      → Listar noticias (con filtros)
GET  /api/news/{id}                 → Obtener noticia
GET  /api/news/search               → Buscar noticias
GET  /api/news/types                → Tipos de noticias
GET  /api/news/type/{id}/recent     → Noticias recientes por tipo
POST /api/news/scrape               → Ejecutar scraping (autenticado)
GET  /api/news/scraping/stats       → Estadísticas de scraping
GET  /api/news/scraping/health      → Estado del scraper
```

### Recomendaciones

```
POST /api/recommendations/generate/all          → Generar para todos los usuarios
POST /api/recommendations/generate/user/{id}    → Generar para un usuario
POST /api/recommendations/generate/role/{name}  → Generar por rol
POST /api/recommendations/generate/news/{id}    → Generar desde noticia
GET  /api/recommendations/user/{id}             → Recomendaciones de usuario
GET  /api/recommendations/recent                → Recomendaciones recientes
GET  /api/recommendations/by-type/{id}          → Por tipo
GET  /api/recommendations/stats                 → Estadísticas
GET  /api/recommendations/health                → Estado del servicio
```

### Sistema

```
GET /api/system/health   → Estado general de todos los servicios
```

---

## Automatización y Cronjobs

El scraping de noticias se ejecuta automáticamente en Windows mediante tareas programadas:

| Horario | Tarea |
|---------|-------|
| 7:00 AM diario | Scraping matutino de noticias |
| 12:30 PM diario | Scraping del mediodía |
| 6:00 PM diario | Scraping vespertino |
| Domingos 2:00 AM | Limpieza de noticias con +30 días |

**Scripts de configuración (ejecutar como Administrador):**
```bash
# Configurar tareas automáticas
cronjobs\setup-news-scraping-tasks.bat

# Remover tareas automáticas
cronjobs\remove-news-scraping-tasks.bat
```

---

## Roles y Permisos

La aplicación usa **Laratrust** para gestión de roles. Los roles están alineados con los departamentos corporativos:

- Administración y Finanzas
- Contratos
- Dirección General
- Ingeniería y Manufactura
- Operaciones
- QHSE
- Servicios Generales y Almacén
- Admin (acceso completo)

Los contenidos de noticias, recomendaciones y configuraciones de agente de IA se filtran automáticamente según el rol del usuario autenticado.

---

## Estructura del Proyecto

```
app/
├── Console/Commands/        # Comandos Artisan (scraping, etc.)
├── Http/
│   ├── Controllers/         # Controladores web y API
│   └── Requests/            # Form Requests
├── Livewire/                # Componentes Livewire (Chat, etc.)
├── Models/                  # Modelos Eloquent
├── Services/                # Servicios (DocumentBotService, etc.)
└── Exports/                 # Exportaciones Excel

resources/views/
├── chat/                    # Vistas del chat con IA
├── document-bot/            # Vistas del buscador de documentos
├── news/                    # Vistas de noticias
├── recommendations/         # Vistas de recomendaciones
└── tech-support/            # Vistas del soporte técnico

routes/
├── web.php                  # Rutas web (autenticadas)
├── api.php                  # Rutas API REST
└── auth.php                 # Rutas de autenticación

cronjobs/                    # Scripts BAT para tareas programadas en Windows
database/
├── migrations/              # Migraciones de base de datos
└── seeders/                 # Seeders (tipos de noticias, roles, etc.)
```

---

## Licencia

Este proyecto es de uso corporativo interno. Todos los derechos reservados.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
