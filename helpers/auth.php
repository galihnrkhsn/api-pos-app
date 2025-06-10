<?php
    function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    function requireAuth() {
        if (!isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['error' => 'Login Require!']);
            exit;
        }
    }
?>