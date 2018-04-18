<?php session_start();

include '../database/db.php';

$startDate = 0;
$endDate = 0;
$uid = (int)$_SESSION["userId"];
if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
}


$totalSales = 0;
$getTotalSales = "select sum(finalPrice)
from Sale as s, Quote as q
where s.qid = q.qid and s.eid = $uid and apDate BETWEEN '$startDate' and '$endDate'";
$getTotalSalesResult = $con->query($getTotalSales);
if ($getTotalSalesResult->num_rows > 0) {
    while ($row = $getTotalSalesResult->fetch_assoc()) {
        $totalSales = $row['sum(finalPrice)'];
    }
}

$TotalHours = 0;
$getTotalHours = "select sum(hoursWorked)
from WorkedOn as w, Quote as q, Sale as s
where s.qid = q.qid and w.sid = s.sid and s.eid = $uid and apDate BETWEEN '$startDate' and '$endDate'";
$getTotalHoursResult = $con->query($getTotalHours);
if ($getTotalHoursResult->num_rows > 0) {
    while ($row = $getTotalHoursResult->fetch_assoc()) {
        $TotalHours = $row['sum(hoursWorked)'];
    }
}

$wage = 14;
$commision = 0;

$sales = (double)$totalSales;
$hours = (double)$TotalHours;

$labourPay = $hours * $wage;

if ($sales < 1000) {
    $commision = 0.2;
} elseif ($sales >= 1000 && $sales < 2000) {
    $commision = 0.25;
} else ($sales >= 2000 && $sales < 3000){
$commision = 0.3
};

$salesPay = $sales * $commision;

$payroll = $salesPay + $labourPay;

echo $payroll;


//$getPayRoll = $con->query($sql);
//if ($getPayRoll->num_rows > 0) {
//    while ($row = $getPayRoll->fetch_assoc()) {
//        $wage = 14;
//        $commision = 0;
//
//        $sales = (double)$row['totalSales'];
//        $hours = (double)$row['totalHours'];
//
//        $labourPay = $hours * $wage;
//
//        if ($sales < 1000) {
//            $commision = 0.2;
//        } elseif ($sales >= 1000 && $sales < 2000) {
//            $commision = 0.25;
//        } else ($sales >= 2000 && $sales < 3000){
//        $commision = 0.3
//        };
//
//        $salesPay = $sales * $commision;
//
//        $payroll = $salesPay + $labourPay;
//
//        echo $payroll;
//    }
//} else {
//    echo "There is no payroll for this period";
//}