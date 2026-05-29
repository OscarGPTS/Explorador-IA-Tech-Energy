# Plan: Administración de Recomendaciones y Noticias

Documento de trabajo para el módulo administrativo de **Recomendaciones** (`/recommendations`)
y **Noticias** (`/news`), con scraping configurable (RSS + HTML), ejecución en cola,
disparo manual bajo demanda y panel de estado del servicio.

> Estado: ✅ IMPLEMENTADO Y VALIDADO. Ver "Resultado de la implementación" al final.

---

## Decisiones acordadas

| Tema | Decisión |
|------|----------|
| Permisos | Permisos granulares Laratrust: `manage-recommendations`, `manage-news`. El rol `admin` los tiene todos; se pueden asignar a usuarios/roles designados. |
| Obtención de noticias | **RSS primero** (estable, no se bloquea), con **fallback a scraping HTML** mediante selectores configurables. |
| Ejecución | **Cola + schedule automático** (`QUEUE_CONNECTION=database`) **+ botón manual** "Scrapear ahora" para validar bajo demanda. |
| Notificación de estado | **Panel admin en vivo**: última ejecución, # de items, errores y badge OK/Falla por fuente. |

---

## Anti-bloqueo / robustez del scraping

- RSS como fuente primaria (estructurado, baja probabilidad de bloqueo).
- Ejecución **siempre en cola**, nunca en el request del usuario.
- Delay aleatorio entre requests + User-Agent rotativo + `timeout`.
- Límite de items por corrida y de-duplicación por `external_link`.
- Reúso de `RecommendationRateLimit` en endpoints de disparo manual.

---

## Fase 0 — Permisos (Laratrust)

- [ ] `database/seeders/PermissionRoleSeeder.php`: crea permisos `manage-recommendations`,
      `manage-news` y el rol `admin` (hoy se usa pero no se siembra) con todos los permisos.
- [ ] Registrar el seeder en `DatabaseSeeder` y ejecutable con `--class`.
- [ ] Gating: middleware `permission:manage-recommendations` / `permission:manage-news`
      en rutas admin y directivas `@permission` en Blade.
- [ ] Comando `user:grant-permission {email} {permission}` para asignar a la persona designada.

## Fase 1 — Recomendaciones (CRUD admin + modal)

- [ ] `RecommendationsController@index` carga de BD agrupado por `recommendation_type` (tabs por área).
- [ ] Migrar el item hardcodeado (Congreso del Petróleo / Marketing) a un seeder.
- [ ] `RecommendationAdminController`: `store/update/destroy` + subida de imagen a `storage/recommendations`.
- [ ] Modal CRUD: `title, description, content, recommendation_type_id, sub_area, imagen|image_url, external_link`.
- [ ] Rutas `admin/recommendations/*` con `permission:manage-recommendations`.

## Fase 2 — Noticias (CRUD admin + carga manual + panel de fuentes)

- [ ] `NewsAdminController`: CRUD de noticias (carga manual) con modal.
- [ ] Migración `scraping_sources` (fuentes administrables).
- [ ] Seeder que migra las fuentes hoy hardcodeadas en los servicios.
- [ ] Panel CRUD de fuentes + selector de área/tipo destino.
- [ ] Refactor `NewsScrapingService` / `RecommendationScrapingService` para leer fuentes de BD + parseo RSS.

## Fase 3 — Cola, schedule, botón manual y estado

- [ ] `ScrapeNewsJob` y `ScrapeRecommendationsJob` (`ShouldQueue`).
- [ ] Schedule en `routes/console.php` (cada 6 h) — requiere `queue:work` + `schedule:work`.
- [ ] Botón "Scrapear ahora" (global y por fuente) que encola Job.
- [ ] Panel de estado en vivo desde columnas `last_*` de `scraping_sources`.

## Verificación

- [ ] `php artisan migrate --seed`.
- [ ] Render dinámico de `/recommendations`.
- [ ] CRUD con y sin permiso (403).
- [ ] Carga manual de noticia + alta de fuente RSS.
- [ ] Disparo manual de Job y reflejo en panel de estado.

---

## Esquema `scraping_sources`

```
id, name, module (news|recommendations), feed_type (rss|html), url,
type_id (FK a news_type/recommendation_type según módulo),
sub_area (nullable), selectors (json, solo HTML), is_active (bool),
last_run_at, last_status (ok|error|never), last_items (int), last_error (text),
timestamps
```

## Operación (notas para el servidor)

- Worker de cola: `php artisan queue:work --queue=default`
- Scheduler: `php artisan schedule:work` (o cron a `schedule:run` cada minuto)
- Scraping manual: botón en panel admin → encola Job → refresca panel de estado.

---

## Resultado de la implementación

