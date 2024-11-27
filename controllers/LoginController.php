<?php
// controllers/LoginController.php
require_once './models/Database.php';

class LoginController {
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($username && $password) {
                try {
                    $database = new Database();
                    $dbh = $database->getConnection();

                    // Felhasználó lekérdezése az adatbázisból
                    $sql = "SELECT * FROM users WHERE username = :username";
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Jelszó ellenőrzés
                    if ($user && password_verify($password, $user['password'])) {
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_role'] = $user['jogosultsag'];
                        // Újratöltés az index.php menüsor frissítése érdekében
                        header('Location: index.php?page=Home');
                        exit;
                    } else {
                        echo "<p>Hibás felhasználónév vagy jelszó!</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Hiba: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p>Kérjük, töltse ki az összes mezőt!</p>";
            }
        }
    }
}
?>