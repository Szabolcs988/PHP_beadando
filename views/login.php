<?php include 'header.php'; ?>
<section id="main">
    <header>
        <h2>Login</h2>
    </header>
    <form action="index.php?page=Login" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</section>

<?php
require_once './controllers/LoginController.php';
$loginController = new LoginController();
$loginController->handleLogin();
?>

<?php include 'footer.php'; ?>
