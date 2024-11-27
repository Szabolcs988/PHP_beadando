<?php
require_once '../vendor/tcpdf/tcpdf.php';
require_once '../models/Database.php';

class PdfController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function generateSoftwarePdf($category = null, $location = null, $installationDate = null) {
    $query = "SELECT szoftver.id, szoftver.nev, szoftver.kategoria, gep.hely, telepites.datum 
              FROM telepites 
              JOIN szoftver ON telepites.szoftverid = szoftver.id 
              JOIN gep ON telepites.gepid = gep.id 
              WHERE 1=1";
    
    // Paraméterek hozzáadása a lekérdezéshez
    $params = [];

    if ($category) {
        $query .= " AND szoftver.kategoria = :category";
        $params[':category'] = $category;
    }
    if ($location) {
        $query .= " AND gep.hely = :location";
        $params[':location'] = $location;
    }
    if ($installationDate) {
        //$formattedDate = date("Y-m-d", strtotime($installationDate));
	        
	$query .= " AND telepites.datum = '2016-12-13'";
        //$params[':installationDate'] = $installationDate;
    } 
    $stmt = $this->conn->prepare($query);

    // Paraméterek kötése az előkészített állításhoz
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
	

    $stmt->execute();
    $softwareList = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
    // Ha nincs adat a megadott feltételek szerint
    if (empty($softwareList)) {
        echo "<p>Nincs megjeleníthető adat.</p>";
        return;
    }

    // PDF generálás
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, 'Software Inventory Report', '', 0, 'C', true, 0, false, false, 0);
    
    foreach ($softwareList as $software) {
        $pdf->Write(0, "ID: " . $software['id'] . ", Name: " . $software['nev'] . ", Category: " . $software['kategoria'] . ", Location: " . $software['hely'] . ", Installation Date: " . $software['datum'], '', 0, '', true, 0, false, false, 0);
	   
}
  	
    $pdf->Output('software_inventory_report.pdf', 'I');
}

}
?>
