<?php
declare(strict_types=1);
require __DIR__ . '/../inc/db.php';

$pdo = db();

header('Content-Type: application/json');

$objectid = $_POST['objectid'] ?? '';
$unitGeoJSON = $_POST['bufferGeometry'] ?? '';

$unitArray = json_decode($unitGeoJSON, true);
if (!$unitArray) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON in buffer_geometry_500']);
    exit;
}

if (!isset($unitArray['type'], $unitArray['coordinates'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid GeoJSON structure']);
    exit;
}

$rings = [];

switch ($unitArray['type']) {
    case 'Polygon':
        foreach ($unitArray['coordinates'] as $ring) {
            $rings[] = $ring;
        }
        break;

    case 'MultiPolygon':
        foreach ($unitArray['coordinates'] as $polygon) {
            foreach ($polygon as $ring) {
                $rings[] = $ring;
            }
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unsupported GeoJSON type: ' . $unitArray['type']]);
        exit;
}

$arcgisPolygon = [
    'rings' => $rings,
    'spatialReference' => ['wkid' => 4326]
];

$polygonJson = json_encode($arcgisPolygon, JSON_UNESCAPED_SLASHES);

$endpoint1 = "https://services.thelist.tas.gov.au/arcgis/rest/services/Public/NaturalEnvironment/MapServer/47/query";
$params1 = [
    'geometry'       => $polygonJson,
    'geometryType'   => 'esriGeometryPolygon',
    'spatialRel'     => 'esriSpatialRelIntersects', 
    'outFields'      => '*',
    'returnGeometry' => 'true',                     
    'inSR'           => '4326',
    'outSR'          => '4326',
    'f'              => 'geojson'
];

$options1 = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params1)
    ]
];
$response1 = file_get_contents($endpoint1, false, stream_context_create($options1));
if ($response1 === false) {
    echo json_encode(["error" => "ArcGIS query (layer 47) failed"]);
    exit;
}
$data1 = json_decode($response1, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["error" => "Invalid JSON from layer 47"]);
    exit;
}

if (empty($data1['features'])) {
    echo json_encode(["type" => "FeatureCollection", "features" => []]);
    exit;
}

$foreignIds = [];
foreach ($data1['features'] as $feature) {
    $fid = $feature['properties']['FOREIGN_ID'] ?? null;
    if ($fid) { $foreignIds[] = $fid; }
}
$foreignIds = array_unique($foreignIds);

if (empty($foreignIds)) {
    echo json_encode(["type" => "FeatureCollection", "features" => []]);
    exit;
}

$endpoint2 = "https://services.thelist.tas.gov.au/arcgis/rest/services/Public/NVAdata/MapServer/2/query";
$whereClause = "FOREIGN_ID IN ('" . implode("','", $foreignIds) . "')";

$params2 = [
    'where'          => $whereClause,
    'geometry'       => $polygonJson,
    'geometryType'   => 'esriGeometryPolygon',
    'spatialRel'     => 'esriSpatialRelIntersects',
    'outFields'      => 'SPECIES_NAME,PREFERRED_COMMON_NAMES,STATE_SCHEDULE,NATIONAL_SCHEDULE,FOREIGN_ID,OBSERVATION_STATE,OBSERVATION_DATE',
    'returnGeometry' => 'true',
    'inSR'           => '4326',
    'outSR'          => '4326',
    'f'              => 'geojson'
];

$options2 = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params2)
    ]
];
$response2 = file_get_contents($endpoint2, false, stream_context_create($options2));
if ($response2 === false) {
    echo json_encode(["error" => "ArcGIS query (layer 2) failed"]);
    exit;
}
$data2 = json_decode($response2, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["error" => "Invalid JSON from layer 2"]);
    exit;
}

$nvaIndex = [];
if (!empty($data2['features'])) {
    foreach ($data2['features'] as $nvafeature) {
        $fid = $nvafeature['properties']['FOREIGN_ID'] ?? null;
        if ($fid) {
            $nvaIndex[$fid] = [
                'properties' => $nvafeature['properties'],
                'geometry'   => $nvafeature['geometry'] ?? null
            ];
        }
    }
}

function samePoint(array $a = null, array $b = null, float $tol = 1e-7): bool {
    if (!$a || !$b || count($a) !== 2 || count($b) !== 2) return false;
    return (abs($a[0] - $b[0]) <= $tol) && (abs($a[1] - $b[1]) <= $tol);
}

$requireEqualGeometry = true; 
$features = [];

foreach ($data1['features'] as $feature) {
    $propsNest = $feature['properties'];
    $coordsNest = $feature['geometry']['coordinates'] ?? null;
    $fid = $propsNest['FOREIGN_ID'] ?? null;
    $nvaProps = [];
    $coordsNva = null;

    if ($fid && isset($nvaIndex[$fid])) {
        $fromNva = $nvaIndex[$fid];
        $nvaPropsSrc = $fromNva['properties'] ?? [];
        $coordsNva   = $fromNva['geometry']['coordinates'] ?? null;

        foreach (['SPECIES_NAME','PREFERRED_COMMON_NAMES','STATE_SCHEDULE','NATIONAL_SCHEDULE','FOREIGN_ID','OBSERVATION_STATE','OBSERVATION_DATE'] as $field) {
            $nvaProps[$field] = $nvaPropsSrc[$field] ?? null;
        }
    }

    if ($requireEqualGeometry && $coordsNest && $coordsNva) {
        if (!samePoint($coordsNest, $coordsNva, 1e-6)) { 
            continue;
        }
    }

    $features[] = [
        "type" => "Feature",
        "geometry" => [
            "type" => "Point",
            "coordinates" => $coordsNest
        ],
        "properties" => $nvaProps
    ];
}

echo json_encode([
    "type" => "FeatureCollection",
    "features" => $features
], JSON_PRETTY_PRINT);
