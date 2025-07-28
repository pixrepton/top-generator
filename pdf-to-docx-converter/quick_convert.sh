#!/bin/bash

# Quick PDF to DOCX Converter
# Szybki skrypt do konwersji PDF na DOCX

echo "⚡ QUICK PDF TO DOCX CONVERTER"
echo "=============================="

# Sprawdź czy podano argumenty
if [ $# -eq 0 ]; then
    echo "❌ Użycie: ./quick_convert.sh [plik.pdf|katalog_pdf/]"
    echo ""
    echo "Przykłady:"
    echo "  ./quick_convert.sh dokument.pdf"
    echo "  ./quick_convert.sh ./katalog_pdf/"
    exit 1
fi

INPUT="$1"

# Sprawdź czy środowisko wirtualne istnieje
if [ ! -d "venv" ]; then
    echo "❌ Środowisko wirtualne nie istnieje"
    echo "Uruchom najpierw: ./setup.sh"
    exit 1
fi

# Aktywuj środowisko wirtualne
source venv/bin/activate

# Sprawdź czy to plik czy katalog
if [ -f "$INPUT" ]; then
    # To jest plik
    echo "📄 Konwertowanie pliku: $INPUT"
    python pdf_to_docx_converter.py "$INPUT"
elif [ -d "$INPUT" ]; then
    # To jest katalog
    echo "📁 Konwertowanie katalogu: $INPUT"
    python pdf_to_docx_converter.py -d "$INPUT"
else
    echo "❌ Plik lub katalog nie istnieje: $INPUT"
    exit 1
fi

echo "✨ Gotowe!"