<?php
include("config/app_patrol.php");
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

            $result = $conDB->sqlQuery("SHOW COLUMNS FROM `requestprojectcode`");
            $validColumns = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $validColumns[] = $row['Field'];
            }

            $id = null;

            foreach ($item as $key => $value) {
                if (in_array($key, $validColumns)) {
                    if (is_array($value)) {
                        echo "Skipping complex field: " . $key . "\n";
                        continue;
                    } else {
                        $columns[] = "`" . $conDB->escape($key) . "`";
                        $values[] = "'" . $conDB->escape($value) . "'";
                        $updateFields[] = "`" . $conDB->escape($key) . "` = '" . $conDB->escape($value) . "'";

                        if ($key == 'ID') {
                            $id = $value;
                        }
                    }
                }
            }

            if (empty($id)) {
                echo "Skipping due to missing ID\n";
                continue;
            }

            $sql_check = "SELECT COUNT(*) AS count FROM `requestprojectcode` WHERE `ID` = '" . $conDB->escape($id) . "'";
            $checkResult = $conDB->sqlQuery($sql_check);
            $checkRow = mysqli_fetch_assoc($checkResult);

            if ($checkRow['count'] > 0) {
                $sql_update = "UPDATE `requestprojectcode` SET " . implode(", ", $updateFields) . " WHERE `ID` = '" . $conDB->escape($id) . "'";
                echo "\nSQL Query (UPDATE): " . $sql_update . "\n";
                
                if ($conDB->sqlQuery($sql_update)) {
                    echo "Data updated successfully\n";
                } else {
                    echo "Error updating data\n";
                }
            } else {
                if (!empty($columns) && !empty($values)) {
                    $sql_insert = "INSERT INTO `requestprojectcode` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
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