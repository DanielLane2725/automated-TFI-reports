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
$fma = $_GET['fma'] ?? null;

if ($veg === null || $tfi === null) {
    echo json_encode(['data' => []]);
    exit;
}

$sql = "
    SELECT
        objectid,
        fma_name AS fma,
        tfi_cat,
        vegetationcode,
        tfi_area_ha as area_ha,
        tfi_total_area_ha as total_area_ha,
        tfi_per
    FROM gis.tfi_tasveg_fma_mv
    WHERE vegetationcode = :veg
      AND tfi_cat = :tfi
";

$params = [
    ':veg' => $veg,
    ':tfi' => $tfi
];

// Optional bioregion filter
if ($fma !== null && $fma !== '' && $fma !== 'ALL') {
    $sql .= " AND fma_name = :fma";
    $params[':fma'] = $fma;
}

$sql .= " ORDER BY fma_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'data' => $data
]);