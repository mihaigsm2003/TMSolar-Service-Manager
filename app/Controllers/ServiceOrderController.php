<?php
declare(strict_types=1);

class ServiceOrderController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $search = trim((string) ($_GET['search'] ?? ''));
        $orders = (new ServiceOrder())->listAll($search);
        $customers = (new Customer())->listAll();
        $devices = (new Device())->listAll();
        $this->view('service-orders/index', ['pageTitle' => 'Service Orders', 'orders' => $orders, 'customers' => $customers, 'devices' => $devices, 'search' => $search]);
    }

    public function create(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photos = $this->uploadFile('photos', APP_ROOT . 'assets/uploads');
            $documents = $this->uploadFile('documents', APP_ROOT . 'assets/uploads');
            $repairCost = (float) ($_POST['repair_cost'] ?? 0);
            $labourCost = (float) ($_POST['labour_cost'] ?? 0);
            $shippingCost = (float) ($_POST['shipping_cost'] ?? 0);
            $totalCost = $repairCost + $labourCost + $shippingCost;
            $data = [
                ':order_number' => trim((string) ($_POST['order_number'] ?? '')),
                ':received_date' => trim((string) ($_POST['received_date'] ?? '')),
                ':customer_id' => (int) ($_POST['customer_id'] ?? 0),
                ':device_id' => (int) ($_POST['device_id'] ?? 0),
                ':accessories' => trim((string) ($_POST['accessories'] ?? '')),
                ':reported_fault' => trim((string) ($_POST['reported_fault'] ?? '')),
                ':diagnosed_fault' => trim((string) ($_POST['diagnosed_fault'] ?? '')),
                ':repair_notes' => trim((string) ($_POST['repair_notes'] ?? '')),
                ':internal_notes' => trim((string) ($_POST['internal_notes'] ?? '')),
                ':priority' => trim((string) ($_POST['priority'] ?? 'Normal')),
                ':status' => trim((string) ($_POST['status'] ?? 'Received')),
                ':repair_cost' => $repairCost,
                ':labour_cost' => $labourCost,
                ':shipping_cost' => $shippingCost,
                ':total_cost' => $totalCost,
                ':warranty_repair' => isset($_POST['warranty_repair']) ? 1 : 0,
                ':photos' => $photos,
                ':documents' => $documents,
            ];
            (new ServiceOrder())->create($data);
            $this->redirect('service-orders');
        }

        $customers = (new Customer())->listAll();
        $devices = (new Device())->listAll();
        $this->view('service-orders/form', ['pageTitle' => 'Add Service Order', 'order' => null, 'customers' => $customers, 'devices' => $devices]);
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
            $labourCost = (float) ($_POST['labour_cost'] ?? 0);
            $shippingCost = (float) ($_POST['shipping_cost'] ?? 0);
            $totalCost = $repairCost + $labourCost + $shippingCost;
            $data = [
                ':order_number' => trim((string) ($_POST['order_number'] ?? '')),
                ':received_date' => trim((string) ($_POST['received_date'] ?? '')),
                ':customer_id' => (int) ($_POST['customer_id'] ?? 0),
                ':device_id' => (int) ($_POST['device_id'] ?? 0),
                ':accessories' => trim((string) ($_POST['accessories'] ?? '')),
                ':reported_fault' => trim((string) ($_POST['reported_fault'] ?? '')),
                ':diagnosed_fault' => trim((string) ($_POST['diagnosed_fault'] ?? '')),
                ':repair_notes' => trim((string) ($_POST['repair_notes'] ?? '')),
                ':internal_notes' => trim((string) ($_POST['internal_notes'] ?? '')),
                ':priority' => trim((string) ($_POST['priority'] ?? 'Normal')),
                ':status' => trim((string) ($_POST['status'] ?? 'Received')),
                ':repair_cost' => $repairCost,
                ':labour_cost' => $labourCost,
                ':shipping_cost' => $shippingCost,
                ':total_cost' => $totalCost,
                ':warranty_repair' => isset($_POST['warranty_repair']) ? 1 : 0,
                ':photos' => $photos,
                ':documents' => $documents,
            ];
            $orderModel->update($id, $data);
            $this->redirect('service-orders');
        }

        $customers = (new Customer())->listAll();
        $devices = (new Device())->listAll();
        $this->view('service-orders/form', ['pageTitle' => 'Edit Service Order', 'order' => $order, 'customers' => $customers, 'devices' => $devices]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        (new ServiceOrder())->delete($id);
        $this->redirect('service-orders');
    }
}
