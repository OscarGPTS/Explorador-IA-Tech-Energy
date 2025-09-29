@echo off
REM Remove Windows Scheduled Tasks for News Scraping
REM Ejecutar como Administrador

echo =========================================
echo   REMOVIENDO TAREAS DE NEWS SCRAPING
echo =========================================

echo Removiendo tareas programadas de scraping de noticias...
echo.

REM Remover tareas de scraping
echo Removiendo: NewsIA_Scraping_Matutino
schtasks /delete /tn "NewsIA_Scraping_Matutino" /f

echo Removiendo: NewsIA_Scraping_Mediodia  
schtasks /delete /tn "NewsIA_Scraping_Mediodia" /f

echo Removiendo: NewsIA_Scraping_Vespertino
schtasks /delete /tn "NewsIA_Scraping_Vespertino" /f

echo Removiendo: NewsIA_Cleanup_Weekly
schtasks /delete /tn "NewsIA_Cleanup_Weekly" /f

echo.
echo ✅ Todas las tareas de news scraping han sido removidas

echo.
echo Para verificar que las tareas fueron removidas ejecuta:
echo schtasks /query /tn "NewsIA_*"

pause