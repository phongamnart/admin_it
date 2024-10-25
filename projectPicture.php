<?php
include("config/app_data.php");
$conDB = new db_conn();

$strSQL = "SELECT `projectdescription`.`ID`, `projectdescription`.`ProjectCode`, `attachments`.`Attachments` 
FROM `projectdescription` LEFT JOIN `attachments` ON `projectdescription`.`ID` = `attachments`.`MasterID`
WHERE `projectdescription`.`RPCID` IS NOT NULL AND `projectdescription`.`ProjectCode` IS NOT NULL AND `attachments`.`Attachments` IS NOT NULL";
$objQuery = $conDB->sqlQuery($strSQL);

if (!$objQuery) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Error in SQL query'
    ]);
    exit();
}

$results = [];

while ($objResult = mysqli_fetch_assoc($objQuery)) {
    if (isset($objResult['ProjectCode'])) {
        $objResult['ProjectCode'] = preg_replace('/[^A-Za-z0-9]/', '', $objResult['ProjectCode']);
        if (preg_match('/^[A-Za-z]{3}[0-9]{4}$/', $objResult['ProjectCode'])) {
            $results[] = $objResult;
        }
    }
}

if (count($results) > 0) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => "",
        'data' => $results
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'No data found'
    ]);
}
