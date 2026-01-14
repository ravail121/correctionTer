<?php
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$favorites = $data['favorites'] ?? [];

if (empty($favorites)) {
    echo json_encode(['error' => 'No favorites received']);
    exit;
}

 include "db.php";

function convertVolume($volume) {
    $billions = $volume / 1_000_000_000;
    $billions_rounded = round($billions, 2);
    $millions = $volume / 1_000_000;
    $millions_rounded = round($millions, 2);
    return [
        'billions' => $billions_rounded . ' B',
        'millions' => $millions_rounded . ' M'
    ];
}

$symbols = "'" . implode("','", array_map([$con, 'real_escape_string'], $favorites)) . "'";
$query = "SELECT * FROM companies WHERE symbl IN ($symbols) AND market_cap > 0";
$result = $con->query($query);

if (!$result) {
    echo json_encode(['error' => 'Database query failed: ' . $con->error]);
    exit;
}

$companies = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $converted_market_cap = convertVolume($row["market_cap"]);
        $converted_average_volume = convertVolume($row["average_volume"]);
        $exchange_value = $row["exchange_name"];
        if ($exchange_value == 'XNAS') {
            $exchange_name = "NASDAQ";
        } elseif ($exchange_value == 'XNYS') {
            $exchange_name = "NYSE";
        }

        $correctionRangeClass = '';
        if ($row["correction_range"] >= -5) {
            $correctionRangeClass = 'bg-danger';
        } else if ($row["correction_range"] >= -15) {
            $correctionRangeClass = 'bg-warning';
        } else {
            $correctionRangeClass = 'bg-success';
        }

        $companies[] = [
            'name' => $row["name"],
            'symbl' => $row["symbl"],
            'last_close' => $row["last_close"],
            'all_time_high' => $row["all_time_high"],
            'converted_market_cap' => $converted_market_cap['billions'],
            'converted_average_volume' => $converted_average_volume['millions'],
            'correction_range' => $row["correction_range"],
            'correctionRangeClass' => $correctionRangeClass,
            'exchange_name' => $exchange_name,
        ];
    }
} else {
    echo json_encode(['error' => 'No companies found']);
    exit;
}

echo json_encode($companies);

$con->close();
?>
