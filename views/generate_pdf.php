<?php
require_once('../models/Database.php'); // Az adatbázis kapcsolatot hozza létre

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_pdf'])) {
    // Az űrlapról kapott adatok beolvasása
    $category = $_POST['category'] ?? null;
    $location = $_POST['location'] ?? null;
    $installationDate = $_POST['installation_date'] ?? null;

    // Adatbázis kapcsolat létrehozása
    try {
        $database = new Database();
        $dbh = $database->getConnection();
        $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

        // SQL lekérdezés alapértelmezett feltételekkel
        $sql = "SELECT szoftver.id, szoftver.nev, szoftver.kategoria, gep.hely, telepites.datum
                FROM telepites
                JOIN szoftver ON telepites.szoftverid = szoftver.id
                JOIN gep ON telepites.gepid = gep.id
                WHERE 1=1";

        // Paraméterek hozzáadása a lekérdezéshez
        if (!empty($category)) {
            $sql .= " AND szoftver.kategoria = :category";
        }
        if (!empty($location)) {
            $sql .= " AND gep.hely = :location";
        }
        if (!empty($installationDate)) {
            $formattedDate = date("Y-m-d", strtotime($installationDate));
            $sql .= " AND telepites.datum = :installationDate";
        }

        // Lekérdezés előkészítése és paraméterek kötése
        $stmt = $dbh->prepare($sql);
        if (!empty($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        if (!empty($location)) {
            $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        }
        if (!empty($installationDate)) {
            $stmt->bindParam(':installationDate', $formattedDate, PDO::PARAM_STR);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Hiba: " . $e->getMessage();
        die();
    }

    // A TCPDF fő fájljának beemelése
    require_once('../vendor/tcpdf/tcpdf.php');

    // Új PDF dokumentum létrehozása
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Dokumentum információk beállítása
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Szoftverleltár PDF Generálás');
    $pdf->SetTitle('Telepített Szoftverek Jelentés');
    $pdf->SetSubject('Szoftverleltár - PDF Generálás');
    $pdf->SetKeywords('TCPDF, PDF, szoftverleltár');

    // Alapértelmezett fejléc beállítása
    $pdf->SetHeaderData("nje.png", 25, "SZOFTVEREK LISTÁJA", "Szoftverleltár\n" . date('Y.m.d', time()));

    // Fejléc és lábléc betűtípus beállítása
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Alapértelmezett monospaced betűtípus beállítása
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Margók beállítása
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Automatikus oldaltörés beállítása
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Betűtípus beállítása
    $pdf->SetFont('helvetica', '', 10);

    // Új oldal hozzáadása
    $pdf->AddPage();

    // HTML tartalom létrehozása a lekérdezés eredményeivel
    $html = '
    <html>
    <head>
        <style>
            table {border-collapse: collapse; width: 100%;}
            th, td {border: 1px solid #000000; padding: 8px; text-align: center;}
            th {background-color: #f2f2f2;}
        </style>
    </head>
    <body>
        <h1 style="text-align: center; color: blue;">TELEPÍTETT SZOFTVEREK LISTÁJA</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>NÉV</th>
                <th>KATEGÓRIA</th>
                <th>HELY</th>
                <th>TELEPÍTÉS DÁTUMA</th>
            </tr>';

    foreach ($rows as $row) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['nev']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['kategoria']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['hely']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['datum']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '
        </table>
    </body>
    </html>';

    // PDF tartalom megírása
    $pdf->writeHTML($html, true, false, true, false, '');

    // PDF fájl bezárása és megjelenítése
    $pdf->Output('szoftverleltar.pdf', 'I');
}
?>
