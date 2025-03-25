<?php
require 'api/config/Database.php';
require 'api/utils/JwtHandler.php';

$url = $_GET['url'] ?? '';

switch ($url) {
    case 'login':
        require 'api/controllers/AuthController.php';
        break;
    case 'users':
        require 'api/controllers/UserController.php';
        break;
    case 'posts':
        require 'api/controllers/PostController.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['status' => 0, 'message' => 'Not found']);
        break;
}
?>