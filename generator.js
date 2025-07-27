jQuery(document).ready(function($) {
    // Domyślna cena
    $('#custom_price').val('41000');

    // Inicjalizacja
    handlePompaTypeChange(); // ustawia domyślnie tanki i widoczność
    loadKitOptions();

    // Obsługa zmiany typu pompy
    $('input[name="pompa_type"]').on('change', function () {
        handlePompaTypeChange();
        loadKitOptions();
    });

    $('#tank_capacity').on('change', loadKitOptions);

    // Obsługa formularza
    $('#generatorForm').on('submit', function(e) {
        e.preventDefault();

        const generateBtn = $('#generateBtn');
        const originalText = generateBtn.html();
        generateBtn.html('⏳ Generowanie...').prop('disabled', true);

        const formData = $(this).serializeArray();
        const data = {};
        $.each(formData, function(i, field) {
            data[field.name] = field.value;
        });

        fetch(topInstal.ajaxurl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'simple_generate',
                nonce: topInstal.nonce,
                ...data
            })
        })
        .then(res => res.json())
        .then(response => {
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
        })
        .catch(err => {
            generateBtn.html(originalText).prop('disabled', false);
            $('#errorText').text('Błąd sieci: ' + err.message);
            $('#error').show();
            $('#results').hide();
        });
    });

    // Logika zależności od wyboru typu pompy
    function handlePompaTypeChange() {
        const type = $('input[name="pompa_type"]:checked').val();
        const $tankCapacity = $('#tank_capacity');
        const $tankManufacturer = $('#tank-manufacturer-select');

        if (type === '1f_split' || type === '3f_split') {
            $tankCapacity.prop('disabled', false);
            $tankManufacturer.prop('disabled', false).parent().show();

            // blokujemy AIO opcje
            $tankCapacity.find('option[value="185-aio"], option[value="260-aio"]').prop('disabled', true);
        } else {
            // AIO – narzucamy odpowiednią wartość
            const forced = (type === '1f_aio') ? '185-aio' : '260-aio';
            $tankCapacity.val(forced).prop('disabled', true);
            $tankCapacity.find('option[value="185-aio"], option[value="260-aio"]').prop('disabled', false);
            $tankManufacturer.val('').prop('disabled', true).parent().hide();
        }
    }

    // Ładowanie zestawów pomp z serwera
    function loadKitOptions() {
        const pompaType = $('input[name="pompa_type"]:checked').val();
        const tankCapacity = $('#tank_capacity').val();
        const kitSelect = $('#kit_model');
        const loading = $('#kit-loading');

        let powerType = '1fazowe';
        if (pompaType === '3f_split' || pompaType === '3f_aio') {
            powerType = '3fazowe';
        }

        kitSelect.empty().append('<option value="">Ładowanie...</option>');
        loading.show();

        fetch(topInstal.ajaxurl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'get_kits',
                nonce: topInstal.nonce,
                power_type: powerType,
                tank_capacity: tankCapacity
            })
        })
        .then(res => res.json())
        .then(response => {
            loading.hide();
            kitSelect.empty();

            if (response.success && response.data) {
                kitSelect.append('<option value="">Wybierz zestaw...</option>');
                for (const [key, kit] of Object.entries(response.data)) {
                    kitSelect.append(`<option value="${key}">${key} (${kit.power}, ${kit.voltage})</option>`);
                }
            } else {
                kitSelect.append('<option value="">Brak dostępnych zestawów</option>');
            }
        })
        .catch(() => {
            loading.hide();
            kitSelect.empty().append('<option value="">Błąd ładowania zestawów</option>');
        });
    }
});