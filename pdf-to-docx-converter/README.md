# PDF to DOCX Converter

🚀 **Szybki lokalny konwerter plików PDF na DOCX** z obsługą przetwarzania równoległego.

## ✨ Funkcje

- ⚡ **Szybka konwersja** - wykorzystuje wszystkie rdzenie procesora
- 📁 **Konwersja wsadowa** - konwertuje wiele plików jednocześnie
- 🎯 **Prosta obsługa** - interfejs CLI i skrypt interaktywny
- 📊 **Pasek postępu** - śledź proces konwersji
- 🛡️ **Obsługa błędów** - szczegółowe raporty o problemach
- 🔧 **Konfigurowalny** - dostosuj liczbę procesów

## 🔧 Instalacja

1. **Uruchom skrypt instalacyjny:**
   ```bash
   chmod +x setup.sh
   ./setup.sh
   ```

2. **Aktywuj środowisko wirtualne:**
   ```bash
   source venv/bin/activate
   ```

## 🚀 Użycie

### Konwersja pojedynczego pliku
```bash
python pdf_to_docx_converter.py dokument.pdf
```

### Konwersja z określeniem katalogu wyjściowego
```bash
python pdf_to_docx_converter.py dokument.pdf -o ./output/
```

### Konwersja całego katalogu
```bash
python pdf_to_docx_converter.py -d ./katalog_pdf/ -o ./katalog_docx/
```

### Konwersja z określoną liczbą procesów
```bash
python pdf_to_docx_converter.py -d ./pdfs/ --workers 4
```

### Konwersja wsadowa (interaktywna)
```bash
python batch_convert.py
```

## 📋 Opcje CLI

- `pdf_file` - Pojedynczy plik PDF do konwersji
- `-d, --directory` - Katalog z plikami PDF
- `-o, --output` - Katalog wyjściowy (domyślnie: ten sam co źródłowy)
- `-w, --workers` - Liczba procesów równoległych (domyślnie: auto)
- `-q, --quiet` - Tryb cichy (bez paska postępu)
- `-h, --help` - Pomoc

## 📊 Przykład wyjścia

```
Znaleziono 13 plików PDF
Używam 8 procesów równoległych
Konwertowanie PDF → DOCX: 100%|████████████| 13/13 [00:45<00:00,  3.51plik/s]

==================================================
PODSUMOWANIE KONWERSJI
==================================================
Łącznie plików: 13
Pomyślnie skonwertowane: 13
Nieudane: 0

✅ POMYŚLNIE SKONWERTOWANE:
   dokument1.pdf → dokument1.docx
   dokument2.pdf → dokument2.docx
   ...

⏱️  Czas wykonania: 45.32 sekund
```

## 🛠️ Wymagania

- Python 3.7+
- pdf2docx
- python-docx
- PyMuPDF
- Pillow
- tqdm

## 📝 Uwagi

- Konwerter wykorzystuje bibliotekę `pdf2docx` do konwersji
- Przetwarzanie równoległe znacznie przyspiesza konwersję wielu plików
- Jakość konwersji zależy od złożoności dokumentu PDF
- Obsługuje pliki PDF z tekstem, obrazami i podstawowym formatowaniem

## 🐛 Rozwiązywanie problemów

### Błąd instalacji PyMuPDF
```bash
pip install --upgrade pip
pip install PyMuPDF
```

### Problemy z uprawnieniami
```bash
chmod +x *.py
```

### Brak modułu venv
```bash
sudo apt-get install python3-venv  # Ubuntu/Debian
```