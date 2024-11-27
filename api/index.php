<?php
require_once '../controllers/RESTController.php';
header("Content-Type: application/json");

$controller = new RESTController();
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch($requestMethod) {
    case 'GET':
        $controller->getGep();
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->createGep($data);
        break;

    case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true); // JSON dekódolás
    var_dump($data); // Ellenőrzi a dekódolt adatokat

    $id = $_GET['id'] ?? null;
    if ($id) {
        $controller->updateGep($id, $data);
    } else {
        echo json_encode(["message" => "ID szükséges a PUT kéréshez"]);
    }
    break;


    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->deleteGep($id);
        } else {
            echo json_encode(["message" => "ID szükséges a DELETE kéréshez"]);
        }
        break;

    default:
        echo json_encode(["message" => "Módszer nem támogatott"]);
        break;
}
?>
