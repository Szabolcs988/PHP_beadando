<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Növeljük a memória limitet
ini_set('memory_limit', '1G');

function getExchangeRatesForPeriod($currencyPair, $startDate, $endDate) {
    try {
        $client = new SoapClient("http://www.mnb.hu/arfolyamok.asmx?WSDL");

        // Helyes formátum: 'currencyNames' => 'EUR,HUF'
        $currencyNames = str_replace('-', ',', strtoupper($currencyPair)); // pl. EUR-HUF -> EUR,HUF
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currencyNames' => $currencyNames
        ];

        $response = $client->GetExchangeRates($params);

        if (isset($response->GetExchangeRatesResult) && !empty($response->GetExchangeRatesResult)) {
            $xml = new SimpleXMLElement($response->GetExchangeRatesResult);

            $data = [];
            foreach ($xml->Day as $day) {
                foreach ($day->Rate as $rate) {
                    $data[] = [
                        'date' => (string) $day['date'],
                        'rate' => (float)str_replace(',', '.', (string) $rate) // Konvertáljuk tizedespontokra és float típusra
                    ];
                }
            }

            return $data;
        } else {
            echo "No exchange rates found.";
            return [];
        }
    } catch (SoapFault $e) {
        echo "SOAP Hiba: " . $e->getMessage();
        return [];
    }
}
?>
