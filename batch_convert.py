#!/usr/bin/env python3
"""
Batch PDF to DOCX Converter
Prosty skrypt do szybkiej konwersji wielu plików PDF na DOCX
"""

import os
import sys
from pathlib import Path
from pdf_to_docx_converter import PDFToDocxConverter, find_pdf_files, print_results

def main():
    print("🔄 BATCH PDF TO DOCX CONVERTER")
    print("=" * 40)
    
    # Sprawdź czy podano katalog jako argument
    if len(sys.argv) > 1:
        input_dir = sys.argv[1]
    else:
        input_dir = input("📁 Podaj ścieżkę do katalogu z plikami PDF: ").strip()
    
    if not os.path.exists(input_dir):
        print(f"❌ Katalog nie istnieje: {input_dir}")
        sys.exit(1)
    
    # Znajdź pliki PDF
    pdf_files = find_pdf_files(input_dir)
    
    if not pdf_files:
        print(f"❌ Nie znaleziono plików PDF w katalogu: {input_dir}")
        sys.exit(1)
    
    print(f"📁 Znaleziono {len(pdf_files)} plików PDF")
    
    # Zapytaj o katalog wyjściowy
    output_dir = input("📂 Katalog wyjściowy (Enter = ten sam katalog): ").strip()
    if not output_dir:
        output_dir = None
    
    # Inicjalizuj konwerter
    converter = PDFToDocxConverter()
    
    print(f"\n🚀 Rozpoczynam konwersję {len(pdf_files)} plików...")
    
    # Konwertuj pliki
    results = converter.convert_batch(pdf_files, output_dir)
    
    # Wyświetl wyniki
    print_results(results)
    
    print("\n✅ Konwersja wsadowa zakończona!")

if __name__ == "__main__":
    main()