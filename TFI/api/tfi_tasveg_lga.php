<?php
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../inc/db.php';

$pdo = db();
header('Content-Type: application/json');

// Required params
$veg = $_GET['veg'] ?? null;
$tfi = $_GET['tfi'] ?? null;

// Optional param
$lga = $_GET['lga'] ?? null;

if ($veg === null || $tfi === null) {
    echo json_encode(['data' => []]);
    exit;
}

$sql = "
    SELECT
        objectid,
        lga_name AS lga,
        tfi_cat,
        vegetationcode,
        tfi_area_ha as area_ha,
        tfi_total_area_ha as total_area_ha,
        tfi_per
    FROM gis.tfi_tasveg_lga_mv
    WHERE vegetationcode = :veg
      AND tfi_cat = :tfi
";

$params = [
    ':veg' => $veg,
    ':tfi' => $tfi
];

// Optional bioregion filter
if ($lga !== null && $lga !== '' && $lga !== 'ALL') {
    $sql .= " AND lga_name = :lga";
    $params[':lga'] = $lga;
}

$sql .= " ORDER BY lga_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'data' => $data
]);