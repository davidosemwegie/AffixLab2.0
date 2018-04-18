<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 2018-02-21
 * Time: 2:44 AM
 */

//include "mysqlConnector.php";
//
//include "forms/formsInfo.php";
//
//include 'forms/addCustomer.php';

include "../database/db.php";

$worked = 0;

$salesPerson = (int)$_POST["salesPerson"];

//Customer Information
$customerName = $_POST["customerName"];
$address = $_POST["address"];
$community = $_POST["community"];
$phoneNumber = $_POST["community"];
$email = $_POST["email"];

//Sale information
$qDate = (string)$_POST["qDate"];
$qPrice = (double)$_POST["price"];
$exWindowCleaning = null;
$inWindowCleaning = null;
$deckWash = null;
$driveWayWash = null;
$gutterCleaning = null;
$siding = null;

if (isset($_POST["exWindowCleaning"])) {
    $exWindowCleaning = $_POST["exWindowCleaning"];
}
if (isset($_POST["inWindowCleaning"])) {
    $inWindowCleaning = $_POST["inWindowCleaning"];
}
if (isset($_POST["deckWash"])) {
    $deckWash = $_POST["deckWash"];
}
if (isset($_POST["driveWayWash"])) {
    $driveWayWash = $_POST["driveWayWash"];
}
if (isset($_POST["gutterCleaning"])) {
    $gutterCleaning = $_POST["gutterCleaning"];
}
if (isset($_POST["siding"])) {
    $siding = $_POST["siding"];
}


$apDate = (string)$_POST["apDate"];
$apTime = (string)$_POST["apTime"];

$notes = (string)$_POST["notes"];

$worker = (int)$_POST["worker"];

$hours = (double)$_POST["hours"];

$isSale = 0;

if (isset($_POST["isSale"])) {
    $isSale = 1;
}

$regDate = (string)date("Y-m-d");

//Insert new customer
$addCustomer = $con->prepare("INSERT INTO Customer (cname, address, community, phoneNumber, email, regDate) VALUES (?, ?, ?, ?, ?, ?)");

$addCustomer->bind_param("ssisss", $customerName, $address, $community, $phoneNumber, $email, $regDate);
$addCustomer->execute();


//Get New customer Id
$getLastId = $con->query("SELECT max(cid) FROM Customer;");

$lastId = 0;

if ($getLastId->num_rows > 0) {
    while ($row = $getLastId->fetch_assoc()) {
        $lastId = (int)$row["max(cid)"];
    }

}

//Add form info quote
$quoteSQL = "INSERT INTO Quote (cid, eid, qDate, price, apDate, notes, apTime) VALUES ( ?, ?, ?, ?, ?, ?, ?)";

$insertIntoQuote = $con->prepare($quoteSQL);

$insertIntoQuote->bind_param("iisdsss", $lastId, $salesPerson, $qDate, $qPrice, $apDate, $notes, $apTime);
$insertIntoQuote->execute();


//Add form info into sale


//services provided

//Get quote Id
$getLastId = $con->query("SELECT max(qid) FROM Quote;");

$maxqid = 0;

if ($getLastId->num_rows > 0) {
    while ($row = $getLastId->fetch_assoc()) {
        $maxqid = (int)$row["max(qid)"];
    }

}

$sList = [];
$sList[0] = $exWindowCleaning;
$sList[1] = $inWindowCleaning;
$sList[2] = $deckWash;
$sList[3] = $driveWayWash;
$sList[4] = $gutterCleaning;
$sList[5] = $siding;


$addService = $con->prepare("INSERT INTO qService (qid, svid) VALUES (?,?)");

for ($i = 0; $i < count($sList); $i++) {
    if ($sList[$i] != null or $sList[$i] != "") {
        $value = (int)$sList[$i];
        $addService->bind_param("ii", $maxqid, $value);
        if ($addService->execute()) {
            $worked = 1;
        }
    }
}

if ($isSale == 1) {
    $makeSale = $con->prepare("INSERT INTO Sale (finalPrice, qid, eid) VALUES (?, ?, ?)");
    $makeSale->bind_param("dii", $qPrice, $maxqid, $salesPerson);
    $makeSale->execute();

    $getLastId = $con->query("SELECT max(sid) FROM Sale;");

    $maxSid = 0;

    if ($getLastId->num_rows > 0) {
        while ($row = $getLastId->fetch_assoc()) {
            $maxSid = (int)$row["max(sid)"];
        }
    }

    $addHours = $con->prepare("INSERT INTO WorkedOn (sid, eid, hoursWorked) VALUES (?, ?, ?)");
    $addHours->bind_param("iid", $maxSid, $worker, $hours);
    $addHours->execute();

    $updateIsSale = $con->prepare("UPDATE Quote SET isSale = ? WHERE qid = ?");
    $updateIsSale->bind_param("ii", $isSale, $maxqid);
    $updateIsSale->execute();
}

if ($worked == 1) {
    header('Location: sales.php');
}

$con->close();


