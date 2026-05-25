<?php
//require __DIR__ . '/../../../db.php';
//require 'settings/constants.php';

declare(strict_types=1);

require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/constants.php';

$pdo = db();

header('Content-Type: application/json; charset=utf-8');
$tbl = ${$_POST['lType']};
$lType = $_POST['lType'] ?? [];
$names = $_POST['names'] ?? [];

if ($lType == 'dataTfiL'){
    $names = explode(',', $names);
};

   if (!is_array($names)) { $names = []; }

    $names = array_values(array_filter(array_map('trim', $names), fn($s) => $s !== ''));

    // CLEAN VALUES
    $names = array_values(array_filter(
        array_map(fn($s) => trim((string)$s), $names),
        fn($s) => $s !== ''
    ));


    if (!$names) { echo json_encode([]); exit; }

    try {
        $placeholders = implode(',', array_fill(0, count($names), '?'));
        if ($lType == 'dataWdL'){
            $sql = $tbl . " WHERE TRIM(LOWER(species)) IN (" . implode(',', array_fill(0, count($names), 'TRIM(LOWER(?))')) . ") or species =''";
        } else if ($lType == 'dataTfiL'){
            //$sql = $tbl . " WHERE TRIM(LOWER(veg_code_d)) IN (" . implode(',', array_fill(0, count($names), 'TRIM(LOWER(?))')) . ")"; 
            $sql = "
            WITH input_codes AS (
            SELECT TRIM(LOWER(x.code)) AS veg_code
            FROM UNNEST(ARRAY[$placeholders]) AS x(code)
            ),
            default_row AS (
            SELECT
                potential_impact AS def_impact,
                management_recommendation AS def_management
            FROM frb_tfi_lut
            WHERE veg_code_d IS NULL OR TRIM(veg_code_d) = ''
            LIMIT 1
            )
            SELECT
            i.veg_code,
            COALESCE(l.potential_impact, d.def_impact) AS impact,
            COALESCE(l.management_recommendation, d.def_management) AS management
            FROM input_codes i
            LEFT JOIN frb_tfi_lut l
            ON TRIM(LOWER(l.veg_code_d)) = i.veg_code
            CROSS JOIN default_row d
            ORDER BY i.veg_code
            ";
        } else {
            $sql = $tbl . " WHERE TRIM(LOWER(species)) IN (" . implode(',', array_fill(0, count($names), 'TRIM(LOWER(?))')) . ")";
        };

        $params = array_map(fn($s) => strtolower(trim((string)$s)), $names);

        //echo $sql;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        if ($lType == 'dataTfiL'){
            echo json_encode([
            'rows' => $rows,
            'sql'  => $sql
             ]);
        }else{
            ////echo json_encode($rows);//.'||sql:'.$sql;        
            echo json_encode([
            'rows' => $rows,
            'sql'  => $sql
            ]);
        };

    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['error' => 'server_error']);
    }
?>