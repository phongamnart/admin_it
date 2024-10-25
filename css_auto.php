<?php 
include("config/app_css.php");
$conDB = new db_conn();

$inputJSON = file_get_contents('php://input');

echo "Raw JSON Data Received:\n";
echo $inputJSON;

$input = json_decode($inputJSON, true);

if (is_array($input)) {
    echo "\n\nParsed Data:\n";

    $columns = [];
    $values = [];
    $updateFields = [];

    $result = $conDB->sqlQuery("SHOW COLUMNS FROM `tb_project_information`");
    $validColumns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $validColumns[] = $row['Field'];
    }

    $id = null;

    foreach ($input as $key => $value) {
        if ($key === 'ID') {
            $key = 'pj_id';
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

    if (empty($id)) {
        echo "Skipping due to missing ID\n";
        exit;
    }

    $sql_check = "SELECT COUNT(*) AS count FROM `tb_project_information` WHERE `pj_id` = '" . $conDB->escape($id) . "'";
    $checkResult = $conDB->sqlQuery($sql_check);
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['count'] > 0) {
        $sql_update = "UPDATE `tb_project_information` SET " . implode(", ", $updateFields) . " WHERE `pj_id` = '" . $conDB->escape($id) . "'";
        echo "\nSQL Query (UPDATE): " . $sql_update . "\n";

        if ($conDB->sqlQuery($sql_update)) {
            echo "Data updated successfully\n";
        } else {
            echo "Error updating data\n";
        }
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
} else {
    echo "\n\nInvalid input data.";
}
?>