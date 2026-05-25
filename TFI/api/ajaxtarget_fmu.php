<?php
declare(strict_types=1);

require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/constants.php';

$pdo = db(); // <-- your db() returns PDO

// --------------------
// Initial SQL query
// --------------------
$iniSQL = "objectid,
    fru_code,
    fmu_name,
    agn_code,
    op_stat,
    to_char(op_s_date, 'DD/MM/YYYY') as op_s_date,
    to_char(op_e_date, 'DD/MM/YYYY') as op_e_date,
    per_comp,
    agn_name";

// NOTE: $tbl must already exist (likely from constants.php or earlier code)
if (!isset($tbl) || $tbl === '') {
    http_response_code(500);
    die("Missing \$tbl");
}

// Minimal identifier safety (prevents obvious injection if $tbl ever becomes user-controlled)
if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tbl)) {
    http_response_code(400);
    die("Invalid table name");
}

$conSQL      = "SELECT {$iniSQL} FROM fta.{$tbl} WHERE op_stat <> 'Abandoned' AND coalesce(trim(fru_code), '') <> ''";
$ConCountSQL = "SELECT count(*) FROM fta.{$tbl} WHERE op_stat <> 'Abandoned' AND coalesce(trim(fru_code), '') <> ''";

$sqlCols = $sqlCols ?? ''; // keep your existing variable behaviour

// --------------------
// Read values (DataTables)
// --------------------
$draw        = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$row         = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$rowperpage  = isset($_POST['length']) ? (int)$_POST['length'] : 10;

$columnIndex     = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$columnName      = $_POST['columns'][$columnIndex]['data'] ?? 'objectid';
$columnSortOrder = $_POST['order'][0]['dir'] ?? 'asc';

// Export handling uses different POST key for search
$isExport = (isset($_POST['ExportToExcel']) && $_POST['ExportToExcel'] === 'Yes');
$searchValue = $isExport ? ($_POST['searchValue'] ?? '') : ($_POST['search']['value'] ?? '');

// --------------------
// ORDER BY safety (whitelist columns + sort direction)
// --------------------
$allowedCols = ['objectid','fru_code','fmu_name','agn_code','op_stat','op_s_date','op_e_date','per_comp','agn_name'];
if (!in_array($columnName, $allowedCols, true)) {
    $columnName = 'objectid';
}
$columnSortOrder = (strtolower($columnSortOrder) === 'desc') ? 'DESC' : 'ASC';

// --------------------
// Search
// --------------------
$searchQuery = "";
$searchQueryVal = [];

if ($searchValue !== '') {
    $searchQuery .= " AND (
        CAST(objectid AS text) ILIKE ? OR
        fru_code ILIKE ? OR
        fmu_name ILIKE ? OR
        agn_code ILIKE ? OR
        op_stat ILIKE ? OR
        CAST(op_s_date AS text) ILIKE ? OR
        CAST(op_e_date AS text) ILIKE ? OR
        CAST(per_comp AS text) ILIKE ? OR
        agn_name ILIKE ?
    )";

    $like = '%' . $searchValue . '%';
    // 9 placeholders
    $searchQueryVal = [$like,$like,$like,$like,$like,$like,$like,$like,$like];
}

// Keep your customSearch hook (WARNING: only safe if server-generated/trusted)
if (isset($_POST['customSearch']) && $_POST['customSearch'] !== '') {
    $searchQuery .= $_POST['customSearch'];
}

// --------------------
// Total records (no filter)
// --------------------
$stmt = $pdo->prepare($ConCountSQL);
$stmt->execute();
$totalRecords = (int)$stmt->fetchColumn();

// --------------------
// Total records (with filter)
// --------------------
$stmt = $pdo->prepare($ConCountSQL . $searchQuery);
$stmt->execute($searchQueryVal);
$totalRecordwithFilter = (int)$stmt->fetchColumn();

// --------------------
// Fetch records
// --------------------
if ($isExport) {
    // Export = no order/limit/offset (clean rebuild)
    $sql = $conSQL . $searchQuery;
} else {
    $sql = $conSQL . $searchQuery . " ORDER BY {$columnName} {$columnSortOrder} LIMIT {$rowperpage} OFFSET {$row}";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($searchQueryVal);

$data = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        "objectid"  => $r['objectid'],
        "fru_code"  => $r['fru_code'],
        "fmu_name"  => $r['fmu_name'],
        "agn_code"  => $r['agn_code'],
        "op_stat"   => $r['op_stat'],
        "op_s_date" => $r['op_s_date'],
        "op_e_date" => $r['op_e_date'],
        "per_comp"  => $r['per_comp'],
        "agn_name"  => $r['agn_name']
    ];
}

// --------------------
// Response
// --------------------
header('Content-Type: application/json; charset=utf-8');

if ($isExport) {
    echo json_encode($data);
} else {
    $response = [
        "draw" => $draw,
        //"iTotalRecords" => $totalRecords,
        //"iTotalDisplayRecords" => $totalRecordwithFilter,
        //"aaData" => $data,        
        "recordsTotal"    => (int)$totalRecords,
        "recordsFiltered" => (int)$totalRecordwithFilter,
        "data"            => $data,
        "aColumns" => explode(",", $sqlCols),
        "aSQL" => $sql
    ];
    echo json_encode($response);
}

?>