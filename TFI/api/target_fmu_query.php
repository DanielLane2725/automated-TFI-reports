<?php
declare(strict_types=1);

require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/constants.php';

$pdo = db();

// expect $objectid coming from request (adjust if yours is GET not POST)
$objectid = $_POST['objectid'] ?? $_GET['objectid'] ?? '';

if ($objectid === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing objectid']);
    exit;
}

// Base SQL
$sqlBase = "
    SELECT objectid,
           st_asgeojson(st_transform(shape, 4326)) AS geojson
    FROM fta.fuel_management_units_fd
";

// LoadAll = no WHERE
if ($objectid === 'LoadAll') {
    $sql = $sqlBase;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} else {
    // enforce integer id (keeps behaviour sane)
    if (!ctype_digit((string)$objectid)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid objectid']);
        exit;
    }

    $sql = $sqlBase . " WHERE objectid = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([(int)$objectid]);
}

// Build GeoJSON FeatureCollection
$features = [];

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // st_asgeojson returns a JSON string - decode it so final output is valid JSON object
    $geom = $r['geojson'] ? json_decode($r['geojson'], true) : null;

    // Skip rows with no geometry (optional)
    if (!$geom) continue;

    $features[] = [
        "type" => "Feature",
        "geometry" => $geom,
        "properties" => [
            "objectid" => (int)$r['objectid']
        ]
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    "type" => "FeatureCollection",
    "features" => $features
]);
?>