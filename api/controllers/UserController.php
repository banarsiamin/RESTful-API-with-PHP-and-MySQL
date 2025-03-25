<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
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

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = null;

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1];
}

if (!$token) {
    http_response_code(401);
    echo json_encode(['status' => 0, 'message' => 'Access denied']);
    exit;
}

$decoded = $jwt->decode($token);

if (!$decoded) {
    http_response_code(401);
    echo json_encode(['status' => 0, 'message' => 'Invalid token']);
    exit;
}

$user->id = $decoded['id'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if ($decoded['user_type'] === 'admin') {
            $stmt = $user->read();
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
            http_response_code(200);
            echo json_encode($users);
        } else {
            http_response_code(403);
            echo json_encode(['status' => 0, 'message' => 'Access denied']);
        }
        break;

    case 'POST':
        if ($decoded['user_type'] === 'admin') {
            if (!empty($data->username) && !empty($data->password) && !empty($data->email)) {
                $user->username = $data->username;
                $user->password = $data->password;
                $user->email = $data->email;
                $user->user_type = $data->user_type ?? 'user';

                if ($user->create()) {
                    http_response_code(201);
                    echo json_encode(['status' => 1, 'message' => 'User created']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 0, 'message' => 'User creation failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['status' => 0, 'message' => 'Invalid input']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['status' => 0, 'message' => 'Access denied']);
        }
        break;

    case 'PUT':
        if (!empty($data->username) && !empty($data->email) && !empty($data->password)) {
            $user->username = $data->username;
            $user->email = $data->email;
            $user->password = $data->password;

            if ($user->update()) {
                http_response_code(200);
                echo json_encode(['status' => 1, 'message' => 'User updated']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 0, 'message' => 'User update failed']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 0, 'message' => 'Invalid input']);
        }
        break;

    case 'DELETE':
        if ($decoded['user_type'] === 'admin') {
            if (!empty($data->id)) {
                $user->id = $data->id;

                if ($user->delete()) {
                    http_response_code(200);
                    echo json_encode(['status' => 1, 'message' => 'User deleted']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 0, 'message' => 'User deletion failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['status' => 0, 'message' => 'Invalid input']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['status' => 0, 'message' => 'Access denied']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 0, 'message' => 'Method not allowed']);
        break;
}
?>