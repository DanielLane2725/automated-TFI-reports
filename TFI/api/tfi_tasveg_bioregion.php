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
$bioregion = $_GET['bioregion'] ?? null;

if ($veg === null || $tfi === null) {
    echo json_encode(['data' => []]);
    exit;
}

$sql = "
    SELECT
        objectid,
        reg_name AS bioregion,
        tfi_cat,
        vegetationcode,
        area_ha,
        total_area_ha,
        tfi_per
    FROM gis.tfi_tasveg_bio_mv
    WHERE vegetationcode = :veg
      AND tfi_cat = :tfi
";

$params = [
    ':veg' => $veg,
    ':tfi' => $tfi
];

// Optional bioregion filter
if ($bioregion !== null && $bioregion !== '' && $bioregion !== 'ALL') {
    $sql .= " AND reg_name = :bioregion";
    $params[':bioregion'] = $bioregion;
}

$sql .= " ORDER BY reg_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'data' => $data
]);