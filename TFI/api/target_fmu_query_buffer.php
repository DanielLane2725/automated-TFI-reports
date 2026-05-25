<?php
declare(strict_types=1);
require __DIR__ . '/../inc/db.php';

$pdo = db();

header('Content-Type: application/json');

// Validate and sanitize input
$objectid = $_POST['objectid'] ?? '';
if (!is_numeric($objectid)) {
    http_response_code(400);
    echo '{"error":"Invalid objectid"}';
    exit;
};

//$objectid ='1969';

// Prepare and execute SQL query
$stmt = $pdo->prepare("
    SELECT 
        objectid, 
        st_asgeojson(st_transform(st_buffer(t.shape, 200::double precision), 4326)) AS geojson_200,
        ST_AsGeoJSON(ST_Transform(ST_SimplifyPreserveTopology(ST_Difference(ST_Buffer(shape, 500),t.shape),10.0),4326)) AS geojson_500,
        ST_AsGeoJSON(ST_Transform(ST_SimplifyPreserveTopology(ST_Difference(ST_Buffer(shape, 1000),ST_Buffer(t.shape, 500)),10.0),4326)) AS geojson_1000 
    FROM fta.fuel_management_units_fd t 
    WHERE objectid = :objectid
");
$stmt->bindValue(':objectid', $objectid, PDO::PARAM_INT);
$stmt->execute();

// Build GeoJSON FeatureCollection as a string
$features = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $features[] = '{"type":"Feature","geometry":' . $row['geojson_200'] . ',"properties":{
    "objectid":' . $row['objectid'] . ',
    "geojson_500":' . $row['geojson_500'] . ',
    "geojson_1000":' . $row['geojson_1000'] . '
    }}';
}

$geojsonString = '{"type":"FeatureCollection","features":[' . implode(',', $features) . ']}';

// Output as string
echo $geojsonString;

?>
