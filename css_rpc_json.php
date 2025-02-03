<?php
include("config/app_css.php");
$conDB = new db_conn();

$inputJSON = file_get_contents('php://input');

echo "Raw JSON Data Received:\n";
echo $inputJSON;

$input = json_decode($inputJSON, true);

if (is_array($input)) {
    echo "\n\nParsed Data:\n";
    
    $items = null;
    if (isset($input['items']['value']) && is_array($input['items']['value'])) {
        $items = $input['items']['value'];
    } elseif (isset($input['value']) && is_array($input['value'])) {
        $items = $input['value'];
    }

    if ($items) {
        foreach ($items as $item) {
            $columns = [];
            $values = [];
            $updateFields = [];

            $result = $conDB->sqlQuery("SHOW COLUMNS FROM `tb_project_information`");
            $validColumns = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $validColumns[] = $row['Field'];
            }

            $id = null;
            $projectCode = null;

            $keyMapping = [
                'OriginalID' => 'RPCID',
                'Department' => 'Fulldepartment'
            ];

            foreach ($item as $key => $value) {
                if (isset($keyMapping[$key])) {
                    $key = $keyMapping[$key];
                }

                if ($key === 'ID') {
                    $key = 'pj_id';
                }

                if ($key === 'ProjectCode') {
                    $projectCode = $value;
                }

                if (in_array($key, $validColumns)) {
                    if (is_array($value)) {
                        echo "Skipping complex field: " . $key . "\n";
                        continue;
                    } else {
                        $columns[] = "`" . $conDB->escape($key) . "`";
                        $values[] = "'" . $conDB->escape($value) . "'";
                        $updateFields[] = "`" . $conDB->escape($key) . "` = '" . $conDB->escape($value) . "'";

                        if ($key == 'pj_id') {
                            $id = $value;
                        }
                    }
                }
            }

            // if (empty($id)) {
            //     echo "Skipping due to missing ID\n";
            //     continue;
            // }

            if (empty($projectCode)) {
                echo "Skipping due to missing ProjectCode\n";
                continue;
            }

            // $sql_check = "SELECT COUNT(*) AS count FROM `tb_project_information` WHERE `pj_id` = '" . $conDB->escape($id) . "'";
            $sql_check = "SELECT COUNT(*) AS count FROM `tb_project_information` WHERE `ProjectCode` = '" . $conDB->escape($projectCode) . "'";
            $checkResult = $conDB->sqlQuery($sql_check);
            $checkRow = mysqli_fetch_assoc($checkResult);

            if ($checkRow['count'] > 0) {
                echo "Skipping: ProjectCode " . $projectCode . " already exists.\n";
            } else {
                if (!empty($columns) && !empty($values)) {
                    $sql_insert = "INSERT INTO `tb_project_information` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
                    echo "\nSQL Query (INSERT): " . $sql_insert . "\n";
                    
                    if ($conDB->sqlQuery($sql_insert)) {
                        echo "Data inserted successfully\n";
                    } else {
                        echo "Error inserting data\n";
                    }
                }
            }
        }
    } else {
        echo "\n\nInvalid input data or 'value' is not an array.";
    }
} else {
    echo "\n\nInvalid input data.";
}
?>