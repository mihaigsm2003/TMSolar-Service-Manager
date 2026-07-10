<?php
declare(strict_types=1);

class DeviceModelController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $search = trim((string) ($_GET['search'] ?? ''));
        $models = (new DeviceModel())->listAll($search);
        $manufacturers = (new Manufacturer())->listAll();
        $this->view('device-models/index', ['pageTitle' => 'Modele Dispozitive', 'models' => $models, 'manufacturers' => $manufacturers, 'search' => $search]);
    }

    public function create(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':manufacturer_id' => (int) ($_POST['manufacturer_id'] ?? 0),
                ':name' => trim((string) ($_POST['name'] ?? '')),
                ':description' => trim((string) ($_POST['description'] ?? '')),
            ];
            (new DeviceModel())->create($data);
            $this->redirect('device-models');
        }

        $manufacturers = (new Manufacturer())->listAll();
        $this->view('device-models/form', ['pageTitle' => 'Adaugă Model Dispozitiv', 'model' => null, 'manufacturers' => $manufacturers]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $model = new DeviceModel();
        $deviceModel = $model->find($id);
        if (!$deviceModel) {
            $this->redirect('device-models');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':manufacturer_id' => (int) ($_POST['manufacturer_id'] ?? 0),
                ':name' => trim((string) ($_POST['name'] ?? '')),
                ':description' => trim((string) ($_POST['description'] ?? '')),
            ];
            $model->update($id, $data);
            $this->redirect('device-models');
        }

        $manufacturers = (new Manufacturer())->listAll();
        $this->view('device-models/form', ['pageTitle' => 'Editează Model Dispozitiv', 'model' => $deviceModel, 'manufacturers' => $manufacturers]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        (new DeviceModel())->delete($id);
        $this->redirect('device-models');
    }
}
