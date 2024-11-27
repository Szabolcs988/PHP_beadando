<?php
require_once './models/Database.php';

class MenuController {
    public function getMenuItems($userRole = null) {
        try {
            $database = new Database();
            $dbh = $database->getConnection();

            // SQL lekérdezés a menüpontokhoz
            $sql = "SELECT * FROM menuk WHERE (jogosultsag IS NULL OR jogosultsag = :userRole) ORDER BY sorrend";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':userRole', $userRole, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Hiba: " . $e->getMessage() . "</p>";
        }
    }
}
?>
