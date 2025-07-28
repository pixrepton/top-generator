#!/usr/bin/env python3
"""
PDF to DOCX Web Converter
Aplikacja webowa do konwersji PDF na DOCX
"""

from flask import Flask, render_template, request, send_file, jsonify, redirect, url_for
import os
import zipfile
from werkzeug.utils import secure_filename
from pdf_to_docx_converter import PDFToDocxConverter
import tempfile
import shutil
from pathlib import Path
import time

app = Flask(__name__)
app.config['MAX_CONTENT_LENGTH'] = 100 * 1024 * 1024  # 100MB max
app.config['UPLOAD_FOLDER'] = 'uploads'
app.config['OUTPUT_FOLDER'] = 'outputs'

# Utwórz foldery jeśli nie istnieją
os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)
os.makedirs(app.config['OUTPUT_FOLDER'], exist_ok=True)

ALLOWED_EXTENSIONS = {'pdf'}

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/upload', methods=['POST'])
def upload_files():
    if 'files' not in request.files:
        return jsonify({'error': 'Nie wybrano plików'}), 400
    
    files = request.files.getlist('files')
    
    if not files or files[0].filename == '':
        return jsonify({'error': 'Nie wybrano plików'}), 400
    
    # Sprawdź czy wszystkie pliki to PDF
    for file in files:
        if not allowed_file(file.filename):
            return jsonify({'error': f'Plik {file.filename} nie jest plikiem PDF'}), 400
    
    # Zapisz pliki
    uploaded_files = []
    timestamp = str(int(time.time()))
    upload_dir = os.path.join(app.config['UPLOAD_FOLDER'], timestamp)
    os.makedirs(upload_dir, exist_ok=True)
    
    for file in files:
        if file and allowed_file(file.filename):
            filename = secure_filename(file.filename)
            filepath = os.path.join(upload_dir, filename)
            file.save(filepath)
            uploaded_files.append(filepath)
    
    # Konwertuj pliki
    converter = PDFToDocxConverter()
    output_dir = os.path.join(app.config['OUTPUT_FOLDER'], timestamp)
    os.makedirs(output_dir, exist_ok=True)
    
    results = {}
    converted_files = []
    
    for pdf_path in uploaded_files:
        success, output_path, error = converter.convert_single_file(pdf_path, output_dir)
        results[os.path.basename(pdf_path)] = {
            'success': success,
            'output_path': output_path,
            'error': error
        }
        if success and output_path:
            converted_files.append(output_path)
    
    # Utwórz ZIP z wynikami
    if converted_files:
        zip_path = os.path.join(output_dir, 'converted_files.zip')
        with zipfile.ZipFile(zip_path, 'w') as zipf:
            for file_path in converted_files:
                zipf.write(file_path, os.path.basename(file_path))
        
        # Wyczyść pliki tymczasowe
        shutil.rmtree(upload_dir)
        for file_path in converted_files:
            if os.path.exists(file_path):
                os.remove(file_path)
        
        return jsonify({
            'success': True,
            'download_url': f'/download/{timestamp}',
            'results': results,
            'total_files': len(uploaded_files),
            'converted_files': len(converted_files)
        })
    else:
        return jsonify({
            'success': False,
            'error': 'Nie udało się skonwertować żadnego pliku',
            'results': results
        }), 400

@app.route('/download/<timestamp>')
def download_file(timestamp):
    zip_path = os.path.join(app.config['OUTPUT_FOLDER'], timestamp, 'converted_files.zip')
    if os.path.exists(zip_path):
        return send_file(zip_path, as_attachment=True, download_name='converted_pdfs.zip')
    else:
        return "Plik nie został znaleziony", 404

@app.route('/cleanup/<timestamp>')
def cleanup(timestamp):
    """Czyści pliki po pobraniu"""
    output_dir = os.path.join(app.config['OUTPUT_FOLDER'], timestamp)
    if os.path.exists(output_dir):
        shutil.rmtree(output_dir)
    return jsonify({'status': 'cleaned'})

if __name__ == '__main__':
    print("🌐 PDF to DOCX Web Converter")
    print("=" * 40)
    print("🚀 Uruchamianie serwera...")
    print("📱 Otwórz w przeglądarce: http://localhost:5000")
    print("📱 Lub z telefonu: http://[IP_KOMPUTERA]:5000")
    print("🛑 Aby zatrzymać: Ctrl+C")
    print("=" * 40)
    
    app.run(host='0.0.0.0', port=5000, debug=False)