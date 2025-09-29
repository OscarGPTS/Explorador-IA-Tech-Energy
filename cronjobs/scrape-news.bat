@echo off
REM News Scraping Automation Script for Windows Task Scheduler
REM Este script ejecuta el web scraping de noticias mexicanas

echo ===================================
echo   NEWS SCRAPING AUTOMATION
echo ===================================
echo Starting at: %date% %time%

REM Cambiar al directorio del proyecto
cd /d "C:\xampp\htdocs\Explorador-IA"

REM Verificar que estamos en el directorio correcto
if not exist "artisan" (
    echo ERROR: No se encontró el archivo artisan. Verificar la ruta del proyecto.
    echo Current directory: %cd%
    pause
    exit /b 1
)

echo Directorio del proyecto: %cd%

REM Ejecutar el seeder de tipos de noticias (solo la primera vez)
echo.
echo Ejecutando seeder de tipos de noticias...
php artisan db:seed --class=NewsTypeSeeder

REM Ejecutar el scraping de noticias
echo.
echo Iniciando scraping de noticias...
php artisan news:scrape

REM Verificar el resultado
if %errorlevel% equ 0 (
    echo.
    echo ✅ Scraping de noticias completado exitosamente
) else (
    echo.
    echo ❌ Error durante el scraping de noticias
)

REM Mostrar estadísticas
echo.
echo Mostrando estadísticas...
php artisan news:scrape --stats

echo.
echo Script finalizado at: %date% %time%
echo ===================================

REM Descomentar la siguiente línea para mantener la ventana abierta en modo debug
REM pause