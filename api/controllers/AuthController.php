<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../models/User.php';
include_once '../utils/JwtHandler.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$jwt = new JwtHandler();

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($data->username) && !empty($data->password)) {
        $user->username = $data->username;
        $user->password = $data->password;

        if ($user->login()) {
            $token = $jwt->encode([
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'user_type' => $user->user_type
            ]);

            http_response_code(200);
            echo json_encode([
                'status' => 1,
                'message' => 'Login successful',
                'token' => $token
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid credentials'
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 0,
            'message' => 'Invalid input'
        ]);
    }
}
?>