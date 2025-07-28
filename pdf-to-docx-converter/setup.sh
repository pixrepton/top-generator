#!/bin/bash

echo "🔧 PDF to DOCX Converter - Instalacja"
echo "====================================="

# Sprawdź czy Python jest zainstalowany
if ! command -v python3 &> /dev/null; then
    echo "❌ Python3 nie jest zainstalowany"
    echo "Zainstaluj Python3 przed uruchomieniem tego skryptu"
    exit 1
fi

echo "✅ Python3 znaleziony: $(python3 --version)"

# Sprawdź czy pip jest zainstalowany
if ! command -v pip3 &> /dev/null; then
    echo "❌ pip3 nie jest zainstalowany"
    echo "Zainstaluj pip3 przed uruchomieniem tego skryptu"
    exit 1
fi

echo "✅ pip3 znaleziony"

# Utwórz środowisko wirtualne
echo "📦 Tworzenie środowiska wirtualnego..."
python3 -m venv venv

# Aktywuj środowisko wirtualne
echo "🔄 Aktywacja środowiska wirtualnego..."
source venv/bin/activate

# Zainstaluj wymagane pakiety
echo "📥 Instalacja wymaganych pakietów..."
pip install -r requirements.txt

# Ustaw uprawnienia wykonywania
chmod +x pdf_to_docx_converter.py
chmod +x batch_convert.py

echo ""
echo "✅ Instalacja zakończona!"
echo ""
echo "🚀 Jak używać:"
echo "   1. Aktywuj środowisko: source venv/bin/activate"
echo "   2. Konwertuj pojedynczy plik: python pdf_to_docx_converter.py plik.pdf"
echo "   3. Konwertuj katalog: python pdf_to_docx_converter.py -d ./katalog_pdf/"
echo "   4. Konwersja wsadowa: python batch_convert.py"
echo ""
echo "📖 Więcej opcji: python pdf_to_docx_converter.py --help"