<?php
declare(strict_types=1);

class ServiceOrderController extends Controller
{
    public function publicOrder(): void
    {
        $error = '';
        $success = '';
        $orderNumber = '';
        $mailWarning = '';
        $mailWarningReason = '';
        $adminMailWarning = '';
        $adminMailWarningReason = '';

        $form = [
            'product' => trim((string) ($_POST['product'] ?? '')),
            'serial' => trim((string) ($_POST['serial'] ?? '')),
            'garantie' => trim((string) ($_POST['garantie'] ?? '')),
            'data' => trim((string) ($_POST['data'] ?? '')),
            'problem' => trim((string) ($_POST['problem'] ?? '')),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'persJuridica' => isset($_POST['persJuridica']) ? '1' : '',
            'firma' => trim((string) ($_POST['firma'] ?? '')),
            'cui' => trim((string) ($_POST['cui'] ?? '')),
            'judet' => trim((string) ($_POST['judet'] ?? '')),
            'oras' => trim((string) ($_POST['oras'] ?? '')),
            'adresa' => trim((string) ($_POST['adresa'] ?? '')),
            'transport' => trim((string) ($_POST['transport'] ?? '')),
            'g-recaptcha-response' => trim((string) ($_POST['g-recaptcha-response'] ?? '')),
        ];

        $publicSettings = MailHelper::loadSettingsFromDatabase();
        $recaptchaEnabled = (string) ($publicSettings['recaptcha_enabled'] ?? '0') === '1';
        $recaptchaSiteKey = trim((string) ($publicSettings['recaptcha_site_key'] ?? ''));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['product', 'serial', 'garantie', 'problem', 'name', 'phone', 'email', 'judet', 'oras', 'adresa', 'transport'];
            foreach ($required as $field) {
                if ($form[$field] === '') {
                    $error = 'Câmp lipsă: ' . $field;
                    break;
                }
            }

            $isCompany = $form['persJuridica'] === '1';
            if ($error === '' && $isCompany && ($form['firma'] === '' || $form['cui'] === '')) {
                $error = 'Pentru persoană juridică, câmpurile firmă și CUI sunt obligatorii.';
            }

            if ($error === '' && $form['garantie'] === 'Service Garantie' && $form['data'] === '') {
                $error = 'Data reparației este obligatorie pentru Service Garanție.';
            }

            $allowedGarantie = ['Service', 'Service Garantie'];
            if ($error === '' && !in_array($form['garantie'], $allowedGarantie, true)) {
                $error = 'Tip comandă invalid.';
            }

            $allowedTransport = ['Curier', 'Personal'];
            if ($error === '' && !in_array($form['transport'], $allowedTransport, true)) {
                $error = 'Tip transport invalid.';
            }

            if ($error === '' && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Email invalid.';
            }

            if ($error === '' && $recaptchaEnabled) {
                $recaptchaCheck = $this->verifyRecaptcha((string) $form['g-recaptcha-response'], $publicSettings);
                if (!$recaptchaCheck['success']) {
                    $error = (string) $recaptchaCheck['message'];
                }
            }

            if ($error === '') {
                try {
                    $db = Database::getInstance();

                    $nameParts = preg_split('/\s+/', $form['name']) ?: [];
                    $firstName = (string) ($nameParts[0] ?? $form['name']);
                    $lastName = trim(implode(' ', array_slice($nameParts, 1)));
                    if ($lastName === '') {
                        $lastName = '-';
                    }

                    $customerModel = new Customer();
                    $customerId = $customerModel->create([
                        ':first_name' => $firstName,
                        ':last_name' => $lastName,
                        ':phone' => $form['phone'],
                        ':email' => $form['email'],
                        ':company' => $isCompany ? $form['firma'] : '',
                        ':vat_number' => $isCompany ? $form['cui'] : '',
                        ':address' => 'Județ: ' . $form['judet'] . ', Oraș: ' . $form['oras'] . ', Adresă: ' . $form['adresa'],
                    ]);

                    $manufacturerId = $this->findOrCreateManufacturer((string) $form['product']);
                    $modelId = $this->findOrCreateDeviceModel($manufacturerId, (string) $form['product']);

                    $deviceModel = new Device();
                    $isWarrantyOrder = $form['garantie'] === 'Service Garantie';
                    $deviceId = $deviceModel->create([
                        ':customer_id' => $customerId,
                        ':manufacturer_id' => $manufacturerId,
                        ':model_id' => $modelId,
                        ':serial_number' => $form['serial'],
                        ':warranty' => $isWarrantyOrder ? 1 : 0,
                        ':purchase_date' => $form['data'] !== '' ? $form['data'] : null,
                        ':photo' => null,
                    ]);

                    $orderNumber = $this->generatePublicOrderNumber($db);
                    $status = 'Noua';

                    $serviceOrderModel = new ServiceOrder();
                    $serviceOrderModel->create([
                        ':order_number' => $orderNumber,
                        ':received_date' => date('Y-m-d'),
                        ':customer_id' => $customerId,
                        ':device_id' => $deviceId,
                        ':command_type' => $form['garantie'],
                        ':transport_type' => $form['transport'],
                        ':accessories' => '',
                        ':reported_fault' => $form['problem'],
                        ':diagnosed_fault' => '',
                        ':repair_notes' => '',
                        ':internal_notes' => "Comandă web publică\nTip client: " . ($isCompany ? 'Persoană juridică' : 'Persoană fizică') . "\n" . ($isCompany ? ('Firmă: ' . $form['firma'] . "\nCUI: " . $form['cui'] . "\n") : '') . "Tip comandă: " . $form['garantie'] . "\nTransport: " . $form['transport'] . "\nJudeț: " . $form['judet'] . "\nOraș: " . $form['oras'] . "\nAdresă: " . $form['adresa'],
                        ':priority' => 'Normal',
                        ':status' => $status,
                        ':repair_cost' => 0,
                        ':labour_cost' => 0,
                        ':shipping_cost' => 0,
                        ':total_cost' => 0,
                        ':warranty_repair' => $isWarrantyOrder ? 1 : 0,
                        ':photos' => null,
                        ':documents' => null,
                    ]);

                    $customerNameEsc = htmlspecialchars((string) $form['name'], ENT_QUOTES, 'UTF-8');
                    $productEsc = htmlspecialchars((string) $form['product'], ENT_QUOTES, 'UTF-8');
                    $serialEsc = htmlspecialchars((string) $form['serial'], ENT_QUOTES, 'UTF-8');
                    $problemEsc = nl2br(htmlspecialchars((string) $form['problem'], ENT_QUOTES, 'UTF-8'));
                    $transportEsc = htmlspecialchars((string) $form['transport'], ENT_QUOTES, 'UTF-8');
                    $orderNumberEsc = htmlspecialchars((string) $orderNumber, ENT_QUOTES, 'UTF-8');
                    $companyEsc = htmlspecialchars((string) $form['firma'], ENT_QUOTES, 'UTF-8');
                    $cuiEsc = htmlspecialchars((string) $form['cui'], ENT_QUOTES, 'UTF-8');

                    usleep(200000);

                    $settings = MailHelper::loadSettingsFromDatabase();

                    $bodyClient = "<div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; font-size: 14px;'>";
                    $bodyClient .= "<p>Salut {$customerNameEsc},</p>";
                    $bodyClient .= "<p>Am primit cererea ta de service. Îți mulțumim!</p>";
                    $bodyClient .= "<p><strong>Număr comandă:</strong> {$orderNumberEsc}<br>";
                    $bodyClient .= "<strong>Produs:</strong> {$productEsc}<br>";
                    $bodyClient .= "<strong>Serie:</strong> {$serialEsc}<br>";
                    $bodyClient .= "<strong>Tip comandă:</strong> " . htmlspecialchars((string) $form['garantie'], ENT_QUOTES, 'UTF-8') . "<br>";
                    $bodyClient .= "<strong>Transport:</strong> {$transportEsc}</p>";
                    $bodyClient .= "<p><strong>Problemă semnalată:</strong><br>{$problemEsc}</p>";
                    $bodyClient .= "<p>Vei fi contactat în cel mai scurt timp pentru pașii următori.</p>";
                    $bodyClient .= "<p>Cu respect,<br>TMSolar Service</p>";
                    $bodyClient .= "<p style='font-size:12px;color:#777'>Acesta este un email automat. Te rugăm să nu răspunzi direct la acest mesaj.</p>";
                    $bodyClient .= "</div>";

                    $clientMailResult = MailHelper::send($form['email'], 'Confirmare comandă online - #' . $orderNumber, $bodyClient, $settings, true);
                    if (!$clientMailResult['success']) {
                        $mailWarning = 'Emailul de confirmare către client nu a putut fi trimis.';
                        $mailWarningReason = trim((string) ($clientMailResult['message'] ?? ''));
                        $this->logPublicOrderIssue('EMAIL_CLIENT_FAIL #' . $orderNumber . ' -> ' . $form['email'] . ': ' . $clientMailResult['message']);
                    }

                    $adminEmails = $this->resolveAdminNotificationEmails($settings);

                    if ($adminEmails !== []) {
                        $emailEsc = htmlspecialchars((string) $form['email'], ENT_QUOTES, 'UTF-8');
                        $phoneEsc = htmlspecialchars((string) $form['phone'], ENT_QUOTES, 'UTF-8');
                        $tipClientEsc = htmlspecialchars((string) ($isCompany ? 'Persoană juridică' : 'Persoană fizică'), ENT_QUOTES, 'UTF-8');
                        $tipComandaEsc = htmlspecialchars((string) $form['garantie'], ENT_QUOTES, 'UTF-8');
                        $judetEsc = htmlspecialchars((string) $form['judet'], ENT_QUOTES, 'UTF-8');
                        $orasEsc = htmlspecialchars((string) $form['oras'], ENT_QUOTES, 'UTF-8');
                        $adresaEsc = htmlspecialchars((string) $form['adresa'], ENT_QUOTES, 'UTF-8');
                        $adminLinkEsc = htmlspecialchars((string) UrlHelper::to('dashboard'), ENT_QUOTES, 'UTF-8');

                        $bodyAdmin = "<div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; font-size: 14px;'>";
                        $bodyAdmin .= "<h2>Comandă nouă primită din formular</h2>";
                        $bodyAdmin .= "<p><strong>Număr comandă:</strong> {$orderNumberEsc}</p>";
                        $bodyAdmin .= "<p><strong>Nume:</strong> {$customerNameEsc}<br>";
                        $bodyAdmin .= "<strong>Email:</strong> {$emailEsc}<br>";
                        $bodyAdmin .= "<strong>Telefon:</strong> {$phoneEsc}</p>";
                        $bodyAdmin .= "<p><strong>Produs:</strong> {$productEsc}<br>";
                        $bodyAdmin .= "<strong>Serial:</strong> {$serialEsc}<br>";
                        $bodyAdmin .= "<strong>Tip client:</strong> {$tipClientEsc}<br>";
                        if ($isCompany) {
                            $bodyAdmin .= "<strong>Nume Firmă:</strong> {$companyEsc}<br><strong>CUI:</strong> {$cuiEsc}<br>";
                        }
                        $bodyAdmin .= "<strong>Tip comandă:</strong> {$tipComandaEsc}<br>";
                        $bodyAdmin .= "<strong>Transport:</strong> {$transportEsc}</p>";
                        $bodyAdmin .= "<p><strong>Problemă:</strong><br>{$problemEsc}</p>";
                        $bodyAdmin .= "<p><strong>Județ:</strong> {$judetEsc}<br><strong>Oraș:</strong> {$orasEsc}<br><strong>Adresă:</strong> {$adresaEsc}</p>";
                        $bodyAdmin .= "<p><a href='{$adminLinkEsc}'>Vezi comanda în panou</a></p>";
                        $bodyAdmin .= "<p style='font-size:12px;color:#777'>Acest email este generat automat de sistemul TMSolar.</p>";
                        $bodyAdmin .= "</div>";

                        $adminSent = false;
                        $adminErrors = [];
                        foreach ($adminEmails as $adminEmail) {
                            $adminMailResult = MailHelper::send($adminEmail, 'Comandă nouă - #' . $orderNumber . ' de la ' . $form['name'], $bodyAdmin, [], true);
                            if ($adminMailResult['success']) {
                                $adminSent = true;
                                continue;
                            }

                            $adminErrors[] = $adminEmail . ': ' . trim((string) ($adminMailResult['message'] ?? 'Eroare necunoscută'));
                            $this->logPublicOrderIssue('EMAIL_ADMIN_FAIL #' . $orderNumber . ' -> ' . $adminEmail . ': ' . $adminMailResult['message']);
                        }

                        if (!$adminSent) {
                            $adminMailWarning = 'Notificarea către administrator nu a putut fi trimisă.';
                            $adminMailWarningReason = $adminErrors !== []
                                ? implode(' | ', $adminErrors)
                                : 'A apărut o eroare necunoscută la trimiterea emailului.';
                        }
                    } else {
                        $adminMailWarning = 'Notificarea către administrator nu a putut fi trimisă.';
                        $adminMailWarningReason = 'Adresa de notificare admin nu este configurată în Setări (Email Suport) și nu există email admin valid în aplicație.';
                        $this->logPublicOrderIssue('EMAIL_ADMIN_FAIL #' . $orderNumber . ': missing admin/support email configuration.');
                    }

                    $success = 'Cererea a fost trimisă cu succes. Număr comandă: ' . $orderNumber;
                    if ($mailWarning !== '') {
                        $success .= ' ' . $mailWarning;
                        if ($mailWarningReason !== '') {
                            $success .= ' Motiv: ' . $mailWarningReason;
                        }
                    }
                    if ($adminMailWarning !== '') {
                        $success .= ' ' . $adminMailWarning;
                        if ($adminMailWarningReason !== '') {
                            $success .= ' Motiv: ' . $adminMailWarningReason;
                        }
                    }

                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json; charset=UTF-8');
                        echo json_encode([
                            'success' => true,
                            'message' => $success,
                            'order_number' => $orderNumber,
                            'mail_warning' => $mailWarning,
                            'mail_warning_reason' => $mailWarningReason,
                            'admin_mail_warning' => $adminMailWarning,
                            'admin_mail_warning_reason' => $adminMailWarningReason,
                            'redirect' => UrlHelper::to('comanda-online/multumim?order=' . urlencode($orderNumber) . (($mailWarning !== '' || $adminMailWarning !== '') ? '&mail_warning=1' : '')),
                        ]);
                        return;
                    }

                    $this->redirect('comanda-online/multumim?order=' . urlencode($orderNumber) . (($mailWarning !== '' || $adminMailWarning !== '') ? '&mail_warning=1' : ''));
                    $form = array_fill_keys(array_keys($form), '');
                } catch (Throwable $exception) {
                    $error = 'Nu s-a putut salva comanda: ' . $exception->getMessage();
                }
            }

            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode([
                    'success' => false,
                    'message' => $error !== '' ? $error : 'Nu s-a putut procesa cererea.',
                ]);
                return;
            }
        }

        $this->view('service-orders/public-form', [
            'pageTitle' => 'Comandă Online',
            'form' => $form,
            'error' => $error,
            'success' => $success,
            'orderNumber' => $orderNumber,
            'recaptchaEnabled' => $recaptchaEnabled,
            'recaptchaSiteKey' => $recaptchaSiteKey,
        ]);
    }

    public function publicThankYou(): void
    {
        $orderNumber = trim((string) ($_GET['order'] ?? ''));
        $mailWarning = (string) ($_GET['mail_warning'] ?? '') === '1';
        $this->view('service-orders/thank-you', [
            'pageTitle' => 'Comandă Online - Confirmare',
            'orderNumber' => $orderNumber,
            'mailWarning' => $mailWarning,
        ]);
    }

    public function index(): void
    {
        $this->requireAuth();
        $search = trim((string) ($_GET['search'] ?? ''));
        $orders = (new ServiceOrder())->listAll($search);
        $customers = (new Customer())->listAll();
        $devices = (new Device())->listAll();
        $this->view('service-orders/index', ['pageTitle' => 'Comenzi Service', 'orders' => $orders, 'customers' => $customers, 'devices' => $devices, 'search' => $search]);
    }

    public function create(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photos = $this->uploadFile('photos', APP_ROOT . 'assets/uploads');
            $documents = $this->uploadFile('documents', APP_ROOT . 'assets/uploads');
            $repairCost = (float) ($_POST['repair_cost'] ?? 0);
            $commandType = trim((string) ($_POST['command_type'] ?? 'Service'));
            $transportType = trim((string) ($_POST['transport_type'] ?? 'Curier'));
            $warrantyRepairDate = trim((string) ($_POST['warranty_repair_date'] ?? ''));
            $labourCost = 0.0;
            $shippingCost = $transportType === 'Curier' ? (float) ($_POST['shipping_cost'] ?? 0) : 0.0;
            $totalCost = $repairCost + $labourCost + $shippingCost;
            $data = [
                ':order_number' => trim((string) ($_POST['order_number'] ?? '')),
                ':received_date' => trim((string) ($_POST['received_date'] ?? '')),
                ':customer_id' => (int) ($_POST['customer_id'] ?? 0),
                ':device_id' => (int) ($_POST['device_id'] ?? 0),
                ':command_type' => $commandType,
                ':transport_type' => $transportType,
                ':accessories' => '',
                ':reported_fault' => trim((string) ($_POST['reported_fault'] ?? '')),
                ':diagnosed_fault' => trim((string) ($_POST['diagnosed_fault'] ?? '')),
                ':repair_notes' => trim((string) ($_POST['repair_notes'] ?? '')),
                ':internal_notes' => trim((string) ($_POST['internal_notes'] ?? '')),
                ':priority' => trim((string) ($_POST['priority'] ?? 'Normal')),
                ':status' => trim((string) ($_POST['status'] ?? 'Noua')),
                ':repair_cost' => $repairCost,
                ':labour_cost' => $labourCost,
                ':shipping_cost' => $shippingCost,
                ':total_cost' => $totalCost,
                ':warranty_repair' => $commandType === 'Service Garantie' ? 1 : 0,
                ':photos' => $photos,
                ':documents' => $documents,
            ];

            $orderId = (new ServiceOrder())->create($data);
            if ((int) $data[':device_id'] > 0) {
                $devicePurchaseDate = $commandType === 'Service Garantie' && $warrantyRepairDate !== '' ? $warrantyRepairDate : null;
                (new Device())->updatePurchaseDate((int) $data[':device_id'], $devicePurchaseDate);
            }
            $customer = (new Customer())->find((int) $data[':customer_id']);
            $customerEmail = trim((string) ($customer['email'] ?? ''));

            if ($customerEmail !== '') {
                $customerName = trim((string) (($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')));
                $body = "Hello " . ($customerName !== '' ? $customerName : 'Customer') . ",\n\n";
                $body .= "Your service order has been created in " . APP_NAME . ".\n";
                $body .= "Order number: " . (string) $data[':order_number'] . "\n";
                $body .= "Status: " . (string) $data[':status'] . "\n";
                $body .= "Received date: " . (string) $data[':received_date'] . "\n";

                $result = MailHelper::send($customerEmail, 'Service order created - ' . (string) $data[':order_number'], $body);
                if (!$result['success']) {
                    $this->logActivity('email_error', 'Service order create email failed for order #' . $orderId . ': ' . $result['message']);
                }
            }

            $this->redirect('service-orders');
        }

        $customers = (new Customer())->listAll();
        $devices = (new Device())->listAll();
        $this->view('service-orders/form', ['pageTitle' => 'Adaugă Comandă Service', 'order' => null, 'customers' => $customers, 'devices' => $devices]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $orderModel = new ServiceOrder();
        $order = $orderModel->find($id);
        if (!$order) {
            $this->redirect('service-orders');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photos = $this->uploadFile('photos', APP_ROOT . 'assets/uploads');
            $documents = $this->uploadFile('documents', APP_ROOT . 'assets/uploads');
            if ($photos === null && !empty($order['photos'])) {
                $photos = $order['photos'];
            }
            if ($documents === null && !empty($order['documents'])) {
                $documents = $order['documents'];
            }
            $repairCost = (float) ($_POST['repair_cost'] ?? 0);
            $commandType = trim((string) ($_POST['command_type'] ?? 'Service'));
            $transportType = trim((string) ($_POST['transport_type'] ?? 'Curier'));
            $warrantyRepairDate = trim((string) ($_POST['warranty_repair_date'] ?? ''));
            $labourCost = 0.0;
            $shippingCost = $transportType === 'Curier' ? (float) ($_POST['shipping_cost'] ?? 0) : 0.0;
            $totalCost = $repairCost + $labourCost + $shippingCost;
            $data = [
                ':order_number' => trim((string) ($_POST['order_number'] ?? '')),
                ':received_date' => trim((string) ($_POST['received_date'] ?? '')),
                ':customer_id' => (int) ($_POST['customer_id'] ?? 0),
                ':device_id' => (int) ($_POST['device_id'] ?? 0),
                ':command_type' => $commandType,
                ':transport_type' => $transportType,
                ':accessories' => '',
                ':reported_fault' => trim((string) ($_POST['reported_fault'] ?? '')),
                ':diagnosed_fault' => trim((string) ($_POST['diagnosed_fault'] ?? '')),
                ':repair_notes' => trim((string) ($_POST['repair_notes'] ?? '')),
                ':internal_notes' => trim((string) ($_POST['internal_notes'] ?? '')),
                ':priority' => trim((string) ($_POST['priority'] ?? 'Normal')),
                ':status' => trim((string) ($_POST['status'] ?? 'Noua')),
                ':repair_cost' => $repairCost,
                ':labour_cost' => $labourCost,
                ':shipping_cost' => $shippingCost,
                ':total_cost' => $totalCost,
                ':warranty_repair' => $commandType === 'Service Garantie' ? 1 : 0,
                ':photos' => $photos,
                ':documents' => $documents,
            ];

            $previousStatus = (string) ($order['status'] ?? '');
            $newStatus = (string) $data[':status'];
            $orderModel->update($id, $data);
            if ((int) $data[':device_id'] > 0) {
                $devicePurchaseDate = $commandType === 'Service Garantie' && $warrantyRepairDate !== '' ? $warrantyRepairDate : null;
                (new Device())->updatePurchaseDate((int) $data[':device_id'], $devicePurchaseDate);
            }

            if ($newStatus !== $previousStatus) {
                $customer = (new Customer())->find((int) $data[':customer_id']);
                $customerEmail = trim((string) ($customer['email'] ?? ''));
                if ($customerEmail !== '') {
                    $customerName = trim((string) (($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')));
                    $body = "Hello " . ($customerName !== '' ? $customerName : 'Customer') . ",\n\n";
                    $body .= "The status of your service order has been updated.\n";
                    $body .= "Order number: " . (string) $data[':order_number'] . "\n";
                    $body .= "Previous status: " . $previousStatus . "\n";
                    $body .= "New status: " . $newStatus . "\n";

                    $result = MailHelper::send($customerEmail, 'Service order status updated - ' . (string) $data[':order_number'], $body);
                    if (!$result['success']) {
                        $this->logActivity('email_error', 'Service order status email failed for order #' . $id . ': ' . $result['message']);
                    }
                }

                $settings = MailHelper::loadSettingsFromDatabase();
                $adminEmail = $this->resolveAdminNotificationEmail($settings);

                $customerName = trim((string) (($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')));
                $adminBody = "<div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; font-size: 14px;'>";
                $adminBody .= "<h2>Actualizare comandă service</h2>";
                $adminBody .= "<p><strong>Număr comandă:</strong> " . htmlspecialchars((string) $data[':order_number'], ENT_QUOTES, 'UTF-8') . "</p>";
                $adminBody .= "<p><strong>Client:</strong> " . htmlspecialchars($customerName !== '' ? $customerName : 'Client', ENT_QUOTES, 'UTF-8') . "<br>";
                $adminBody .= "<strong>Email:</strong> " . htmlspecialchars($customerEmail, ENT_QUOTES, 'UTF-8') . "</p>";
                $adminBody .= "<p><strong>Status anterior:</strong> " . htmlspecialchars($previousStatus, ENT_QUOTES, 'UTF-8') . "<br>";
                $adminBody .= "<strong>Status nou:</strong> " . htmlspecialchars($newStatus, ENT_QUOTES, 'UTF-8') . "</p>";
                $adminBody .= "<p><a href='" . htmlspecialchars((string) UrlHelper::to('service-orders/edit/' . $id), ENT_QUOTES, 'UTF-8') . "'>Deschide comanda în panou</a></p>";
                $adminBody .= "<p style='font-size:12px;color:#777'>Email generat automat de sistemul TMSolar.</p>";
                $adminBody .= "</div>";

                $adminSubject = 'Actualizare comandă - #' . (string) $data[':order_number'];
                if ($newStatus === 'Finalizate') {
                    $adminSubject = 'Comandă finalizată - #' . (string) $data[':order_number'];
                }

                $adminResult = MailHelper::send($adminEmail, $adminSubject, $adminBody, [], true);
                if (!$adminResult['success']) {
                    $this->logActivity('email_error', 'Admin status email failed for order #' . $id . ' to ' . $adminEmail . ': ' . $adminResult['message']);
                }
            }

            $this->redirect('service-orders');
        }

        $customers = (new Customer())->listAll();
        $devices = (new Device())->listAll();
        $this->view('service-orders/form', ['pageTitle' => 'Editează Comandă Service', 'order' => $order, 'customers' => $customers, 'devices' => $devices]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        (new ServiceOrder())->delete($id);
        $this->redirect('service-orders');
    }

    private function findOrCreateManufacturer(string $product): int
    {
        $db = Database::getInstance();
        $manufacturerName = 'Formular Web';

        $statement = $db->prepare('SELECT id FROM manufacturers WHERE name = :name LIMIT 1');
        $statement->execute([':name' => $manufacturerName]);
        $existing = $statement->fetch();
        if ($existing) {
            return (int) $existing['id'];
        }

        return (new Manufacturer())->create([
            ':name' => $manufacturerName,
            ':contact_person' => '',
            ':phone' => '',
            ':email' => '',
            ':website' => '',
        ]);
    }

    private function findOrCreateDeviceModel(int $manufacturerId, string $product): int
    {
        $db = Database::getInstance();

        $statement = $db->prepare('SELECT id FROM device_models WHERE manufacturer_id = :manufacturer_id AND name = :name LIMIT 1');
        $statement->execute([
            ':manufacturer_id' => $manufacturerId,
            ':name' => $product,
        ]);
        $existing = $statement->fetch();
        if ($existing) {
            return (int) $existing['id'];
        }

        return (new DeviceModel())->create([
            ':manufacturer_id' => $manufacturerId,
            ':name' => $product,
            ':description' => 'Model creat automat din formularul public.',
        ]);
    }

    private function generatePublicOrderNumber(PDO $db): string
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $candidate = 'WEB-' . date('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $statement = $db->prepare('SELECT COUNT(*) FROM service_orders WHERE order_number = :order_number');
            $statement->execute([':order_number' => $candidate]);
            if ((int) $statement->fetchColumn() === 0) {
                return $candidate;
            }
        }

        return 'WEB-' . date('YmdHis');
    }

    /**
     * @param array<string, string> $settings
     * @return array{success: bool, message: string}
     */
    private function verifyRecaptcha(string $token, array $settings): array
    {
        if ($token === '') {
            return [
                'success' => false,
                'message' => 'Confirmă reCAPTCHA înainte de trimitere.',
            ];
        }

        $secret = trim((string) ($settings['recaptcha_secret_key'] ?? ''));
        if ($secret === '') {
            return [
                'success' => false,
                'message' => 'reCAPTCHA este activat, dar cheia secretă nu este configurată.',
            ];
        }

        $payload = http_build_query([
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 10,
            ],
        ]);

        $result = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        if ($result === false) {
            return [
                'success' => false,
                'message' => 'Verificarea reCAPTCHA a eșuat. Încearcă din nou.',
            ];
        }

        $json = json_decode($result, true);
        if (!is_array($json) || empty($json['success'])) {
            return [
                'success' => false,
                'message' => 'reCAPTCHA invalid. Reîncearcă.',
            ];
        }

        return ['success' => true, 'message' => 'OK'];
    }

    private function isAjaxRequest(): bool
    {
        $requestedWith = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
        if ($requestedWith === 'xmlhttprequest') {
            return true;
        }

        $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? ''));
        return str_contains($accept, 'application/json');
    }

    private function logPublicOrderIssue(string $message): void
    {
        $logDir = APP_ROOT . 'storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }

        $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        @file_put_contents($logDir . '/public-orders.log', $line, FILE_APPEND);
    }

    /**
     * @param array<string, string> $settings
     */
    private function resolveAdminNotificationEmail(array $settings): string
    {
        $emails = $this->resolveAdminNotificationEmails($settings);
        return $emails[0] ?? '';
    }

    /**
     * @param array<string, string> $settings
     * @return string[]
     */
    private function resolveAdminNotificationEmails(array $settings): array
    {
        $candidateEmails = array_merge(
            [
                trim((string) ($settings['support_email'] ?? '')),
                trim((string) ($settings['mail_from_address'] ?? '')),
            ],
            $this->findAdminUserEmails()
        );

        $unique = [];
        foreach ($candidateEmails as $candidateEmail) {
            if (!$this->isUsableNotificationEmail($candidateEmail)) {
                continue;
            }

            $key = strtolower(trim($candidateEmail));
            if (!isset($unique[$key])) {
                $unique[$key] = trim($candidateEmail);
            }
        }

        return array_values($unique);
    }

    private function findPrimaryAdminUserEmail(): string
    {
        $emails = $this->findAdminUserEmails();
        return $emails[0] ?? '';
    }

    /**
     * @return string[]
     */
    private function findAdminUserEmails(): array
    {
        try {
            $db = Database::getInstance();
            $statement = $db->query("SELECT email FROM users WHERE role = 'admin' ORDER BY (status = 'active') DESC, id ASC");
            $rows = $statement->fetchAll();

            $emails = [];
            foreach ($rows as $row) {
                $email = trim((string) ($row['email'] ?? ''));
                if ($email !== '') {
                    $emails[] = $email;
                }
            }

            return $emails;
        } catch (Throwable $exception) {
            return [];
        }
    }

    private function isUsableNotificationEmail(string $email): bool
    {
        $email = trim($email);
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $lower = strtolower($email);
        $blockedPlaceholders = [
            'support@example.com',
            'your-email@yourdomain.com',
            'no-reply@yourdomain.com',
        ];

        return !in_array($lower, $blockedPlaceholders, true);
    }
}
