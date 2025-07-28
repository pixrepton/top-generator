#!/bin/bash

echo "🌐 PDF to DOCX Web Converter"
echo "============================="

# Sprawdź czy środowisko wirtualne istnieje
if [ ! -d "venv" ]; then
    echo "❌ Środowisko wirtualne nie istnieje"
    echo "Uruchom najpierw: ./setup.sh"
    exit 1
fi

# Aktywuj środowisko wirtualne
source venv/bin/activate

# Sprawdź czy Flask jest zainstalowany
if ! python -c "import flask" 2>/dev/null; then
    echo "📦 Instalowanie Flask..."
    pip install flask
fi

echo ""
echo "🚀 Uruchamianie serwera webowego..."
echo ""
echo "📱 OTWÓRZ W PRZEGLĄDARCE:"
echo "   🖥️  Na komputerze: http://localhost:5000"
echo "   📱 Na telefonie: http://$(hostname -I | awk '{print $1}'):5000"
echo ""
echo "💡 INSTRUKCJA:"
echo "   1. Otwórz powyższy adres w Chrome na iPhone"
echo "   2. Wrzuć swoje 13 plików PDF"
echo "   3. Kliknij 'Konwertuj na DOCX'"
echo "   4. Pobierz plik ZIP z wynikami"
echo ""
echo "🛑 Aby zatrzymać serwer: Ctrl+C"
echo "============================="
echo ""

# Uruchom aplikację
python web_app.py