<?php
/**
 * Plugin Name: Top-Instal Generator
 * Plugin URI: https://topinstal.com.pl
 * Description: Generator ofert dla instalacji HVAC z interfejsem Windows 95
 * Version: 1.0.0
 * Author: Top-Instal
 * License: GPL v2 or later
 * Text Domain: top-instal-generator
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TOP_INSTAL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TOP_INSTAL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TOP_INSTAL_PLUGIN_VERSION', '1.0.0');

// Include Composer autoload
if (file_exists(TOP_INSTAL_PLUGIN_PATH . 'vendor/autoload.php')) {
    require_once TOP_INSTAL_PLUGIN_PATH . 'vendor/autoload.php';
} else {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Top-Instal Generator: Composer dependencies missing. Please upload vendor folder.</p></div>';
    });
}

// Enqueue scripts and styles
function top_instal_enqueue_scripts() {
    wp_enqueue_style('top-instal-generator', TOP_INSTAL_PLUGIN_URL . 'generator.css', array(), TOP_INSTAL_PLUGIN_VERSION);
    wp_enqueue_script('top-instal-generator', TOP_INSTAL_PLUGIN_URL . 'generator.js', array('jquery'), TOP_INSTAL_PLUGIN_VERSION, true);

    wp_localize_script('top-instal-generator', 'topInstal', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('top_instal_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'top_instal_enqueue_scripts');

// Register shortcode
function top_instal_quote_form_shortcode() {
    ob_start();
    ?>
    <div class="top-instal-wp-container">
        <div class="win95-window" id="mainWindow">
            <div class="win95-title-bar">
                <div class="win95-title-text">🏠 Generator Ofert HVAC - Top-Instal v1.0</div>
                <div class="win95-window-controls">
                    <button class="win95-btn-minimize">_</button>
                    <button class="win95-btn-maximize">□</button>
                    <button class="win95-btn-close">×</button>
                </div>
            </div>

            <div class="win95-content">
                <div class="win95-form">
                    <form id="generatorForm" class="win95-form">
                        <div class="win95-group">
    <label class="win95-label">⚡ Rodzaj pompy ciepła:</label>
    <div class="win95-button-group">
        <label class="win95-label"><input type="radio" name="pompa_type" value="1f_split" checked> 1-fazowa split</label>
        <label class="win95-label"><input type="radio" name="pompa_type" value="1f_aio"> 1-fazowa All-in-One</label>
        <label class="win95-label"><input type="radio" name="pompa_type" value="3f_split"> 3-fazowa split</label>
        <label class="win95-label"><input type="radio" name="pompa_type" value="3f_aio"> 3-fazowa All-in-One</label>
    </div>
</div>

                        <div class="win95-group" id="tankCapacityGroup">
                            <label class="win95-label" for="tank_capacity">📏 Pojemność zbiornika na C.W.U.:</label>
                            <select id="tank_capacity" name="tank_capacity" class="win95-select" required>
                                <option value="150">150 litrów</option>
                                <option value="160">160 litrów</option>
                                <option value="200" selected>200 litrów</option>
                                <option value="250">250 litrów</option>
                                <option value="300">300 litrów</option>
                                <option value="400">400 litrów</option>
                                <option value="185-aio">ALL-IN-ONE 185L</option>
                                <option value="260-aio">ALL-IN-ONE 260L</option>
                            </select>
                        </div>

                        <div class="win95-group" id="tankManufacturerGroup">
                        <label for="tank-manufacturer-select" class="win95-label">Producent zbiornika:</label>
                        <select id="tank-manufacturer-select" name="tank_manufacturer" class="win95-select">
                            <option value=" ">Nie wpisuj</option>
                            <option value="THERMATEC">Thermatec</option>
                            <option value="VIQTIS">Viqtis</option>
                            <option value="ECLIS Puretherm">Eclis</option>
                            <option value="Trinnity" selected>Trinnity! TAK! Od Senczka</option>
                            <option value="Galmet">Galmet - co ty galmeta nie robimy juz</option>
                        </select>
                    </div>

                        <!-- Buffer Tank -->
                        <div class="win95-group">
                            <label class="win95-label" for="buffer_capacity">🔄 CZY Z BUFOREM ?:</label>
                            <select id="buffer_capacity" name="buffer_capacity" class="win95-select">
                                <option value="none" selected>Bez bufora</option>
                                <option value="60">60 litrów</option>
                                <option value="80">80 litrów</option>
                                <option value="100">100 litrów</option>
                                <option value="120">120 litrów</option>
                                <option value="150">150 litrów</option>
                                <option value="200">200 litrów</option>
                            </select>
                        </div>

                        <!-- Kit Selection -->
                        <div class="win95-group">
                            <label class="win95-label" for="kit_model">🎯 Model zestawu pompy ciepła:</label>
                            <select id="kit_model" name="kit_model" class="win95-select" required>
                                <option value="">Wybierz zestaw...</option>
                            </select>
                            <div id="kit-loading" class="win95-status loading" style="display: none;">⏳ Ładowanie zestawów...</div>
                        </div>

                        <!-- Price -->
                        <div class="win95-group">
                            <label class="win95-label" for="custom_price">💰 Cena (zł brutto):</label>
                            <input type="number" id="custom_price" name="custom_price" class="win95-input" value="41000" min="1000" step="100" required>
                        </div>

                        <!-- Material Cost (hidden field) -->
                    <div class="win95-group">
                        <label for="material-cost-select" class="win95-label">Materiał koszt (nie widać w ofercie [wpisuje na potrzeby obliczeń]):</label>
                        <select id="material-cost-select" name="material_cost_type" class="win95-select">
                            <option value="3000">nowy dom ok. 3000</option>
                            <option value="4500" selected>modernizacja ok. 4500</option>
                            <option value="5500">modernizacja + dwa obiegi ok 5500</option>
                            <option value="custom">wpisze ręcznie koszt materiału</option>
                        </select>
                    </div>

                    <!-- Custom Material Cost (shown when "custom" selected) -->
                    <div id="custom-material-cost-group" class="win95-group hidden">
                        <label for="custom-material-cost" class="win95-label">Koszt materiału (1000-10000 PLN):</label>
                        <input type="number" id="custom-material-cost" name="custom_material_cost" 
                               class="win95-input" min="1000" max="10000" step="100">
                    </div>

                        <!-- Output Format -->
                        <div class="win95-group">
                            <label class="win95-label" for="output_format">📄 Format wyjściowy:</label>
                            <div class="win95-button-group">
                                  <label class="win95-label">
                                    <input type="radio" name="output_format" value="pdf" checked>
                                    <span>PDF (.pdf)</span>
                                </label>
                                <label class="win95-label">
                                    <input type="radio" name="output_format" value="docx">
                                    <span>Word (.docx)</span>
                                </label>
                            </div>
                        </div>

                        <div class="win95-separator"></div>

                        <!-- Generate Button -->
                        <div class="win95-group">
                            <button type="submit" id="generateBtn" class="win95-button win95-button-primary">
                                🔄 Generuj Ofertę
                            </button>
                        </div>
                    </form>

                    <!-- Results -->
                    <div id="results" style="display: none;">
                        <div class="win95-status success">
                            <strong>✅ Oferta wygenerowana!</strong><br>
                            <span id="resultText"></span><br>
                            <a id="downloadLink" href="#" class="win95-button" target="_blank">⬇️ Pobierz ofertę</a>
                        </div>
                    </div>

                    <div id="error" style="display: none;">
                        <div class="win95-status error">
                            <strong>❌ Błąd</strong><br>
                            <span id="errorText"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="win95-status">
                <span>Ready</span> | <span>Top-Instal Generator v1.0</span> | <span id="currentTime"></span>
            </div>
        </div>
    </div>

<script>
    // Update time in status bar
    function updateTime() {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('pl-PL');
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Form handling
    jQuery(document).ready(function($) {
        const form = $('#generatorForm');
        const tankCapacityGroup = $('#tankCapacityGroup');
        const tankManufacturerGroup = $('#tankManufacturerGroup');
        const kitModelSelect = $('#kit_model');
        const kitLoading = $('#kit-loading');
        const tankCapacitySelect = $('#tank_capacity');
        const tankManufacturerSelect = $('#tank-manufacturer-select');

        // 🔁 Nowa funkcja – zachowanie po wyborze rodzaju pompy
        function handlePompaSelection() {
            const selected = $('input[name="pompa_type"]:checked').val();

            if (selected === '1f_split' || selected === '3f_split') {
                tankCapacitySelect.prop('disabled', false);
                tankManufacturerSelect.prop('disabled', false);
                tankManufacturerGroup.show();
                tankCapacityGroup.show();

                // Blokujemy AIO opcje
                tankCapacitySelect.find('option[value="185-aio"], option[value="260-aio"]').prop('disabled', true);
            } else {
                tankCapacityGroup.show(); // może być widoczne, ale zablokowane
                tankCapacitySelect.prop('disabled', true);
                tankManufacturerSelect.prop('disabled', true);
                tankManufacturerGroup.hide();

                if (selected === '1f_aio') {
                    tankCapacitySelect.val('185-aio');
                } else if (selected === '3f_aio') {
                    tankCapacitySelect.val('260-aio');
                }

                // Odblokuj AIO opcje, jeśli były zablokowane
                tankCapacitySelect.find('option[value="185-aio"], option[value="260-aio"]').prop('disabled', false);
                tankManufacturerSelect.val(''); // wyczyść producenta
            }

            loadKits();
        }

        // 🔁 Nowa funkcja ładowania zestawów pomp
        function loadKits() {
            const pompaType = $('input[name="pompa_type"]:checked').val();
            const tankCapacity = $('#tank_capacity').val();

            let powerType = '1fazowe';
            if (pompaType === '3f_split' || pompaType === '3f_aio') {
                powerType = '3fazowe';
            }

            kitLoading.show();
            kitModelSelect.html('<option value="">Ładowanie...</option>');

            $.ajax({
                url: topInstal.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_kits',
                    nonce: topInstal.nonce,
                    power_type: powerType,
                    tank_capacity: tankCapacity
                },
                success: function(response) {
                    kitLoading.hide();
                    kitModelSelect.html('<option value="">Wybierz zestaw...</option>');

                    if (response.success && response.data) {
                        $.each(response.data, function(kitModel, kit) {
                            const option = $('<option></option>')
                                .attr('value', kitModel)
                                .text(kitModel + ' (' + kit.power + ', ' + kit.voltage + ')');
                            kitModelSelect.append(option);
                        });
                    } else {
                        kitModelSelect.html('<option value="">Brak dostępnych zestawów</option>');
                    }
                },
                error: function() {
                    kitLoading.hide();
                    kitModelSelect.html('<option value="">Błąd ładowania zestawów</option>');
                }
            });
        }

        // Obsługa zmiany typu pompy
        $('input[name="pompa_type"]').on('change', handlePompaSelection);
        $('#tank_capacity').on('change', loadKits);

        // Obsługa formularza
        form.on('submit', function(e) {
            e.preventDefault();

            const generateBtn = $('#generateBtn');
            const originalText = generateBtn.html();
            generateBtn.html('⏳ Generowanie...').prop('disabled', true);

            const formData = form.serializeArray();
            const data = {};
            $.each(formData, function(i, field) {
                data[field.name] = field.value;
            });

            $.ajax({
                url: topInstal.ajaxurl,
                type: 'POST',
                data: {
                    action: 'simple_generate',
                    nonce: topInstal.nonce,
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    generateBtn.html(originalText).prop('disabled', false);

                    if (response.success) {
                        $('#resultText').text('Oferta została wygenerowana: ' + response.data.filename);
                        $('#downloadLink').attr('href', response.data.download_url);
                        $('#results').show();
                        $('#error').hide();
                    } else {
                        $('#errorText').text(response.data || 'Nieznany błąd');
                        $('#error').show();
                        $('#results').hide();
                    }
                },
                error: function(xhr, status, error) {
                    generateBtn.html(originalText).prop('disabled', false);
                    $('#errorText').text('Błąd połączenia: ' + error);
                    $('#error').show();
                    $('#results').hide();
                }
            });
        });

        // Inicjalizacja na starcie
        handlePompaSelection();
    });
</script>

    <?php
    return ob_get_clean();
}
add_shortcode('top_instal_offer_generator', 'top_instal_quote_form_shortcode');

// AJAX: get kits
add_action('wp_ajax_get_kits', 'handle_get_kits');
add_action('wp_ajax_nopriv_get_kits', 'handle_get_kits');

function handle_get_kits() {
    check_ajax_referer('top_instal_nonce', 'nonce');

    $power_type = sanitize_text_field($_POST['power_type'] ?? '1fazowe');
    $tank_capacity = sanitize_text_field($_POST['tank_capacity'] ?? '200');

    // Simplified tank capacity mapping
    $data_key = match($tank_capacity) {
        '185-aio' => 'all_in_one_185',
        '260-aio' => 'all_in_one_260',
        default => $power_type
    };

    $kits_file = TOP_INSTAL_PLUGIN_PATH . 'kits.json';
    if (!file_exists($kits_file)) {
        wp_send_json_error('Plik z zestawami nie istnieje');
    }

    // Implement caching for product kits
    $cache_key = 'top_instal_kits_' . md5($kits_file . filemtime($kits_file));
    $kits_data = wp_cache_get($cache_key);

    if ($kits_data === false) {
        $kits_data = json_decode(file_get_contents($kits_file), true);
        wp_cache_set($cache_key, $kits_data, '', 3600); // Cache for 1 hour
    }

    if (!isset($kits_data[$data_key])) {
        wp_send_json_error("Nie znaleziono zestawów dla klucza: $data_key");
    }

    wp_send_json_success($kits_data[$data_key]);
}

// AJAX: generate DOCX/PDF
add_action('wp_ajax_simple_generate', 'handle_simple_generate');
add_action('wp_ajax_nopriv_simple_generate', 'handle_simple_generate');

function handle_simple_generate() {
    check_ajax_referer('top_instal_nonce', 'nonce');

    $input_data = json_decode(stripslashes($_POST['data']), true);
    if (!$input_data) {
        wp_send_json_error('Invalid JSON input');
    }

    $pompa_type = sanitize_text_field($input_data['pompa_type'] ?? '1f_split');

    // Domyślne wartości
    $power_type = '1fazowe';
    $tank_type = 'with';
    $tank_capacity = sanitize_text_field($input_data['tank_capacity'] ?? '200');
    $tank_manufacturer = sanitize_text_field($input_data['tank_manufacturer'] ?? '');
    $buffer_capacity = sanitize_text_field($input_data['buffer_capacity'] ?? '100');
    $kit_model = sanitize_text_field($input_data['kit_model'] ?? '');
    $custom_price = intval($input_data['custom_price'] ?? 41000);
    $output_format = sanitize_text_field($input_data['output_format'] ?? 'docx');

    if (empty($kit_model) || $custom_price <= 0) {
        wp_send_json_error('Nieprawidłowe dane formularza');
    }

    // Wczytaj zestawy
    $kits_file = TOP_INSTAL_PLUGIN_PATH . 'kits.json';
    if (!file_exists($kits_file)) {
        wp_send_json_error('Plik z zestawami nie istnieje');
    }
    $kits_data = json_decode(file_get_contents($kits_file), true);

    // Znajdź zestaw
    $kit_info = null;
    $kit_group = '';
    foreach ($kits_data as $group => $group_data) {
        if (isset($group_data[$kit_model])) {
            $kit_info = $group_data[$kit_model];
            $kit_group = $group;
            break;
        }
    }

    if (!$kit_info) {
        wp_send_json_error('Nie znaleziono wybranego zestawu');
    }

    // 🔍 Logika wyboru szablonu
    $isAllInOne = str_contains($kit_group, 'all_in_one');
    $is3Phase = ($kit_info['voltage'] ?? '') === '400V';
    $hasCWU = ($tank_type !== 'without');

    if ($isAllInOne) {
        $template_name = $is3Phase ? 'szablon-3f-aio.docx' : 'szablon-1f-aio.docx';
    } else {
        $template_name = match (true) {
            $is3Phase && $hasCWU => 'szablon-3f-split.docx',
            $is3Phase && !$hasCWU => 'szablon-3f-split-nocwu.docx',
            !$is3Phase && $hasCWU => 'szablon-1f-split.docx',
            default => 'szablon-1f-split-nocwu.docx'
        };
    }

    $template_path = TOP_INSTAL_PLUGIN_PATH . $template_name;

    if (!file_exists($template_path)) {
        wp_send_json_error('Szablon nie istnieje: ' . $template_name);
    }

    // 🔁 Ścieżki i przygotowanie pliku wyjściowego
    $upload_dir = wp_upload_dir();
    $output_dir = $upload_dir['basedir'] . '/top-instal-offers';
    if (!file_exists($output_dir)) {
        wp_mkdir_p($output_dir);
    }

    $timestamp = date('Y-m-d_H-i-s');
    $filename = "Oferta-PC-Panasonic.docx";
    $output_path = "$output_dir/$filename";


    copy($template_path, $output_path);

    $zip = new ZipArchive();
    if ($zip->open($output_path) !== TRUE) {
        wp_send_json_error('Nie można otworzyć pliku');
    }

    $document = $zip->getFromName('word/document.xml');
    if ($document === FALSE) {
        $zip->close();
        wp_send_json_error('Nie można odczytać dokumentu');
    }

    // 🔧 Funkcja do czyszczenia XML i znajdowania placeholderów
    function cleanAndReplacePlaceholders($xmlContent, $replacements) {
        error_log("🔧 Starting ultra-advanced placeholder replacement");

        $cleanContent = $xmlContent;

        // Najpierw spróbuj znaleźć normalne placeholdery
        foreach ($replacements as $search => $replace) {
            $count = 0;
            $cleanContent = str_replace($search, $replace, $cleanContent, $count);
            if ($count > 0) {
                error_log("✅ Normal replacement: $search → $replace ($count times)");
                continue;
            }
        }

        // Jeśli normalne nie zadziałało, użyj ultra-agresywnego podejścia
        $placeholderMap = [
            'BFR' => $replacements['{{BFR}}'] ?? '',
            'PRC' => $replacements['{{PRC}}'] ?? '',
            'MOC' => $replacements['{{MOC}}'] ?? '',
            'KIT' => $replacements['{{KIT}}'] ?? '',
            'INDOOR' => $replacements['{{INDOOR}}'] ?? '',
            'OUTDOOR' => $replacements['{{OUTDOOR}}'] ?? '',
            'CWU' => $replacements['{{CWU}}'] ?? '',
            'TANK' => $replacements['{{TANK}}'] ?? '',  
        ];

        foreach ($placeholderMap as $placeholder => $replacement) {
            // Ultra-agresywne wzorce dla bardzo rozbitych placeholderów
            $patterns = [
                // Standardowy wzorzec z tagami XML
                '/\{\{[^}]*?' . implode('[^}]*?', str_split($placeholder)) . '[^}]*?\}\}/is',

                // Wzorzec dla bardzo rozbitych placeholderów (każda litera może być w osobnym tagu)
                '/\{[^}]*?\{[^}]*?' . implode('[^}]*?', str_split($placeholder)) . '[^}]*?\}[^}]*?\}/is',

                // Wzorzec dla placeholderów z wieloma tagami XML
                '/\{(?:[^}]|<[^>]*>)*?\{(?:[^}]|<[^>]*>)*?' . 
                implode('(?:[^}]|<[^>]*>)*?', str_split($placeholder)) . 
                '(?:[^}]|<[^>]*>)*?\}(?:[^}]|<[^>]*>)*?\}/is',

                // Wzorzec dla przypadków gdzie { i } są rozdzielone
                '/\{(?:[^{}]|<[^>]*>)*?' . 
                implode('(?:[^{}]|<[^>]*>)*?', str_split($placeholder)) . 
                '(?:[^{}]|<[^>]*>)*?\}/is',
            ];

            foreach ($patterns as $i => $pattern) {
                $count = 0;
                $cleanContent = preg_replace($pattern, $replacement, $cleanContent, -1, $count);

                if ($count > 0) {
                    error_log("✅ Ultra-replacement pattern $i for $placeholder: $replacement ($count times)");
                    break; // Znalazł i zastąpił, przejdź do następnego placeholdera
                }
            }

            // Jeśli nadal nie znalazł, spróbuj jeszcze bardziej desperackiego podejścia
            if (strpos($cleanContent, $placeholder) !== false) {
                error_log("🔍 Found raw $placeholder in content, trying desperate replacement");

                // Znajdź wszystkie wystąpienia liter placeholdera i zastąp całe okoliczne fragmenty
                $letters = str_split($placeholder);
                $desperate_pattern = '/[^a-zA-Z]' . implode('[^a-zA-Z]*?', $letters) . '[^a-zA-Z]/';

                if (preg_match_all($desperate_pattern, $cleanContent, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[0] as $match) {
                        error_log("🔍 Desperate match found: " . $match[0]);
                        // Zastąp znaleziony fragment
                        $cleanContent = str_replace($match[0], $replacement, $cleanContent);
                    }
                }
            }
        }

        return $cleanContent;
    }

    error_log("🔍 Document analysis for template: $template_name");

    $kits_data = json_decode(file_get_contents($kits_file), true);
    $kit_info = null;
    foreach ($kits_data as $power_kits) {
        if (isset($power_kits[$kit_model])) {
            $kit_info = $power_kits[$kit_model];
            break;
        }
    }

    $kw_value = '9';
    if ($kit_info && preg_match('/(\d+)/', $kit_info['power'], $matches)) {
        $kw_value = $matches[1];
    }

    $elektro_value = ($power_type === '3fazowe') ? '3-FAZOWA | 400 V' : '1-FAZOWA | 230 V';
    $indoor_unit = $kit_info['indoor_unit'] ?? 'WH-SDC09K3E8';
    $outdoor_unit = $kit_info['outdoor_unit'] ?? 'WH-UDZ09KE8';
    $voltage = $kit_info['voltage'] ?? '230V';

    $tank_map = [
        '150' => '150 litrów', '160' => '160 litrów', '200' => '200 litrów',
        '250' => '250 litrów', '300' => '300 litrów', '400' => '400 litrów',
        '185-aio' => 'ALL-IN-ONE 185 litrów', '260-aio' => 'ALL-IN-ONE 260 litrów'
    ];
    $tank_capacity_text = $tank_map[$tank_capacity] ?? '200 litrów';
    $tank_manufacturer_text = empty($tank_manufacturer) ? '' : $tank_manufacturer;

    // Fix buffer capacity logic - check for string 'none' and handle properly
    if ($buffer_capacity === 'none' || empty($buffer_capacity)) {
        $buffer_capacity_text = 'brak - nie rekomendowany';
    } else {
        $buffer_capacity_text = $buffer_capacity . ' litrów';
    }

    $cwu_info = ($tank_type === 'without') ? 'Bez c.w.u.' : $tank_capacity_text;

    // Fix price formatting - ensure it's properly formatted with spaces
    $formatted_price_with_spaces = number_format((int)$custom_price, 0, '', ' ') . ' zł';

    $replacements = [
        '{{MOC}}' => $kw_value,
        '{{KIT}}' => $kit_model,
        '{{INDOOR}}' => $indoor_unit,
        '{{OUTDOOR}}' => $outdoor_unit,
        '{{CWU}}' => $cwu_info,
        '{{TANK}}' => $tank_manufacturer_text,
        '{{BFR}}' => $buffer_capacity_text,
        '{{PRC}}' => $formatted_price_with_spaces,
    ];

    // Debug logging
    error_log("🔍 Debug replacements:");
    error_log("Buffer capacity: " . $buffer_capacity_text);
    error_log("Price: " . $formatted_price_with_spaces);

    // Użyj nowej funkcji do zastępowania
    $document = cleanAndReplacePlaceholders($document, $replacements);

    $zip->addFromString('word/document.xml', $document);
    $zip->close();

    if ($output_format === 'pdf') {
    $pdf_filename = str_replace('.docx', '.pdf', $filename);
    $pdf_output_path = str_replace('.docx', '.pdf', $output_path);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://195.201.147.138/convert');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($output_path, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
    ]);

    $response = curl_exec($ch);
		$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

file_put_contents(__DIR__.'/curl_debug.txt', print_r([
    'http_code' => $httpCode,
    'error' => $error,
    'response' => $response,
], true));
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    error_log("🧪 CURL wysłane na: http://195.201.147.138/convert");
error_log("📎 Plik: $output_path");
error_log("📎 Istnieje? " . (file_exists($output_path) ? 'TAK' : 'NIE'));
error_log("📎 Rozmiar: " . (file_exists($output_path) ? filesize($output_path) . ' B' : 'brak'));
error_log("↩️ HTTP code: $http_code");
error_log("↩️ Response length: " . strlen($response));
error_log("⚠️ CURL error: $error");

    if ($http_code === 200 && $response) {
        file_put_contents($pdf_output_path, $response);
        $filename = $pdf_filename;
    } else {
        error_log("Błąd konwersji PDF: $error");
        wp_send_json_error('Błąd konwersji na PDF przez serwer');
    }
}


    $download_url = $upload_dir['baseurl'] . '/top-instal-offers/' . $filename;

    wp_send_json_success([
        'filename' => $filename,
        'download_url' => $download_url
    ]);
}

// Activation setup
function top_instal_create_directories() {
    $upload_dir = wp_upload_dir();
    $plugin_upload_dir = $upload_dir['basedir'] . '/top-instal-offers';
    if (!file_exists($plugin_upload_dir)) {
        wp_mkdir_p($plugin_upload_dir);
    }
}
register_activation_hook(__FILE__, 'top_instal_create_directories');
?>