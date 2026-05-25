<?php
declare(strict_types=1);

define('DB_PROFILE', 'RA'); // 'RA' (Azure) or 'LOCAL' (default)

require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/constants.php';

$pdo = db();

header('Content-Type: application/json');

// --------------------
// Input
// --------------------
$boundary_geometry = $_POST['boundaryGeometry'] ?? '';

if ($boundary_geometry === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing boundaryGeometry']);
    exit;
}

// strip wrapping quotes (matches your original behaviour)
$boundary_geometry = trim($boundary_geometry, '"');

// --------------------
// SQL
// --------------------
$sql = "
WITH aoi AS (
    SELECT
        ST_Transform(
            ST_SetSRID(
                ST_GeomFromGeoJSON(:boundary_geom),
                4326
            ),
            28355
        ) AS geom
),
dissolved AS (
    SELECT
        tv.vegetationcode,
        firesens,
        tv.tfi_cat,
        ST_Multi(
            ST_Union(
                ST_Intersection(tv.geom, aoi.geom)
            )
        ) AS geom_28355
    FROM riskadmin.tasveglive_tfi_2025 tv
    JOIN aoi
      ON tv.geom && aoi.geom
     AND ST_Intersects(tv.geom, aoi.geom)
    GROUP BY
        tv.vegetationcode,
        firesens,
        tv.tfi_cat
)
SELECT
    vegetationcode,
    firesens,
    tfi_cat,
    ST_Area(geom_28355) AS area_m2,
    ROUND((ST_Area(geom_28355) / 10000.0)::numeric, 2) AS area_ha,
    ST_AsGeoJSON(
        ST_Transform(geom_28355, 4326)
    ) AS clipped_geom
FROM dissolved
WHERE NOT ST_IsEmpty(geom_28355)
ORDER BY vegetationcode, tfi_cat
";

// --------------------
// Execute
// --------------------
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'boundary_geom' => $boundary_geometry
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
    exit;
}

// --------------------
// Build GeoJSON
// --------------------
$features = [];

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $features[] = [
        'type' => 'Feature',
        'properties' => [
            'vegetationcode' => $r['vegetationcode'],
            'firesens'       => $r['firesens'],
            'tfi_category'   => $r['tfi_cat'],
            'area_m2'        => (float)$r['area_m2'],
            'area_ha'        => (float)$r['area_ha']
        ],
        'geometry' => json_decode($r['clipped_geom'], true)
    ];
}

echo json_encode([
    'type'     => 'FeatureCollection',
    'features' => $features
]);
?>