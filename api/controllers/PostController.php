<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../models/Post.php';
include_once '../utils/JwtHandler.php';

$database = new Database();
$db = $database->getConnection();

$post = new Post($db);
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

$post->user_id = $decoded['id'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $post->read();
        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = $row;
        }
        http_response_code(200);
        echo json_encode($posts);
        break;

    case 'POST':
        if (!empty($data->title) && !empty($data->content)) {
            $post->title = $data->title;
            $post->content = $data->content;

            if ($post->create()) {
                http_response_code(201);
                echo json_encode(['status' => 1, 'message' => 'Post created']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 0, 'message' => 'Post creation failed']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 0, 'message' => 'Invalid input']);
        }
        break;

    case 'PUT':
        if (!empty($data->id) && !empty($data->title) && !empty($data->content)) {
            $post->id = $data->id;
            $post->title = $data->title;
            $post->content = $data->content;

            if ($post->update()) {
                http_response_code(200);
                echo json_encode(['status' => 1, 'message' => 'Post updated']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 0, 'message' => 'Post update failed']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 0, 'message' => 'Invalid input']);
        }
        break;

    case 'DELETE':
        if (!empty($data->id)) {
            $post->id = $data->id;

            if ($post->delete()) {
                http_response_code(200);
                echo json_encode(['status' => 1, 'message' => 'Post deleted']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 0, 'message' => 'Post deletion failed']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 0, 'message' => 'Invalid input']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 0, 'message' => 'Method not allowed']);
        break;
}
?>