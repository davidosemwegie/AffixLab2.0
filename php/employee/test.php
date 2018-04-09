<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 2018-04-07
 * Time: 6:24 PM
 */
$startDate = 0;
$endDate = 0;
if (isset($_GET['startDate']) || isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = date_create($_GET['endDate']);
}
$sDate = date_format($startDate,"Y/m/d");
$eDate = date_format($endDate,"Y/m/d");


echo $startDate;