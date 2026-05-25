<?php
declare(strict_types=1);

require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/constants.php';

$pdo = db();

header('Content-Type: application/json');

// ----------------------------
// Inputs
// ----------------------------
$boundary_geometry = $_POST['boundary_geometry'] ?? '';
$buffer_geometry   = $_POST['buffer_geometry'] ?? '';
$dataKey           = $_POST['data'] ?? '';

if ($boundary_geometry === '' || $buffer_geometry === '' || $dataKey === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

// Trim wrapping quotes (matches your original behaviour)
$boundary_geometry = trim($boundary_geometry, '"');
$buffer_geometry   = trim($buffer_geometry, '"');

// Resolve table name from constants (same as ${$_POST['data']})
if (!isset($$dataKey)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data key']);
    exit;
}
$tbl = $$dataKey;

// Very important: table name safety
if (!preg_match('/^[a-zA-Z0-9_.]+$/', $tbl)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid table name']);
    exit;
}

// ----------------------------
// SQL
// ----------------------------
$sql = "
WITH first AS (
    SELECT
        CONCAT(
            COALESCE(pt.species_name, 'N/A'), '; ',
            COALESCE(pt.preferred_common_names, 'N/A'), '; ',
            COALESCE(pt.state_schedule_desc, 'N/A'), '; ',
            COALESCE(pt.national_schedule_desc, 'N/A')
        ) AS species_name,
        'within' AS within,
        preferred_common_names,
        state_schedule_desc,
        national_schedule_desc,
        TO_CHAR(CURRENT_DATE, 'DD/MM/YYYY') AS searchdate,
        ST_AsGeoJSON(pt.geometry) AS geojson,
        pt.geometry
    FROM {$tbl} pt
    WHERE ST_Intersects(
        ST_Transform(
            ST_SetSRID(ST_GeomFromGeoJSON(:boundary_geom), 4326),
            ST_SRID(pt.geometry)
        ),
        pt.geometry
    )
),
second AS (
    SELECT
        CONCAT(
            COALESCE(pt.species_name, 'N/A'), '; ',
            COALESCE(pt.preferred_common_names, 'N/A'), '; ',
            COALESCE(pt.state_schedule_desc, 'N/A'), '; ',
            COALESCE(pt.national_schedule_desc, 'N/A')
        ) AS species_name,
        'within 200m' AS within,
        preferred_common_names,
        state_schedule_desc,
        national_schedule_desc,
        TO_CHAR(CURRENT_DATE, 'DD/MM/YYYY') AS searchdate,
        ST_AsGeoJSON(pt.geometry) AS geojson,
        pt.geometry
    FROM {$tbl} pt
    WHERE ST_Intersects(
        ST_Transform(
            ST_SetSRID(ST_GeomFromGeoJSON(:buffer_geom), 4326),
            ST_SRID(pt.geometry)
        ),
        pt.geometry
    )
    AND NOT EXISTS (
        SELECT 1 FROM first f
        WHERE ST_Equals(f.geometry, pt.geometry)
    )
),
combined AS (
    SELECT * FROM first
    UNION ALL
    SELECT * FROM second
)
SELECT
    species_name,
    MIN(within) AS within,
    preferred_common_names,
    state_schedule_desc,
    national_schedule_desc,
    searchdate,
    geojson
FROM combined
GROUP BY species_name, geometry, preferred_common_names,
         state_schedule_desc, national_schedule_desc,
         searchdate, geojson
ORDER BY within DESC
";

// ----------------------------
// Execute
// ----------------------------
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'boundary_geom' => $boundary_geometry,
        'buffer_geom'   => $buffer_geometry
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
    exit;
}

// ----------------------------
// Build GeoJSON (SAFE)
// ----------------------------
$features = [];

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $features[] = [
        'type' => 'Feature',
        'properties' => [
            'species_name'           => $r['species_name'],
            'within'                 => $r['within'],
            'preferred_common_names' => $r['preferred_common_names'],
            'state_schedule_desc'    => $r['state_schedule_desc'],
            'national_schedule_desc' => $r['national_schedule_desc'],
            'searchdate'             => $r['searchdate']
        ],
        'geometry' => json_decode($r['geojson'], true)
    ];
}

echo json_encode([
    'type'     => 'FeatureCollection',
    'features' => $features
]);
?>