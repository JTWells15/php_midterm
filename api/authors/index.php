<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/Author.php';

$database = new Database();
$db = $database->getConnection();

$author = new Author($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if ($id) {
            $author->id = $id;
            $result = $author->readSingle();
            $row = $result->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                echo json_encode($row);
            } else {
                echo json_encode(['message' => 'No Authors Found']);
            }
        } else {
            $result = $author->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $authors_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $authors_arr[] = $row;
                }

                echo json_encode($authors_arr);
            } else {
                echo json_encode(['message' => 'No Authors Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->author) || empty(trim($data->author))) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $author->author = $data->author;

        if ($author->create()) {
            echo json_encode([
                'id' => (int) $author->id,
                'author' => $author->author
            ]);
        } else {
            echo json_encode(['message' => 'Author Not Created']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id) || !isset($data->author) || empty(trim($data->author))) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $author->id = (int) $data->id;

        if (!$author->exists()) {
            echo json_encode(['message' => 'No Authors Found']);
            break;
        }

        $author->author = $data->author;

        if ($author->update()) {
            echo json_encode([
                'id' => (int) $author->id,
                'author' => $author->author
            ]);
        } else {
            echo json_encode(['message' => 'Author Not Updated']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $author->id = (int) $data->id;

        if (!$author->exists()) {
            echo json_encode(['message' => 'No Authors Found']);
            break;
        }

        if ($author->delete()) {
            echo json_encode(['id' => (int) $author->id]);
        } else {
            echo json_encode(['message' => 'Author Not Deleted']);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
?>