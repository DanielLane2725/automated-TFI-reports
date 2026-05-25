
<?php
require __DIR__ . '/../inc/db.php';

header('Content-Type: application/json');

$pdo = db();

$objectid = $_POST['objectid'] ?? '';
//$objectid = '1248'; // crazy complicated polygon

// 1) Pull buffered/simplified FMU polygon as GeoJSON (4326)
$stmt = $pdo->prepare("  
SELECT ST_AsGeoJSON(
         ST_Transform(
           ST_SimplifyPreserveTopology(
             shape,                  -- original geometry, no buffer
             10.0                    -- simplify tolerance in source units
           ),
           4326                      -- output lon/lat
         )
       ) AS geojson
FROM fta.fuel_management_units_fd
WHERE objectid = :objectid;
");
$stmt->bindValue(':objectid', $objectid, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo json_encode(["error" => "objectid unit not found"]);
    exit;
}
$row = $stmt->fetch();
$unitGeoJSON = $row['geojson'];

// 2) Convert GeoJSON -> ArcGIS JSON Polygon
$unitArray = json_decode($unitGeoJSON, true);
$rings = [];

if (!isset($unitArray['type'], $unitArray['coordinates'])) {
    echo json_encode(["error" => "Invalid GeoJSON structure"]);
    exit;
}

if ($unitArray['type'] === 'Polygon') {
    foreach ($unitArray['coordinates'] as $ring) {
        $rings[] = $ring;
    }
} elseif ($unitArray['type'] === 'MultiPolygon') {
    foreach ($unitArray['coordinates'] as $polygon) {
        foreach ($polygon as $ring) {
            $rings[] = $ring;
        }
    }
} else {
    echo json_encode(["error" => "Unsupported GeoJSON type: {$unitArray['type']}"]);
    exit;
}

$arcgisPolygon = [
    'rings' => $rings,
    'spatialReference' => ['wkid' => 4326]
];
$polygonJson = json_encode($arcgisPolygon, JSON_UNESCAPED_SLASHES);

// 3) Query Raptor Nests (NaturalEnvironment MapServer/47) with geometry filter
$endpoint1 = "https://services.thelist.tas.gov.au/arcgis/rest/services/Public/NaturalEnvironment/MapServer/47/query";
$params1 = [
    'geometry'       => $polygonJson,
    'geometryType'   => 'esriGeometryPolygon',
    'spatialRel'     => 'esriSpatialRelIntersects',  // intersects FMU polygon
    'outFields'      => '*',
    'returnGeometry' => 'true',                      // we need coords to match later
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

// 4) Collect FOREIGN_IDs (dedupe) from raptor nest features
$foreignIds = [];
foreach ($data1['features'] as $feature) {
    $fid = $feature['properties']['FOREIGN_ID'] ?? null;
    if ($fid) { $foreignIds[] = $fid; }
}
$foreignIds = array_unique($foreignIds);

if (empty($foreignIds)) {
    // No linked NVAdata records to pull; return the nests-only view if desired
    echo json_encode(["type" => "FeatureCollection", "features" => []]);
    exit;
}

// 5) Query NVAdata (MapServer/2) using FOREIGN_ID list ***AND*** the same geometry
$endpoint2 = "https://services.thelist.tas.gov.au/arcgis/rest/services/Public/NVAdata/MapServer/2/query";
$whereClause = "FOREIGN_ID IN ('" . implode("','", $foreignIds) . "')";

// If you expect a very large list, you can chunk $foreignIds and merge results.
// For now, single pass:
$params2 = [
    'where'          => $whereClause,
    'geometry'       => $polygonJson,                // same FMU geometry constraint
    'geometryType'   => 'esriGeometryPolygon',
    'spatialRel'     => 'esriSpatialRelIntersects',
    'outFields'      => 'SPECIES_NAME,PREFERRED_COMMON_NAMES,STATE_SCHEDULE,NATIONAL_SCHEDULE,FOREIGN_ID,OBSERVATION_STATE,OBSERVATION_DATE',
    'returnGeometry' => 'true',                      // enable strict equality check below
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

// 6) Index NVAdata by FOREIGN_ID with geometry
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

// 7) Helper: strict coordinate equality (lon/lat)
function samePoint(array $a = null, array $b = null, float $tol = 1e-7): bool {
    // $a and $b are [lon, lat]; tol ~ 1e-7 deg ≈ ~1 cm; relax to 1e-6 (~0.11 m) if needed
    if (!$a || !$b || count($a) !== 2 || count($b) !== 2) return false;
    return (abs($a[0] - $b[0]) <= $tol) && (abs($a[1] - $b[1]) <= $tol);
}

// 8) Build GeoJSON FeatureCollection
$requireEqualGeometry = true;  // set false if Intersects-only is sufficient
$features = [];

foreach ($data1['features'] as $feature) {
    $propsNest = $feature['properties'];
    $coordsNest = $feature['geometry']['coordinates'] ?? null;
    $fid = $propsNest['FOREIGN_ID'] ?? null;

    // Merge selected NVAdata fields if available
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

    // If strict equality required, skip features where nest and NVA coords differ
    if ($requireEqualGeometry && $coordsNest && $coordsNva) {
        if (!samePoint($coordsNest, $coordsNva, 1e-6)) { // ~0.11 m tolerance
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


/*require __DIR__ . '/../../../db.php';
header('Content-Type: application/json');
//$objectid = $_POST['objectid'] ?? '';
$objectid = '1248'; // crazy complicated polygon

$stmt = $pdo->prepare("SELECT ST_AsGeoJSON(
         ST_Transform(
           ST_SimplifyPreserveTopology(
             ST_Buffer(shape, 200),                -- your existing buffer
             10.0                                  -- tolerance in meters; tune 5–20
           ),
           4326
         )
       ) AS geojson
FROM fta.fuel_management_units_fd
WHERE objectid = :objectid");

$stmt->bindValue(':objectid', $objectid, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo json_encode(["error" => "objectid unit not found"]);
    exit;
}

$row = $stmt->fetch();
$unitGeoJSON = $row['geojson'];

// Convert GeoJSON to ArcGIS Polygon JSON (handles Polygon and MultiPolygon)
$unitArray = json_decode($unitGeoJSON, true);
$rings = [];

if (!isset($unitArray['type'], $unitArray['coordinates'])) {
    echo json_encode(["error" => "Invalid GeoJSON structure"]);
    exit;
}

if ($unitArray['type'] === 'Polygon') {
    // Polygon: coordinates = [ [ring1], [hole], ... ]
    foreach ($unitArray['coordinates'] as $ring) {
        $rings[] = $ring;
    }
} elseif ($unitArray['type'] === 'MultiPolygon') {
    // MultiPolygon: coordinates = [ [ [ring1], [hole], ... ], [ [ring1], ... ], ... ]
    foreach ($unitArray['coordinates'] as $polygon) {
        foreach ($polygon as $ring) {
            $rings[] = $ring;
        }
    }
} else {
    echo json_encode(["error" => "Unsupported GeoJSON type: {$unitArray['type']}"]);
    exit;
}

// Build ArcGIS polygon JSON
$arcgisPolygon = [
    'rings' => $rings,
    'spatialReference' => ['wkid' => 4326]
];

//echo json_encode($arcgisPolygon, JSON_UNESCAPED_SLASHES);

// Step 2: Query Raptor Nests
$endpoint1 = "https://services.thelist.tas.gov.au/arcgis/rest/services/Public/NaturalEnvironment/MapServer/47/query";
$params1 = [
    'geometry' => json_encode($arcgisPolygon, JSON_UNESCAPED_SLASHES),
    'geometryType' => 'esriGeometryPolygon',
    'spatialRel' => 'esriSpatialRelIntersects',
    'outFields' => '*',
    'returnGeometry' => 'true',
    'inSR' => '4326',
    'outSR' => '4326',
    'f' => 'geojson'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params1)
    ]
];
$response1 = file_get_contents($endpoint1, false, stream_context_create($options));
$data1 = json_decode($response1, true);

if (!isset($data1['features']) || count($data1['features']) === 0) {
    echo json_encode(["type" => "FeatureCollection", "features" => []]);
    exit;
}

// Collect FOREIGN_IDs
$foreignIds = [];
foreach ($data1['features'] as $feature) {
    if (isset($feature['properties']['FOREIGN_ID'])) {
        $foreignIds[] = $feature['properties']['FOREIGN_ID'];
    }
}
$foreignIds = array_unique($foreignIds);

// Step 3: Query NVAdata layer using FOREIGN_ID
$endpoint2 = "https://services.thelist.tas.gov.au/arcgis/rest/services/Public/NVAdata/MapServer/2/query";
$whereClause = "FOREIGN_ID IN ('" . implode("','", $foreignIds) . "')";
$params2 = [
    'where' => $whereClause,
    'outFields' => 'SPECIES_NAME,PREFERRED_COMMON_NAMES,STATE_SCHEDULE,NATIONAL_SCHEDULE,FOREIGN_ID,OBSERVATION_STATE,OBSERVATION_DATE',
    'returnGeometry' => 'false',
    'f' => 'geojson'
];

$options2 = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params2)
    ]
];
$response2 = file_get_contents($endpoint2, false, stream_context_create($options2));
$data2 = json_decode($response2, true);

// Index NVAdata by FOREIGN_ID
$nvaIndex = [];
if (isset($data2['features'])) {
    foreach ($data2['features'] as $nvafeature) {
        $fid = $nvafeature['properties']['FOREIGN_ID'] ?? null;
        if ($fid) {
            $nvaIndex[$fid] = $nvafeature['properties'];
        }
    }
}

// Step 4: Build GeoJSON FeatureCollection
$features = [];
foreach ($data1['features'] as $feature) {
    $props = $feature['properties'];
    $coords = $feature['geometry']['coordinates'];
    $fid = $props['FOREIGN_ID'] ?? null;

    // Merge selected NVAdata fields if available
    $nvaProps = [];
    if ($fid && isset($nvaIndex[$fid])) {
        foreach (['SPECIES_NAME','PREFERRED_COMMON_NAMES','STATE_SCHEDULE','NATIONAL_SCHEDULE','FOREIGN_ID','OBSERVATION_STATE','OBSERVATION_DATE'] as $field) {
            $nvaProps[$field] = $nvaIndex[$fid][$field] ?? null;
        }
    }

    $features[] = [
        "type" => "Feature",
        "geometry" => [
            "type" => "Point",
            "coordinates" => $coords
        ],
        "properties" => $nvaProps
    ];
}

echo json_encode([
    "type" => "FeatureCollection",
    "features" => $features
], JSON_PRETTY_PRINT);*/
?>