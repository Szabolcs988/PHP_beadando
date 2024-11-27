<?php
// controllers/SOAPController.php
require_once './models/Database.php';

class SOAPController {
    public function getInstallationsWithDetails() {
        try {
            $database = new Database();
            $dbh = $database->getConnection();

            $sql = "SELECT telepites.id, gep.hely, gep.tipus, szoftver.nev, szoftver.kategoria, telepites.verzio, telepites.datum 
                    FROM telepites 
                    JOIN gep ON telepites.gepid = gep.id 
                    JOIN szoftver ON telepites.szoftverid = szoftver.id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Hiba: " . $e->getMessage() . "</p>";
        }
    }
}
?>
