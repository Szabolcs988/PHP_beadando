<?php
require_once './models/Database.php';
class MenuController {
    public function getMenuItems($userRole) {
        try {
            $database = new Database();
            $dbh = $database->getConnection();

            // SQL lekérdezés a menüpontokhoz
            if ($userRole === 'admin') {
                $sql = "SELECT * FROM menu ORDER BY sorrend";
            } elseif ($userRole === 'regisztrált') {
                $sql = "SELECT * FROM menu WHERE jogosultsag IS NULL OR jogosultsag = 'regisztrált' ORDER BY sorrend";
            } else {
                $sql = "SELECT * FROM menu WHERE jogosultsag IS NULL ORDER BY sorrend";
            }

            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Hiba: " . $e->getMessage() . "</p>";
        }
    }
}
?>
