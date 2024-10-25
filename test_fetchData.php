<?php
include("_check_session.php");
$conDB = new db_conn();

$inputJSON = file_get_contents('php://input');

// echo "Raw JSON Data Received:\n";
// echo $inputJSON;

$input = json_decode($inputJSON, true);

if (is_array($input)) {
    // echo "\n\nParsed Data:\n";
    $result = [];

    //value
    if (isset($input['items']['value']) && is_array($input['items']['value'])) {
        foreach ($input['items']['value'] as $item) {
            $id = $item['ID'];
            $result[$id] = [
                'ID' => $id,
                'MasterID' => $item['MasterID'],
                'Attachments' => []
            ];
        }
    }

    //attachments
    if (isset($input['attachments']) && is_array($input['attachments'])) {
        foreach ($input['attachments'] as $attachment) {
            $id = explode('/', $attachment)[9];

            if (isset($result[$id])) {
                $result[$id]['Attachments'][] = $attachment;
            }
        }
    }

    $output = [];

    foreach ($result as $id => $data) {
        $output[] = [
            'ID' => $data['ID'],
            'MasterID' => $data['MasterID'],
            'Attachments' => !empty($data['Attachments']) ? $data['Attachments'] : null
        ];
    }

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $output
    ]);

    foreach ($result as $id => $data) {
        $masterID = $data['MasterID'];

        if (!empty($data['Attachments'])) {
            foreach ($data['Attachments'] as $attachment) {
                $sql_insert = "INSERT INTO `attachments` (`ID`, `MasterID`, `Attachments`)
                               VALUES ('$id', '$masterID', '$attachment')";
                $conDB->sqlQuery($sql_insert);
            }
        } else {
            $sql_insert = "INSERT INTO `attachments` (`ID`, `MasterID`, `Attachments`) 
                           VALUES ('$id', '$masterID', null)";
            $conDB->sqlQuery($sql_insert);
        }
    }

} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input data.'
    ]);
}
