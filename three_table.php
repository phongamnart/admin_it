<?php
include("_check_session.php");
$conDB = new db_conn();

$strSQL = "SELECT `tb_project_information`.*, `projectdescription`.* FROM `tb_project_information` 
LEFT JOIN `projectdescription` ON `tb_project_information`.`RPCID` = `projectdescription`.`RPCID`;";
$objQuery = $conDB->sqlQuery($strSQL);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>RPCID</th>
                <th>ProjectCode</th>
                <th>ProjectNameTH</th>
                <th>OwnerNameTH</th>
                <th>ProjectNameEN</th>
                <th>OwnerNameEN</th>
                <th>PMName</th>
                <th>PMEmail</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($objResult = mysqli_fetch_assoc($objQuery)) {
            ?>
                <tr>
                    <td><?php echo $objResult['RPCID'] ?></td>
                    <td><?php echo $objResult['ProjectCode'] ?></td>
                    <td><?php echo $objResult['ProjectNameTH'] ?></td>
                    <td><?php echo $objResult['OwnerNameTH'] ?></td>
                    <td><?php echo $objResult['ProjectNameEN'] ?></td>
                    <td><?php echo $objResult['OwnerNameEN'] ?></td>
                    <td><?php echo $objResult['PMName'] ?></td>
                    <td><?php echo $objResult['PMEmail'] ?></td>
                <?php } ?>
        </tbody>
    </table>
</body>

</html>