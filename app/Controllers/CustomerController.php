<?php
declare(strict_types=1);

class CustomerController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $search = trim((string) ($_GET['search'] ?? ''));
        $customers = (new Customer())->listAll($search);
        $this->view('customers/index', ['pageTitle' => 'Customers', 'customers' => $customers, 'search' => $search]);
    }

    public function create(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':first_name' => trim((string) ($_POST['first_name'] ?? '')),
                ':last_name' => trim((string) ($_POST['last_name'] ?? '')),
                ':phone' => trim((string) ($_POST['phone'] ?? '')),
                ':email' => trim((string) ($_POST['email'] ?? '')),
                ':company' => trim((string) ($_POST['company'] ?? '')),
                ':vat_number' => trim((string) ($_POST['vat_number'] ?? '')),
                ':address' => trim((string) ($_POST['address'] ?? '')),
            ];
            (new Customer())->create($data);
            $this->redirect('customers');
        }

        $this->view('customers/form', ['pageTitle' => 'Add Customer', 'customer' => null]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $customerModel = new Customer();
        $customer = $customerModel->find($id);
        if (!$customer) {
            $this->redirect('customers');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':first_name' => trim((string) ($_POST['first_name'] ?? '')),
                ':last_name' => trim((string) ($_POST['last_name'] ?? '')),
                ':phone' => trim((string) ($_POST['phone'] ?? '')),
                ':email' => trim((string) ($_POST['email'] ?? '')),
                ':company' => trim((string) ($_POST['company'] ?? '')),
                ':vat_number' => trim((string) ($_POST['vat_number'] ?? '')),
                ':address' => trim((string) ($_POST['address'] ?? '')),
            ];
            $customerModel->update($id, $data);
            $this->redirect('customers');
        }

        $this->view('customers/form', ['pageTitle' => 'Edit Customer', 'customer' => $customer]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        (new Customer())->delete($id);
        $this->redirect('customers');
    }
}
