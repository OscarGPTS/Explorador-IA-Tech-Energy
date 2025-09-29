@echo off
REM Setup Windows Scheduled Tasks for News Scraping
REM Ejecutar como Administrador

echo ========================================
echo   CONFIGURANDO TAREAS DE NEWS SCRAPING
echo ========================================

set PROJECT_PATH=C:\xampp\htdocs\Explorador-IA
set SCRIPT_PATH=%PROJECT_PATH%\cronjobs\scrape-news.bat

echo Configurando tareas programadas para el scraping de noticias...
echo.

REM Verificar que el script existe
if not exist "%SCRIPT_PATH%" (
    echo ERROR: No se encontró el script de scraping en: %SCRIPT_PATH%
    pause
    exit /b 1
)

REM Crear tarea para scraping matutino (7:00 AM)
echo Creando tarea: Scraping de Noticias - Matutino
schtasks /create /tn "NewsIA_Scraping_Matutino" /tr "%SCRIPT_PATH%" /sc daily /st 07:00 /ru SYSTEM /f

REM Crear tarea para scraping de mediodía (12:30 PM)
echo Creando tarea: Scraping de Noticias - Mediodia
schtasks /create /tn "NewsIA_Scraping_Mediodia" /tr "%SCRIPT_PATH%" /sc daily /st 12:30 /ru SYSTEM /f

REM Crear tarea para scraping vespertino (6:00 PM)
echo Creando tarea: Scraping de Noticias - Vespertino
schtasks /create /tn "NewsIA_Scraping_Vespertino" /tr "%SCRIPT_PATH%" /sc daily /st 18:00 /ru SYSTEM /f

REM Crear tarea de limpieza semanal (Domingos 2:00 AM)
echo Creando tarea: Limpieza de Noticias Antiguas
schtasks /create /tn "NewsIA_Cleanup_Weekly" /tr "cmd /c cd /d \"%PROJECT_PATH%\" && php artisan news:scrape --clean-old" /sc weekly /d SUN /st 02:00 /ru SYSTEM /f

echo.
echo ✅ Tareas programadas creadas exitosamente:
echo    - NewsIA_Scraping_Matutino: Diario 7:00 AM
echo    - NewsIA_Scraping_Mediodia: Diario 12:30 PM
echo    - NewsIA_Scraping_Vespertino: Diario 6:00 PM
echo    - NewsIA_Cleanup_Weekly: Domingos 2:00 AM (limpieza)

echo.
echo Para verificar las tareas creadas ejecuta:
echo schtasks /query /tn "NewsIA_*"

echo.
echo Para ejecutar una tarea manualmente:
echo schtasks /run /tn "NewsIA_Scraping_Matutino"

pause