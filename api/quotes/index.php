<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/Quote.php';
include_once '../models/Author.php';
include_once '../models/Category.php';

$database = new Database();
$db = $database->getConnection();

$quote = new Quote($db);
$author = new Author($db);
$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $author_id = isset($_GET['author_id']) ? (int) $_GET['author_id'] : null;
        $category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;

        if ($id) {
            $quote->id = $id;
            $result = $quote->readSingle();
            $row = $result->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                echo json_encode($row);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        } elseif ($author_id && $category_id) {
            $quote->author_id = $author_id;
            $quote->category_id = $category_id;
            $result = $quote->readByAuthorAndCategory();
            $num = $result->rowCount();

            if ($num > 0) {
                $quotes_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $quotes_arr[] = $row;
                }

                echo json_encode($quotes_arr);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        } elseif ($author_id) {
            $quote->author_id = $author_id;
            $result = $quote->readByAuthor();
            $num = $result->rowCount();

            if ($num > 0) {
                $quotes_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $quotes_arr[] = $row;
                }

                echo json_encode($quotes_arr);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        } elseif ($category_id) {
            $quote->category_id = $category_id;
            $result = $quote->readByCategory();
            $num = $result->rowCount();

            if ($num > 0) {
                $quotes_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $quotes_arr[] = $row;
                }

                echo json_encode($quotes_arr);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        } else {
            $result = $quote->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $quotes_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $quotes_arr[] = $row;
                }

                echo json_encode($quotes_arr);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (
            !isset($data->quote) || empty(trim($data->quote)) ||
            !isset($data->author_id) ||
            !isset($data->category_id)
        ) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $author->id = (int) $data->author_id;
        $category->id = (int) $data->category_id;

        if (!$author->exists()) {
            echo json_encode(['message' => 'author_id Not Found']);
            break;
        }

        if (!$category->exists()) {
            echo json_encode(['message' => 'category_id Not Found']);
            break;
        }

        $quote->quote = $data->quote;
        $quote->author_id = (int) $data->author_id;
        $quote->category_id = (int) $data->category_id;

        if ($quote->create()) {
            echo json_encode([
                'id' => (int) $quote->id,
                'quote' => $quote->quote,
                'author_id' => (int) $quote->author_id,
                'category_id' => (int) $quote->category_id
            ]);
        } else {
            echo json_encode(['message' => 'Quote Not Created']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (
            !isset($data->id) ||
            !isset($data->quote) || empty(trim($data->quote)) ||
            !isset($data->author_id) ||
            !isset($data->category_id)
        ) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $quote->id = (int) $data->id;

        if (!$quote->exists()) {
            echo json_encode(['message' => 'No Quotes Found']);
            break;
        }

        $author->id = (int) $data->author_id;
        $category->id = (int) $data->category_id;

        if (!$author->exists()) {
            echo json_encode(['message' => 'author_id Not Found']);
            break;
        }

        if (!$category->exists()) {
            echo json_encode(['message' => 'category_id Not Found']);
            break;
        }

        $quote->quote = $data->quote;
        $quote->author_id = (int) $data->author_id;
        $quote->category_id = (int) $data->category_id;

        if ($quote->update()) {
            echo json_encode([
                'id' => (int) $quote->id,
                'quote' => $quote->quote,
                'author_id' => (int) $quote->author_id,
                'category_id' => (int) $quote->category_id
            ]);
        } else {
            echo json_encode(['message' => 'Quote Not Updated']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $quote->id = (int) $data->id;

        if (!$quote->exists()) {
            echo json_encode(['message' => 'No Quotes Found']);
            break;
        }

        if ($quote->delete()) {
            echo json_encode(['id' => (int) $quote->id]);
        } else {
            echo json_encode(['message' => 'Quote Not Deleted']);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
?>