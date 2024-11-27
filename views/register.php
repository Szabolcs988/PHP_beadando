<?php include 'header.php'; ?>
<section id="main">
    <header>
        <h2>Register</h2>
    </header>
    <form action="index.php?page=Register" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</section>

<?php
require_once './controllers/RegisterController.php';
$registerController = new RegisterController();
$registerController->handleRegister();
?>

<?php include 'footer.php'; ?>
