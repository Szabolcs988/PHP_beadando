<?php
try {
    $client = new SoapClient(null, [
        'location' => 'http://localhost/web2/SOAPServer.php',
        'uri' => 'http://localhost/web2/',
        'trace' => true
    ]);

    $result = $client->getSoftwareList();

    echo "<h2>Software List</h2>";
    foreach ($result as $software) {
        echo "<p>ID: " . $software['id'] . ", Name: " . $software['nev'] . ", Category: " . $software['kategoria'] . "</p>";
    }
} catch (SoapFault $e) {
    echo "Error: " . $e->getMessage();
}
?>
