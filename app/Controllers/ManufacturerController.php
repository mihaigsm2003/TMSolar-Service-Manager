<?php
declare(strict_types=1);

class ManufacturerController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $search = trim((string) ($_GET['search'] ?? ''));
        $manufacturers = (new Manufacturer())->listAll($search);
        $this->view('manufacturers/index', ['pageTitle' => 'Manufacturers', 'manufacturers' => $manufacturers, 'search' => $search]);
    }

    public function create(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':name' => trim((string) ($_POST['name'] ?? '')),
                ':contact_person' => trim((string) ($_POST['contact_person'] ?? '')),
                ':phone' => trim((string) ($_POST['phone'] ?? '')),
                ':email' => trim((string) ($_POST['email'] ?? '')),
                ':website' => trim((string) ($_POST['website'] ?? '')),
            ];
            (new Manufacturer())->create($data);
            $this->redirect('manufacturers');
        }

        $this->view('manufacturers/form', ['pageTitle' => 'Add Manufacturer', 'manufacturer' => null]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $manufacturerModel = new Manufacturer();
        $manufacturer = $manufacturerModel->find($id);
        if (!$manufacturer) {
            $this->redirect('manufacturers');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':name' => trim((string) ($_POST['name'] ?? '')),
                ':contact_person' => trim((string) ($_POST['contact_person'] ?? '')),
                ':phone' => trim((string) ($_POST['phone'] ?? '')),
                ':email' => trim((string) ($_POST['email'] ?? '')),
                ':website' => trim((string) ($_POST['website'] ?? '')),
            ];
            $manufacturerModel->update($id, $data);
            $this->redirect('manufacturers');
        }

        $this->view('manufacturers/form', ['pageTitle' => 'Edit Manufacturer', 'manufacturer' => $manufacturer]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        (new Manufacturer())->delete($id);
        $this->redirect('manufacturers');
    }
}
