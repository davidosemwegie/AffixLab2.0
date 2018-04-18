<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 2018-04-03
 * Time: 12:58 PM
 */

include "../database/db.php";

$eid  = $_SESSION["userId"];

$sql = "SELECT sum(finalPrice)
        FROM Sale as s
        WHERE eid = $eid;";

$result = $con->query($sql);

$totalSales = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totalSales = $row["sum(finalPrice)"];
    }
}

$totalSales = number_format((float)$totalSales, 2, '.', '');