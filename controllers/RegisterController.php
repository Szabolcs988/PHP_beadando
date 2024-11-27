<?php
require_once './models/Database.php';

class RegisterController {
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($username && $password) {
                try {
                    $database = new Database();
                    $dbh = $database->getConnection();

                    // Jelszó hashelés
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Felhasználó beszúrása
                    $sql = "INSERT INTO users (username, password, jogosultsag) VALUES (:username, :password, 'regisztrált')";
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        echo "<p>Sikeres regisztráció!</p>";
                    } else {
                        echo "<p>Sikertelen regisztráció! Próbálkozzon újra.</p>";
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
