<style>
    .public-order-wrapper {
        color: #f5f5f5;
    }

    .public-order-alert {
        background-color: #fff3cd;
        color: #b30000;
        text-align: center;
        padding: 12px 8px;
        font-size: 17px;
        font-weight: 700;
        border-bottom: 2px solid #ffcc00;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .public-order-card {
        max-width: 860px;
        margin: 0 auto;
        background: #1e1e1e;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.45);
    }

    .public-order-logo-text {
        text-align: center;
        font-size: 40px;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 4px;
    }

    .public-order-logo-subtitle {
        text-align: center;
        font-size: 16px;
        color: #ff7a00;
        margin-bottom: 15px;
    }

    .public-order-card h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #fff;
    }

    .public-order-info {
        background: #242424;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .public-order-info h3 {
        color: #ff7a00;
        margin-top: 15px;
        margin-bottom: 8px;
        font-size: 18px;
    }

    .public-order-info p {
        color: #ccc;
        margin-bottom: 12px;
    }

    .public-order-danger-title {
        color: #ff4d4f !important;
        font-weight: 700;
        text-transform: uppercase;
    }

    .public-order-section-title {
        font-size: 18px;
        font-weight: 700;
        margin-top: 22px;
        border-bottom: 2px solid #ff7a00;
        padding-bottom: 5px;
        margin-bottom: 10px;
        color: #ff7a00;
    }

    .public-order-wrapper label {
        font-weight: 700;
        display: block;
        margin-top: 15px;
        color: #ddd;
    }

    .public-order-wrapper input,
    .public-order-wrapper textarea,
    .public-order-wrapper select {
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #555;
        background: #2a2a2a;
        color: #f5f5f5;
        font-size: 14px;
    }

    .public-order-wrapper input:focus,
    .public-order-wrapper textarea:focus,
    .public-order-wrapper select:focus {
        border-color: #0d6efd;
        background: #333;
        outline: none;
    }

    .public-order-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .public-order-col {
        flex: 1;
        min-width: 150px;
    }

    .public-order-submit {
        background: linear-gradient(90deg, #0d6efd, #ff7a00);
        color: #fff;
        padding: 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 25px;
        width: 100%;
        font-size: 16px;
        transition: transform 0.2s ease, filter 0.3s ease;
    }

    .public-order-submit:hover {
        transform: scale(1.01);
        filter: brightness(1.15);
    }

    .pj-wrapper {
        margin-top: 10px;
    }

    .pj-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
        font-weight: 700;
        color: #ddd;
    }

    .pj-checkbox input[type="checkbox"] {
        width: auto;
        margin: 0;
        transform: scale(1.2);
        cursor: pointer;
    }

    #firmaContainer {
        display: none;
        margin-top: 10px;
    }

    .public-order-error {
        margin-top: 10px;
        color: #ff6666;
        font-weight: 700;
    }

    .public-order-popup {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .public-order-popup-content {
        background-color: #1e1e1e;
        color: #fff;
        margin: 15% auto;
        padding: 20px;
        border-radius: 10px;
        width: 80%;
        max-width: 430px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.12);
        position: relative;
    }

    .public-order-popup-close {
        position: absolute;
        right: 15px;
        top: 10px;
        color: #aaa;
        font-size: 28px;
        font-weight: 700;
        cursor: pointer;
    }

    .public-order-popup-close:hover {
        color: #fff;
    }

    .public-order-popup button {
        margin-top: 15px;
        background-color: #0d6efd;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
    }

    .public-order-popup button:hover {
        background-color: #ff7a00;
    }

    @media (max-width: 600px) {
        .public-order-card {
            padding: 20px;
        }

        .public-order-card h2 {
            font-size: 22px;
        }

        .public-order-logo-text {
            font-size: 32px;
        }

        .public-order-row {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<div class="public-order-wrapper">
    <div class="public-order-alert">Citiți cu atenție înainte de a completa formularul!</div>

    <div class="public-order-card">
        <div class="public-order-logo-text">TMSolar S.R.L Ploiești</div>
        <div class="public-order-logo-subtitle">Service / Reparații Fotovoltaice</div>

        <h2>Formular Cerere Service</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars((string) $error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div>
        <?php endif; ?>

        <div class="public-order-info">
            <h3>Servicii rapide și complete de reparații</h3>
            <p>Îți oferim evaluări rapide și servicii complete pentru echipamentele tale fotovoltaice. Ne angajăm să furnizăm servicii eficiente și transparente, cu informații clare despre costuri și garanția lucrărilor efectuate.</p>

            <h3>Echipă calificată</h3>
            <p>Experiență de peste 6 ani în diagnosticarea și remedierea echipamentelor fotovoltaice. Asigurăm funcționarea optimă și durabilă a echipamentelor tale.</p>

            <h3>Modalitate de lucru</h3>
            <p>Completează formularul și așteaptă confirmarea din partea noastră. După confirmare, vei primi adresa și instrucțiunile pentru trimiterea echipamentului.</p>

            <h3>Taxa de constatare</h3>
            <p>Se percepe o taxă de diagnostic pentru evaluarea tehnică. Dacă optezi pentru reparație, taxa se exclude. În caz că nu dorești reparația sau defectul nu se confirmă, taxa trebuie achitată.</p>

            <h3>Transport</h3>
            <p>Beneficiarul plătește transportul către noi și retur. Beneficiarul este responsabil de ambalarea corectă a echipamentului trimis în service.</p>

            <h3 class="public-order-danger-title">Refuzăm preluarea de la curier a echipamentelor ambalate necorespunzător fără nicio explicație!</h3>
        </div>

        <form method="post" action="<?= UrlHelper::to('comanda-online') ?>" id="serviceForm">
            <div class="public-order-section-title">Detalii produs / cerere</div>

            <label for="product">Produs / Model</label>
            <input type="text" id="product" name="product" value="<?= htmlspecialchars((string) ($form['product'] ?? '')) ?>" required>

            <label for="serial">Serie / IMEI</label>
            <input type="text" id="serial" name="serial" value="<?= htmlspecialchars((string) ($form['serial'] ?? '')) ?>" required>

            <div class="public-order-row">
                <div class="public-order-col">
                    <label for="garantie">Tip Comandă</label>
                    <select id="garantie" name="garantie" required>
                        <?php $garantieValue = (string) ($form['garantie'] ?? ''); ?>
                        <option value="" <?= $garantieValue === '' ? 'selected' : '' ?>>Selectează...</option>
                        <option value="Service" <?= $garantieValue === 'Service' ? 'selected' : '' ?>>Service</option>
                        <option value="Service Garantie" <?= $garantieValue === 'Service Garantie' ? 'selected' : '' ?>>Service Garanție</option>
                    </select>
                </div>
                <div class="public-order-col" id="dataReparatieContainer">
                    <label for="data">Data Reparației dacă este în Garanție</label>
                    <input type="date" id="data" name="data" value="<?= htmlspecialchars((string) ($form['data'] ?? '')) ?>">
                </div>
            </div>

            <label for="problem">Descriere problemă</label>
            <textarea id="problem" name="problem" rows="4" required><?= htmlspecialchars((string) ($form['problem'] ?? '')) ?></textarea>

            <div class="public-order-section-title">Date personale</div>
            <div class="public-order-row">
                <div class="public-order-col">
                    <label for="name">Nume și Prenume</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars((string) ($form['name'] ?? '')) ?>" required>
                </div>
                <div class="public-order-col">
                    <label for="phone">Telefon</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars((string) ($form['phone'] ?? '')) ?>" required>
                </div>
            </div>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars((string) ($form['email'] ?? '')) ?>" required>

            <div class="public-order-section-title">Tip client</div>
            <div class="pj-wrapper">
                <label class="pj-checkbox">
                    <input type="checkbox" id="persJuridica" name="persJuridica" value="1" <?= !empty($form['persJuridica']) ? 'checked' : '' ?>>
                    <span>Persoană juridică (bifează dacă ești firmă)</span>
                </label>

                <div id="firmaContainer">
                    <label for="firma">Nume Firmă</label>
                    <input type="text" id="firma" name="firma" value="<?= htmlspecialchars((string) ($form['firma'] ?? '')) ?>">

                    <label for="cui">CUI</label>
                    <input type="text" id="cui" name="cui" value="<?= htmlspecialchars((string) ($form['cui'] ?? '')) ?>">
                </div>
            </div>

            <div class="public-order-section-title">Adresă livrare</div>
            <div class="public-order-row">
                <div class="public-order-col">
                    <label for="judet">Județ</label>
                    <?php $judetValue = (string) ($form['judet'] ?? ''); ?>
                    <select id="judet" name="judet" required>
                        <option value="" <?= $judetValue === '' ? 'selected' : '' ?>>Selectează județul</option>
                        <?php
                        $judete = [
                            'Alba', 'Arad', 'Argeș', 'Bacău', 'Bihor', 'Bistrița-Năsăud', 'Botoșani', 'Brașov',
                            'Brăila', 'București', 'Buzău', 'Călărași', 'Caraș-Severin', 'Cluj', 'Constanța',
                            'Covasna', 'Dâmbovița', 'Dolj', 'Galați', 'Giurgiu', 'Gorj', 'Harghita', 'Hunedoara',
                            'Ialomița', 'Iași', 'Ilfov', 'Maramureș', 'Mehedinți', 'Mureș', 'Neamț', 'Olt',
                            'Prahova', 'Satu Mare', 'Sălaj', 'Sibiu', 'Suceava', 'Teleorman', 'Timiș', 'Tulcea',
                            'Vaslui', 'Vâlcea', 'Vrancea',
                        ];
                        foreach ($judete as $judet):
                        ?>
                            <option value="<?= htmlspecialchars($judet) ?>" <?= $judetValue === $judet ? 'selected' : '' ?>><?= htmlspecialchars($judet) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="public-order-col">
                    <label for="oras">Oraș</label>
                    <input type="text" id="oras" name="oras" value="<?= htmlspecialchars((string) ($form['oras'] ?? '')) ?>" required>
                </div>
            </div>

            <label for="adresa">Adresă</label>
            <input type="text" id="adresa" name="adresa" value="<?= htmlspecialchars((string) ($form['adresa'] ?? '')) ?>" required>

            <label for="transport">Transport</label>
            <?php $transportValue = (string) ($form['transport'] ?? ''); ?>
            <select id="transport" name="transport" required>
                <option value="" <?= $transportValue === '' ? 'selected' : '' ?>>Selectează...</option>
                <option value="Curier" <?= $transportValue === 'Curier' ? 'selected' : '' ?>>Curier</option>
                <option value="Personal" <?= $transportValue === 'Personal' ? 'selected' : '' ?>>Personal</option>
            </select>

            <?php if (!empty($recaptchaEnabled)): ?>
                <div class="mt-3">
                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars((string) ($recaptchaSiteKey ?? '')) ?>"></div>
                </div>
            <?php endif; ?>

            <button type="submit" class="public-order-submit" id="submitBtn">Trimite cererea</button>
            <p class="public-order-error" id="errorMsg"></p>
        </form>
    </div>
</div>

<div id="publicOrderPopup" class="public-order-popup">
    <div class="public-order-popup-content">
        <span class="public-order-popup-close" id="publicOrderPopupClose">&times;</span>
        <p id="publicOrderPopupText"></p>
        <button id="publicOrderPopupOk" type="button">OK</button>
    </div>
</div>

<?php if (!empty($recaptchaEnabled)): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<script>
    (function () {
        const garantieSelect = document.getElementById('garantie');
        const dataContainer = document.getElementById('dataReparatieContainer');
        const dataInput = document.getElementById('data');
        const persJuridicaCheckbox = document.getElementById('persJuridica');
        const firmaContainer = document.getElementById('firmaContainer');
        const firmaInput = document.getElementById('firma');
        const cuiInput = document.getElementById('cui');
        const form = document.getElementById('serviceForm');
        const errorMsg = document.getElementById('errorMsg');
        const submitBtn = document.getElementById('submitBtn');
        const popup = document.getElementById('publicOrderPopup');
        const popupText = document.getElementById('publicOrderPopupText');
        const popupClose = document.getElementById('publicOrderPopupClose');
        const popupOk = document.getElementById('publicOrderPopupOk');
        let pendingRedirect = '';

        if (!garantieSelect || !dataContainer || !dataInput || !persJuridicaCheckbox || !firmaContainer || !firmaInput || !cuiInput || !form || !errorMsg || !submitBtn || !popup || !popupText || !popupClose || !popupOk) {
            return;
        }

        function updateDataVisibility() {
            if (garantieSelect.value === 'Service Garantie') {
                dataContainer.style.display = 'block';
                dataInput.required = true;
            } else {
                dataContainer.style.display = 'none';
                dataInput.required = false;
                dataInput.value = '';
            }
        }

        function updateCompanyVisibility() {
            if (persJuridicaCheckbox.checked) {
                firmaContainer.style.display = 'block';
                firmaInput.required = true;
                cuiInput.required = true;
            } else {
                firmaContainer.style.display = 'none';
                firmaInput.required = false;
                cuiInput.required = false;
            }
        }

        garantieSelect.addEventListener('change', updateDataVisibility);
        persJuridicaCheckbox.addEventListener('change', updateCompanyVisibility);

        function showPopup(message, redirectUrl) {
            popupText.innerHTML = message;
            pendingRedirect = redirectUrl || '';
            popup.style.display = 'block';
        }

        function closePopupAndRedirect() {
            popup.style.display = 'none';
            if (pendingRedirect !== '') {
                window.location.href = pendingRedirect;
            }
        }

        popupClose.addEventListener('click', closePopupAndRedirect);
        popupOk.addEventListener('click', closePopupAndRedirect);
        window.addEventListener('click', function (event) {
            if (event.target === popup) {
                closePopupAndRedirect();
            }
        });

        form.addEventListener('submit', async function (event) {
            errorMsg.textContent = '';
            event.preventDefault();

            const requiredIds = ['product', 'serial', 'garantie', 'problem', 'name', 'phone', 'email', 'judet', 'oras', 'adresa', 'transport'];
            if (garantieSelect.value === 'Service Garantie') {
                requiredIds.push('data');
            }
            if (persJuridicaCheckbox.checked) {
                requiredIds.push('firma', 'cui');
            }

            let hasEmpty = false;
            requiredIds.forEach(function (id) {
                const el = document.getElementById(id);
                if (!el) {
                    return;
                }
                if (el.value.trim() === '') {
                    hasEmpty = true;
                    el.style.borderColor = '#ff4444';
                } else {
                    el.style.borderColor = '#555';
                }
            });

            if (hasEmpty) {
                errorMsg.textContent = 'Toate câmpurile obligatorii trebuie completate.';
                return;
            }

            const recaptchaEnabled = <?= !empty($recaptchaEnabled) ? 'true' : 'false' ?>;
            if (recaptchaEnabled && typeof window.grecaptcha !== 'undefined' && window.grecaptcha.getResponse() === '') {
                errorMsg.textContent = 'Vă rugăm să confirmați reCAPTCHA.';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Se trimite...';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                if (!result || result.success !== true) {
                    errorMsg.textContent = (result && result.message) ? result.message : 'Nu s-a putut trimite cererea.';
                    return;
                }

                form.reset();
                updateDataVisibility();
                updateCompanyVisibility();
                if (typeof window.grecaptcha !== 'undefined') {
                    window.grecaptcha.reset();
                }

                showPopup(result.message || 'Cererea a fost trimisă cu succes.', result.redirect || '');
            } catch (error) {
                errorMsg.textContent = 'A apărut o eroare la trimitere. Încearcă din nou.';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Trimite cererea';
            }
        });

        updateDataVisibility();
        updateCompanyVisibility();
    })();
</script>
