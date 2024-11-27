<?php

require_once 'models/Database.php';

class RestClient {
    private $apiUrl; // API URL a REST hívásokhoz
    private $conn;   // Adatbázis kapcsolat

    public function __construct($url) {
        $this->apiUrl = $url;

        // Adatbázis kapcsolat inicializálása
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($this->conn === null) {
            throw new Exception("Adatbázis kapcsolat sikertelen");
        }
    }

    // GET kérés: az összes gép lekérése API-n keresztül
    public function getGep() {
        $response = file_get_contents($this->apiUrl);
        if ($response === false) {
            throw new Exception("GET kérés sikertelen: " . $this->apiUrl);
        }
        return json_decode($response, true);
    }

    // POST kérés: új gép hozzáadása az adatbázishoz
    public function createGep($data) {
        if (!isset($data['hely'], $data['tipus'], $data['ipcim'])) {
            throw new Exception("Hiányzó adatok: 'hely', 'tipus', 'ipcim'");
        }

        $query = "INSERT INTO gep (hely, tipus, ipcim) VALUES (:hely, :tipus, :ipcim)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":hely", $data['hely']);
        $stmt->bindParam(":tipus", $data['tipus']);
        $stmt->bindParam(":ipcim", $data['ipcim']);

        if ($stmt->execute()) {
            return json_encode(["message" => "Gép hozzáadva"]);
        } else {
            throw new Exception("Hiba történt az adatbázis művelet során");
        }
    }

    // PUT kérés: gép frissítése API-n keresztül
    public function updateGep($id, $data) {
        if (!$id) {
            throw new Exception("ID hiányzik a PUT kéréshez");
        }

        $options = [
            "http" => [
                "header" => "Content-Type: application/json\r\n",
                "method" => "PUT",
                "content" => json_encode($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($this->apiUrl . "?id=" . $id, false, $context);

        if ($response === false) {
            throw new Exception("PUT kérés sikertelen: " . $this->apiUrl . "?id=" . $id);
        }

        return json_decode($response, true);
    }

    // DELETE kérés: gép törlése API-n keresztül
    public function deleteGep($id) {
        if (!$id) {
            throw new Exception("ID hiányzik a DELETE kéréshez");
        }

        $options = [
            "http" => [
                "method" => "DELETE"
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($this->apiUrl . "?id=" . $id, false, $context);

        if ($response === false) {
            throw new Exception("DELETE kérés sikertelen: " . $this->apiUrl . "?id=" . $id);
        }

        return json_decode($response, true);
    }
}
