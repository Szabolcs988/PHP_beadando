<!-- views/header.php -->
<?php
require_once './controllers/MenuController.php';

$menuController = new MenuController();
$userRole = $_SESSION['user_role'] ?? 'látogató'; // Felhasználói jogosultság megállapítása, ha nincs bejelentkezve, látogató
$menuItems = $menuController->getMenuItems($userRole);
if (!$menuItems) {
    $menuItems = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <title>Szoftverleltár Rendszer</title>
    <link rel="stylesheet" href="/assets/twenty/assets/css/main.css">
	<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body>

<header id="header">
    <h1 id="logo"><a href="index.php?page=Home">Szoftverleltár Rendszer</a></h1>
    <nav id="nav">
        <ul>
            <li class="current">
                <a href="index.php?page=Home">Home</a>
                <?php if ($userRole !== 'látogató'): ?>
                    <ul>
                        <li><a href="index.php?page=Mnb">MNB</a></li>
                        <li><a href="index.php?page=Pdf">PDF</a></li>
                        <li><a href="index.php?page=Soap">SOAP</a></li>
                    </ul>
                <?php endif; ?>
            </li>

            <?php if ($userRole === 'admin'): ?>
                <li><a href="index.php?page=Rest">Admin</a></li>
            <?php endif; ?>

            <?php if ($userRole === 'látogató'): ?>
                <li><a href="index.php?page=Login" class="button primary">Login</a></li>
                <li><a href="index.php?page=Register" class="button primary">Sign Up</a></li>
            <?php else: ?>
                <li><a href="index.php?page=logout" class="button primary">Logout</a></li>
                <li><span>Bejelentkezve: <?= htmlspecialchars($_SESSION['username']) ?></span></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>



</body>
</html>