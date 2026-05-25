<?php
require __DIR__ . '/../inc/db.php';

header('Content-Type: application/json');

/* --------- helpers --------- */
function bad($msg){
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

function parse_poly($raw){
    $g = json_decode($raw, true);
    return ($g && isset($g['type'], $g['coordinates']) && ($g['type'] === 'Polygon' || $g['type'] === 'MultiPolygon')) ? $g : null;
}

function to_arcgis_polygon_json(array $gj){
    $rings = [];
    if ($gj['type'] === 'Polygon'){
        foreach ($gj['coordinates'] as $r) $rings[] = $r;
    } else { // MultiPolygon
        foreach ($gj['coordinates'] as $poly){
            foreach ($poly as $r) $rings[] = $r;
        }
    }
    // ArcGIS geometry JSON (NOT GeoJSON)
    return json_encode(['rings' => $rings, 'spatialReference' => ['wkid' => 4326]], JSON_UNESCAPED_SLASHES);
}

function post_arcgis($params){
    $endpoint = "https://services.thelist.tas.gov.au/arcgis/rest/services/Public/NVAdata/MapServer/2/query";
    $ctx = stream_context_create(['http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params),
        'timeout' => 25
    ]]);
    $raw = @file_get_contents($endpoint, false, $ctx);
    if ($raw === false) return null;

    $j = json_decode($raw, true);
    // Expect GeoJSON when f=geojson
    return (is_array($j) && isset($j['features']) && isset($j['type']) && $j['type'] === 'FeatureCollection') ? $j : null;
}


function oid($f){
    if (isset($f['id'])) return $f['id'];  // top-level id is preferred and most reliable

    $p = $f['properties'] ?? [];
    if (isset($p['OBJECTID'])) return $p['OBJECTID'];
    if (isset($p['objectid'])) return $p['objectid'];
    if (isset($p['FID']))      return $p['FID'];

    return null;
}
/* ----------------------------------- */

// inputs from AJAX
$bufferGeoJSON   = $_POST['bufferGeometry']   ?? '';
$boundaryGeoJSON = $_POST['boundaryGeometry'] ?? '';

$bufferObj   = parse_poly($bufferGeoJSON);
$boundaryObj = parse_poly($boundaryGeoJSON);
if (!$bufferObj)   bad('Invalid bufferGeometry (must be GeoJSON Polygon or MultiPolygon)');
if (!$boundaryObj) bad('Invalid boundaryGeometry (must be GeoJSON Polygon or MultiPolygon)');

$bufferPolygonJson   = to_arcgis_polygon_json($bufferObj);
$boundaryPolygonJson = to_arcgis_polygon_json($boundaryObj);

$where = "KINGDOM = 'Plantae' AND INTRODUCED_WATCH_LIST IS NOT NULL";

$base = [
    'where'          => $where,
    'geometryType'   => 'esriGeometryPolygon',
    'spatialRel'     => 'esriSpatialRelIntersects', // default; overridden below as needed
    'outFields'      => '*',
    'returnGeometry' => 'true',
    'inSR'           => '4326',
    'outSR'          => '4326',
    'f'              => 'geojson'
];

$boundaryFC = post_arcgis($base + [
    'geometry'   => $boundaryPolygonJson,
    'spatialRel' => 'esriSpatialRelWithin'   // tighter than Intersects; matches "within"
]);
if (!$boundaryFC) bad('Boundary query failed');

// Index by ID and tag as "within"
$featuresById = [];
$insideIds = [];

foreach ($boundaryFC['features'] as $f){
    $id = oid($f);
    if ($id === null) {
        // Skip features without a stable ID—cannot dedupe reliably
        continue;
    }
    if (!isset($f['properties']) || !is_array($f['properties'])) $f['properties'] = [];
    $f['properties']['within'] = 'within';

    $featuresById[$id] = $f;
    $insideIds[$id] = true;
}

$bufferFC = post_arcgis($base + [
    'geometry'   => $bufferPolygonJson,
    'spatialRel' => 'esriSpatialRelIntersects'
]);
if (!$bufferFC) bad('Buffer query failed');

foreach ($bufferFC['features'] as $f){
    $id = oid($f);
    if ($id === null) continue;            // cannot dedupe, skip
    if (isset($insideIds[$id])) continue;  // already tagged as 'within'

    if (!isset($f['properties']) || !is_array($f['properties'])) $f['properties'] = [];
    $f['properties']['within'] = 'within 200m';

    if (!isset($featuresById[$id])) {
        $featuresById[$id] = $f;
    }
}

$features = array_values($featuresById);


$wanted = [
    'SPECIES_NAME',
    'PREFERRED_COMMON_NAMES',
    'STATE_SCHEDULE',
    'NATIONAL_SCHEDULE',
    'FOREIGN_ID',
    'OBSERVATION_STATE',
    'OBSERVATION_DATE',
    'INTRODUCED_WATCH_LIST',
    'within'
];

foreach ($features as &$f) {
    $p = $f['properties'] ?? [];
    $new = [];
    foreach ($wanted as $k) {
        if (array_key_exists($k, $p)) {
            $new[$k] = $p[$k];
        }
    }
    $f['properties'] = $new;
}
unset($f);

echo json_encode([
    'type'     => 'FeatureCollection',
    'features' => $features
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>