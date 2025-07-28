#!/usr/bin/env python3
from pdf_to_docx_converter import PDFToDocxConverter, find_pdf_files, print_results
import sys

if len(sys.argv) < 2:
    print("Użycie: python simple_batch.py <katalog_pdf>")
    sys.exit(1)

input_dir = sys.argv[1]
pdf_files = find_pdf_files(input_dir)

print(f"Znaleziono {len(pdf_files)} plików PDF")

converter = PDFToDocxConverter()
results = converter.convert_batch(pdf_files)

print_results(results)
