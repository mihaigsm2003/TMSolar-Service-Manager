<?php
declare(strict_types=1);

/**
 * Authentication controller for sign-in and sign-out.
 */
class AuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && $userModel->verifyPassword($password, (string) $user['password_hash'])) {
                $_SESSION['user'] = [
                    'id' => (int) $user['id'],
                    'name' => (string) $user['name'],
                    'email' => (string) $user['email'],
                    'role' => (string) $user['role'],
                ];

                header('Location: ' . UrlHelper::to('dashboard'));
                exit;
            }

            $this->view('auth/login', ['error' => 'Date de autentificare invalide.']);
            return;
        }

        $this->view('auth/login');
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: ' . UrlHelper::to('login'));
        exit;
    }
}
