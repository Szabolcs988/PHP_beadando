<?php
include 'header.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('./soap_mnb_client.php');

$data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currencyPair = $_POST['currencyPair'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    
    $data = getExchangeRatesForPeriod($currencyPair, $startDate, $endDate);
    // var_dump($data); // Ellenőrizzük a lekérdezett adatokat
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deviza Árfolyamok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Deviza Árfolyam Lekérdezés</h1>

    <form method="POST">
        <label for="currencyPair">Devizapár (pl. EUR-HUF):</label>
        <input type="text" id="currencyPair" name="currencyPair" required>
        <br><br>
        
        <label for="startDate">Kezdő dátum:</label>
        <input type="date" id="startDate" name="startDate" required>
        <br><br>

        <label for="endDate">Befejező dátum:</label>
        <input type="date" id="endDate" name="endDate" required>
        <br><br>

        <button type="submit">Lekérdezés</button>
    </form>

    <?php if (!empty($data)): ?>
        <h2>Árfolyamok a megadott időszakra:</h2>
        <table>
            <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Árfolyam</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $rate): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rate['date']); ?></td>
                        <td><?php echo htmlspecialchars($rate['rate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Grafikon:</h3>
        <canvas id="exchangeRateChart"></canvas>
        <script>
            const ctx = document.getElementById('exchangeRateChart').getContext('2d');
            const chartData = {
                labels: <?php echo json_encode(array_column($data, 'date')); ?>,
                datasets: [{
                    label: 'Árfolyamok',
                    data: <?php echo json_encode(array_column($data, 'rate')); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1,
                    fill: false
                }]
            };

            const config = {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Dátum'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Árfolyam'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            };

            const myChart = new Chart(ctx, config);
        </script>
    <?php endif; ?>

</body>
</html>

<?php include 'footer.php'; ?>