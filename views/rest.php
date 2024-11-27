<!-- views/rest.php -->
<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESTful Kliens</title>
</head>
<body>
    <h1>RESTful API Kliens</h1>
    
    <form method="POST" action="">
        <label for="action">Művelet:</label>
        <select name="action" id="action">
            <option value="get">GET - Lekérés</option>
            <option value="post">POST - Új gép hozzáadása</option>
            <option value="put">PUT - Gép frissítése</option>
            <option value="delete">DELETE - Gép törlése</option>
        </select>
        
        <!-- ID mező a PUT és DELETE műveletekhez -->
        <div id="idField" style="display: none;">
            <label for="id">Gép ID:</label>
            <input type="text" name="id" id="id">
        </div>

        <!-- Adatmezők a POST és PUT műveletekhez -->
        <div id="dataFields" style="display: none;">
            <label for="hely">Hely:</label>
            <input type="text" name="hely" id="hely"><br>
            <label for="tipus">Típus:</label>
            <input type="text" name="tipus" id="tipus"><br>
            <label for="ipcim">IP Cím:</label>
            <input type="text" name="ipcim" id="ipcim"><br>
        </div>

        <button type="submit" name="submit">Küldés</button>
    </form>

    <script>
        // Dinamikus mezőmegjelenítés a művelet alapján
        document.getElementById('action').addEventListener('change', function() {
            var action = this.value;
            document.getElementById('idField').style.display = (action === 'put' || action === 'delete') ? 'block' : 'none';
            document.getElementById('dataFields').style.display = (action === 'post' || action === 'put') ? 'block' : 'none';
            
            // Töröljük az előző eredményeket, amikor a művelet megváltozik
            var resultElement = document.querySelector('#result');
            if (resultElement) {
                resultElement.innerHTML = '';
            }
        });
    </script>
    

<?php
require_once './RestClient.php';
$client = new RestClient("http://www.jagersz.nhely.hu/api/index.php");

if (isset($_POST['submit'])) {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $data = [
        "hely" => $_POST['hely'] ?? null,
        "tipus" => $_POST['tipus'] ?? null,
        "ipcim" => $_POST['ipcim'] ?? null
    ];

    switch ($action) {
        case 'get':
            $response = $client->getGep();
            break;
        case 'post':
            $response = $client->createGep($data);
            break;
        case 'put':
            $response = $client->updateGep($id, $data);
            
            break;
        case 'delete':
            $response = $client->deleteGep($id);
            break;
        default:
            $response = ["message" => "Érvénytelen művelet"];
            break;
    }

    echo "<div id='result'><h2>Eredmény:</h2><pre>" . print_r($response, true) . "</pre></div>";
}
?>
</body>
</html>

<?php include 'footer.php'; ?>