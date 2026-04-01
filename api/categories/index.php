<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/Category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if ($id) {
            $category->id = $id;
            $result = $category->readSingle();
            $row = $result->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                echo json_encode($row);
            } else {
                echo json_encode(['message' => 'No Categories Found']);
            }
        } else {
            $result = $category->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $categories_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $categories_arr[] = $row;
                }

                echo json_encode($categories_arr);
            } else {
                echo json_encode(['message' => 'No Categories Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->category) || empty(trim($data->category))) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $category->category = $data->category;

        if ($category->create()) {
            echo json_encode([
                'id' => (int) $category->id,
                'category' => $category->category
            ]);
        } else {
            echo json_encode(['message' => 'Category Not Created']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id) || !isset($data->category) || empty(trim($data->category))) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $category->id = (int) $data->id;

        if (!$category->exists()) {
            echo json_encode(['message' => 'No Categories Found']);
            break;
        }

        $category->category = $data->category;

        if ($category->update()) {
            echo json_encode([
                'id' => (int) $category->id,
                'category' => $category->category
            ]);
        } else {
            echo json_encode(['message' => 'Category Not Updated']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $category->id = (int) $data->id;

        if (!$category->exists()) {
            echo json_encode(['message' => 'No Categories Found']);
            break;
        }

        if ($category->delete()) {
            echo json_encode(['id' => (int) $category->id]);
        } else {
            echo json_encode(['message' => 'Category Not Deleted']);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
?>