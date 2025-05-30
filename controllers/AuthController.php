<?php
    require_once dirname(__DIR__) . '/config/db.php';

    class AuthController {
        private $conn;

        public function __construct(){
            $db = new Database();
            $this->conn = $db->conn;
        }

        public function login($username, $password) {
            $username = $this->conn->real_escape_string($username);

            $sql = "SELECT id, username, role, password FROM users WHERE username = '$username'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    unset($user['password']);
                    return ['status' => 'success', 'user' => $user];
                }
            }

            return ['status' => 'error', 'message' => 'Username atau Password Salah!'];
        }
    }
?>