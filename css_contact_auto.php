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

    $result = $conDB->sqlQuery("SHOW COLUMNS FROM `tb_contact`");
    $validColumns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $validColumns[] = $row['Field'];
    }

    $email = null;

    foreach ($input as $key => $value) {
        if (in_array($key, $validColumns)) {
            if (is_array($value)) {
                echo "Skipping complex field: " . $key . "\n";
                continue;
            } else {
                $columns[] = "`" . $conDB->escape($key) . "`";
                $values[] = "'" . $conDB->escape($value) . "'";
                $updateFields[] = "`" . $conDB->escape($key) . "` = '" . $conDB->escape($value) . "'";

                if ($key == 'Payer_Email') {
                    $email = $value;
                }
            }
        }
    }

    if (empty($email)) {
        echo "Skipping due to missing Payer_Email\n";
        exit;
    }

    $sql_check = "SELECT COUNT(*) AS count FROM `tb_contact` WHERE `Payer_Email` = '" . $conDB->escape($email) . "'";
    $checkResult = $conDB->sqlQuery($sql_check);
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['count'] > 0) {
        $sql_update = "UPDATE `tb_contact` SET " . implode(", ", $updateFields) . " WHERE `Payer_Email` = '" . $conDB->escape($email) . "'";
        echo "\nSQL Query (UPDATE): " . $sql_update . "\n";

        if ($conDB->sqlQuery($sql_update)) {
            echo "Data updated successfully\n";
        } else {
            echo "Error updating data\n";
        }
    } else {
        if (!empty($columns) && !empty($values)) {
            $sql_insert = "INSERT INTO `tb_contact` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
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