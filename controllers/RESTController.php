<?php
require_once '../models/Database.php';
header("Content-Type: application/json");

class RESTController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // GET request: összes gép listázása
    public function getGep() {
        $query = "SELECT * FROM gep";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    }

    // POST request: új gép hozzáadása
    public function createGep($data) {
        $query = "INSERT INTO gep (hely, tipus, ipcim) VALUES (:hely, :tipus, :ipcim)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":hely", $data['hely']);
        $stmt->bindParam(":tipus", $data['tipus']);
        $stmt->bindParam(":ipcim", $data['ipcim']);
        $stmt->execute();
        echo json_encode(["message" => "Gép hozzáadva"]);
    }

    // PUT request: gép frissítése
   public function updateGep($id, $data) {
    $query = "UPDATE gep SET hely = :hely, tipus = :tipus, ipcim = :ipcim WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":hely", $data['hely']);
    $stmt->bindParam(":tipus", $data['tipus']);
    $stmt->bindParam(":ipcim", $data['ipcim']);
    $stmt->bindParam(":id", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["message" => "Gép frissítve"]);
    } else {
        echo json_encode(["message" => "Sikertelen frissítés"]);
    }
}


    // DELETE request: gép törlése
    public function deleteGep($id) {
        $query = "DELETE FROM gep WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        echo json_encode(["message" => "Gép törölve"]);
    }
}
?>
