@echo off
echo Starting Ngrok for local server on port 80...
echo.
echo Please ensure XAMPP Apache module is running before using this script.
echo.
echo Once Ngrok starts, copy the 'Forwarding' URL (e.g., https://xxxx.ngrok-free.app)
echo and share it with others to access your local project.
echo.
ngrok http 80
pause
