<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>PDF Generálás Űrlap</title>
</head>
<body>
    <h1>PDF Generálás</h1>
    
    <form method="POST" action="views/generate_pdf.php">
        <label for="category">Szoftver kategória:</label>
        <input type="text" id="category" name="category"><br><br>

        <label for="location">Hely (gépek):</label>
        <input type="text" id="location" name="location"><br><br>

        <label for="installation_date">Telepítés dátuma:</label>
        <input type="date" id="installation_date" name="installation_date"><br><br>

        <button type="submit" name="generate_pdf">PDF Létrehozása</button>
    </form>
</body>
</html>
<?php include 'footer.php'; ?>
