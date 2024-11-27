<?php
include 'header.php';
require_once './controllers/SOAPController.php';

$soapController = new SOAPController();
$installations = $soapController->getInstallationsWithDetails();
?>

<h2>Telepítések Részletei</h2>
<table >
    <tr >
        <th>ID</th>
        <th>Gép Helye</th>
        <th>Gép Típusa</th>
        <th>Szoftver Neve</th>
        <th>Szoftver Kategóriája</th>
        <th>Verzió</th>
        <th>Telepítés Dátuma</th>
    </tr>
    <?php foreach ($installations as $installation): ?>
        <tr>
            <td text-align: center;><?= htmlspecialchars($installation['id']) ?></td>
            <td><?= htmlspecialchars($installation['hely']) ?></td>
            <td><?= htmlspecialchars($installation['tipus']) ?></td>
            <td><?= htmlspecialchars($installation['nev']) ?></td>
            <td><?= htmlspecialchars($installation['kategoria']) ?></td>
            <td><?= htmlspecialchars($installation['verzio']) ?></td>
            <td><?= htmlspecialchars($installation['datum']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>