### Archivos creados
- `database/seeders/PermissionRoleSeeder.php` — permisos `manage-recommendations`, `manage-news`, `manage-scraping` + rol `admin`.
- `app/Console/Commands/GrantPermission.php` — `user:grant-permission {email} {permiso|admin} [--revoke]`.
- `database/seeders/MarketingRecommendationSeeder.php` — migra el item hardcodeado (Congreso del Petróleo).
- `app/Http/Controllers/Admin/RecommendationAdminController.php` — CRUD recomendaciones.
- `app/Http/Controllers/Admin/NewsAdminController.php` — CRUD noticias + fuentes + scrapeNow.
- `database/migrations/..._create_scraping_sources_table.php` + `app/Models/ScrapingSource.php`.
- `database/migrations/..._widen_link_columns.php` — `external_link`/`image_url` a TEXT (URLs largas de RSS).
- `app/Services/SourceScraperService.php` — motor RSS + HTML genérico, dedupe, estado por fuente.
- `app/Jobs/RunScrapingJob.php` + `app/Console/Commands/RunScraping.php` — `scraping:run {module} [--sync]`.
- `database/seeders/ScrapingSourceSeeder.php` — 15 fuentes Google News RSS por área.
- `resources/views/news/admin.blade.php` — panel admin (estado + fuentes + noticias).

### Archivos modificados
- `config/laratrust.php` — `teams.enabled => false` (la tabla no tenía `team_id`).
- `routes/web.php` — rutas `admin/recommendations/*` y `admin/news/*` con `permission:`.
- `routes/console.php` — schedule: noticias cada 6 h, recomendaciones diario 06:30.
- `app/Http/Controllers/RecommendationsController.php` — index dinámico desde BD.
- `resources/views/recommendations/index.blade.php` — dinámico + modales CRUD.
- `resources/views/news/index.blade.php` — botón "Administrar" (`@permission`).
- `database/seeders/DatabaseSeeder.php` — llama `PermissionRoleSeeder`.

### Validación realizada
- Seeders ejecutados; rol admin asignado a `ochavez@gptservices.com`.
- `scraping:run news --sync` → 10 fuentes, 98 items, 0 errores.
- `scraping:run recommendations --sync` → 5 fuentes, 40 items, 0 errores.
- Flujo en cola: `scraping:run news` (encola) + `queue:work --once` → procesado OK.
- Estado final: 41 recomendaciones, 102 noticias, 15 fuentes, 3 permisos.

### Cómo asignar a la "persona designada"
```
php artisan user:grant-permission persona@empresa.com manage-news
php artisan user:grant-permission persona@empresa.com manage-recommendations
```

### Addendum — Reestructura Oil & Gas (recomendaciones)
GPT Services es una empresa de **Oil & Gas**. Para diferenciar **recomendaciones** de **news**
(que son noticias generales del sector), las recomendaciones se enfocan en **desarrollo
profesional**: congresos, cursos, capacitaciones y certificaciones.

- Categorías (tabs): **Congresos y Expos · Cursos y Capacitación · Certificaciones y Normas ·
  Desarrollo Profesional** (todas dentro del sector Oil & Gas).
- `database/seeders/OilGasRecommendationSeeder.php` crea las categorías, reapunta el Congreso
  del Petróleo a "Eventos y Congresos", borra las recomendaciones scrapeadas genéricas,
  recrea las fuentes RSS enfocadas en Oil & Gas y limpia los tipos de departamento sin uso.
- La vista de recomendaciones ahora usa un **grid único filtrable** con tab **"Todas"** +
  un tab por categoría (con contador), y encabezado dinámico por categoría.
- `ScrapingSourceSeeder` ya solo siembra fuentes de **noticias**; las de recomendaciones
  las gobierna `OilGasRecommendationSeeder`.
- Resultado: 42 recomendaciones Oil & Gas distribuidas en las 5 categorías, 0 errores.

### Addendum — Reestructura Oil & Gas (noticias)
Mismo enfoque del sector aplicado a `/news`, con **referencia a medios reales de Oil & Gas**:

- Nuevas categorías: **Mercado Energético · Pemex e Hidrocarburos · Exploración y Producción ·
  Tecnología y Energía · Internacional Oil & Gas**.
- `database/seeders/OilGasNewsSeeder.php` crea las categorías, borra noticias scrapeadas
  genéricas, limpia tipos sin uso y siembra fuentes RSS reales del sector:
  **Energía a Debate, Global Energy, Petroquimex** (México) y **OilPrice, World Oil,
  Rigzone, Offshore Technology** (internacionales), más consultas Google News del sector.
- `NewsController@index` ahora tiene **fallback**: si el usuario no ha personalizado su feed,
  muestra todas las categorías con noticias (antes quedaba vacío).
- `ScrapingSourceSeeder` ahora solo orquesta `OilGasNewsSeeder` + `OilGasRecommendationSeeder`.
- Resultado: 108 noticias Oil & Gas en 5 categorías desde 11 fuentes, 0 errores.

### Estrategia anti-bloqueo aplicada
Se usa **Google News RSS** como fuente por defecto (estructurado, estable, no se bloquea
como el scraping HTML directo a los periódicos). El admin puede añadir feeds RSS propios
o fuentes HTML con selectores XPath. Todo corre en cola con delay entre requests,
User-Agent rotativo y dedupe por `external_link`.
