<?php
include("config/app_emp.php");
$conDB_she = new db_conn("italthai_safety_report", "italthai_safety_report", "Ite@1234!");
$conDB_data = new db_conn("italthai_data", "italthai_italthai", "aM5vUe341w");

// คิวรี่เอาชื่อแบบไม่ซ้ำจากตาราง daily_report_header ในฐานข้อมูล she_it
$sql_distinct_names = "SELECT DISTINCT `created_by` FROM `daily_report_header`";
$query_names = $conDB_she->sqlQuery($sql_distinct_names);

if ($query_names) {
    while ($row = mysqli_fetch_assoc($query_names)) {
        $name = $row['created_by']; // ชื่อจาก daily_report_header
        
        // อัพเดตฟิล safety_officer ในตาราง employees ในฐานข้อมูล data
        $sql_update = "UPDATE `employees` SET `safety_officer` = 1 WHERE `displayName` = '" . $conDB_data->escape($name) . "'";
        $update_result = $conDB_data->sqlQuery($sql_update);

        if ($update_result) {
            echo "Updated safety_officer for: " . $name . "\n";
        } else {
            echo "Failed to update safety_officer for: " . $name . "\n";
        }
    }
} else {
    echo "Failed to fetch distinct names from daily_report_header.\n";
}

?>
