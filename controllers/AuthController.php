<?php
    require_once __DIR__ . '/../models/User.php';

    class AuthController {
        public function register() {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input['name'], $input['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Isi semua data!']);
                return;
            }

            $userModel      = new User();
            $existingUser   = $userModel->findByName($input['name']);

            if ($existingUser) {
                http_response_code(400);
                echo json_encode(['error' => 'User sudah ada!']);
                return;
            }

            $role           = 'kasir';
            $hashedPassword = password_hash($input['password'], PASSWORD_BCRYPT);
            $userModel->create($input['name'], $hashedPassword, $role);

            echo json_encode(['message' => 'Registration Successfully']);
        }

        public function login() {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input['name'], $input['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Data tidak lengkap!']);
                return;
            }

            $userModel = new User();
            $user = $userModel->findByName($input['name']);

            if (!$user || !password_verify($input['password'], $user['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Username atau Password salah!']);
                return;
            }

            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['name'] = $user['name'];

            echo json_encode([
                'message' => 'Login Success',
                'user' => [
                    'id' => $user['userId'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                ]
            ]);
        }

        public function logout() {
            session_destroy();
            echo json_encode(['message' => 'Logout Successfully']);
        }
    }
?>