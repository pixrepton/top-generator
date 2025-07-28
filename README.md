# PDF to DOCX Converter

🚀 **Szybki lokalny konwerter plików PDF na DOCX** z obsługą przetwarzania równoległego.

## ✨ Funkcje

- ⚡ **Szybka konwersja** - wykorzystuje bibliotekę pdfplumber
- 📁 **Konwersja wsadowa** - konwertuje wiele plików jednocześnie
- 🎯 **Prosta obsługa** - interfejs CLI i skrypty pomocnicze
- 📊 **Szczegółowe raporty** - informacje o sukcesach i błędach
- 🛡️ **Obsługa błędów** - szczegółowe komunikaty o problemach
- 🔧 **Niezależny** - działa lokalnie, nie wymaga internetu

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

### Szybka konwersja (skrypt pomocniczy)
```bash
./quick_convert.sh dokument.pdf
```

### Konwersja wsadowa
```bash
python simple_batch.py katalog_z_pdf/
```

## 📁 Struktura projektu

```
pdf-to-docx-converter/
├── pdf_to_docx_converter.py  # Główny konwerter
├── batch_convert.py          # Skrypt konwersji wsadowej (interaktywny)
├── simple_batch.py           # Prosty skrypt wsadowy
├── quick_convert.sh          # Szybki skrypt bash
├── setup.sh                  # Skrypt instalacyjny
├── requirements.txt          # Wymagane biblioteki Python
├── README.md                 # Ta dokumentacja
└── venv/                     # Środowisko wirtualne Python
```

## 📋 Wymagania

- Python 3.7+
- Biblioteki Python (instalowane automatycznie):
  - `pdfplumber` - ekstrakcja tekstu z PDF
  - `python-docx` - tworzenie dokumentów DOCX
  - `tqdm` - paski postępu
  - `reportlab` - tworzenie przykładowych PDF

## 🎯 Przykłady użycia

### Konwersja pojedynczego pliku
```bash
# Konwertuj plik PDF na DOCX
python pdf_to_docx_converter.py moj_dokument.pdf

# Wynik: moj_dokument.docx w tym samym katalogu
```

### Konwersja wsadowa
```bash
# Konwertuj wszystkie PDF w katalogu
python simple_batch.py ./dokumenty_pdf/

# Wszystkie pliki .pdf zostaną skonwertowane na .docx
```

### Szybka konwersja
```bash
# Użyj skryptu bash dla szybkiej konwersji
./quick_convert.sh dokument.pdf
```

## 🔍 Funkcje konwertera

- **Ekstrakcja tekstu** - wyciąga cały tekst z dokumentu PDF
- **Obsługa stron** - każda strona PDF to osobna sekcja w DOCX
- **Zachowanie formatowania** - próbuje zachować podstawowe formatowanie
- **Obsługa tabel** - wykrywa i konwertuje tabele z PDF
- **Polskie znaki** - pełna obsługa znaków diakrytycznych
- **Raporty błędów** - szczegółowe informacje o problemach

## ⚡ Wydajność

Konwerter został zoptymalizowany pod kątem:
- Szybkiej konwersji pojedynczych plików
- Wsadowego przetwarzania wielu dokumentów
- Minimalnego zużycia pamięci
- Lokalnego działania bez internetu

## 🛠️ Rozwiązywanie problemów

### Błąd: "No module named..."
```bash
# Upewnij się, że środowisko wirtualne jest aktywne
source venv/bin/activate

# Zainstaluj ponownie wymagania
pip install -r requirements.txt
```

### Błąd uprawnień
```bash
# Nadaj uprawnienia wykonywania
chmod +x *.sh *.py
```

### Problemy z konwersją
- Sprawdź czy plik PDF nie jest uszkodzony
- Niektóre PDF mogą być zabezpieczone przed odczytem
- Skomplikowane formatowanie może nie zostać w pełni zachowane

## 📞 Wsparcie

Konwerter jest gotowy do użycia i zawiera wszystkie niezbędne funkcje:
- ✅ Konwersja pojedynczych plików
- ✅ Konwersja wsadowa
- ✅ Obsługa błędów
- ✅ Szczegółowe raporty
- ✅ Polskie znaki
- ✅ Lokalne działanie

**Gotowy do konwersji Twoich dokumentów PDF na DOCX!** 🎉
