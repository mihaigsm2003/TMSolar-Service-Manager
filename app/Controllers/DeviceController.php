<?php
declare(strict_types=1);

class DeviceController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $search = trim((string) ($_GET['search'] ?? ''));
        $devices = (new Device())->listAll($search);
        $customers = (new Customer())->listAll();
        $manufacturers = (new Manufacturer())->listAll();
        $models = (new DeviceModel())->listAll();
        $this->view('devices/index', ['pageTitle' => 'Devices', 'devices' => $devices, 'customers' => $customers, 'manufacturers' => $manufacturers, 'models' => $models, 'search' => $search]);
    }

    public function create(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photo = $this->uploadFile('photo', APP_ROOT . 'assets/uploads');
            $data = [
                ':customer_id' => (int) ($_POST['customer_id'] ?? 0),
                ':manufacturer_id' => (int) ($_POST['manufacturer_id'] ?? 0),
                ':model_id' => (int) ($_POST['model_id'] ?? 0),
                ':serial_number' => trim((string) ($_POST['serial_number'] ?? '')),
                ':warranty' => isset($_POST['warranty']) ? 1 : 0,
                ':purchase_date' => trim((string) ($_POST['purchase_date'] ?? '')),
                ':photo' => $photo,
            ];
            (new Device())->create($data);
            $this->redirect('devices');
        }

        $customers = (new Customer())->listAll();
        $manufacturers = (new Manufacturer())->listAll();
        $models = (new DeviceModel())->listAll();
        $this->view('devices/form', ['pageTitle' => 'Add Device', 'device' => null, 'customers' => $customers, 'manufacturers' => $manufacturers, 'models' => $models]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $deviceModel = new Device();
        $device = $deviceModel->find($id);
        if (!$device) {
            $this->redirect('devices');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photo = $this->uploadFile('photo', APP_ROOT . 'assets/uploads');
            if ($photo === null && !empty($device['photo'])) {
                $photo = $device['photo'];
            }
            $data = [
                ':customer_id' => (int) ($_POST['customer_id'] ?? 0),
                ':manufacturer_id' => (int) ($_POST['manufacturer_id'] ?? 0),
                ':model_id' => (int) ($_POST['model_id'] ?? 0),
                ':serial_number' => trim((string) ($_POST['serial_number'] ?? '')),
                ':warranty' => isset($_POST['warranty']) ? 1 : 0,
                ':purchase_date' => trim((string) ($_POST['purchase_date'] ?? '')),
                ':photo' => $photo,
            ];
            $deviceModel->update($id, $data);
            $this->redirect('devices');
        }

        $customers = (new Customer())->listAll();
        $manufacturers = (new Manufacturer())->listAll();
        $models = (new DeviceModel())->listAll();
        $this->view('devices/form', ['pageTitle' => 'Edit Device', 'device' => $device, 'customers' => $customers, 'manufacturers' => $manufacturers, 'models' => $models]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        (new Device())->delete($id);
        $this->redirect('devices');
    }
}
