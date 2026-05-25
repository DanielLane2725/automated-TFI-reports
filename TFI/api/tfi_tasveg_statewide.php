<?php
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../inc/db.php';

$pdo = db();

header('Content-Type: application/json');

$stmt = $pdo->prepare("
    SELECT
        objectid,
        tfi_cat,
        vegetationcode,
        vegetationgroup,
        frb_treat,
        tfi_area_ha,
        tfi_total_area_ha,
        tfi_per
    FROM gis.tfi_tasveg_statewide_geom_mv
    ORDER BY vegetationcode, tfi_cat
");

$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'data' => $data
]);