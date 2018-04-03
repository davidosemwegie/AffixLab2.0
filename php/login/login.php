<?php
session_start();
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2018-01-08
 * Time: 6:58 PM
 */

include "../../database/db.php";

//Access the information that was passed from the form
$code = (int) $_POST["code"];
$eid = (int) $_POST["eid"];
$password = (string) $_POST["password"];


//The querying the database to validate the user log in information
$sql = "select eid, ename, branchId, branchName
from Employee as e, Branch as b
where e.branch = b.branchId and branch = ? and eid = ? and password = SHA1(?);";

$rowCount = 0; //initializing the rowCount variable
$result = $con->prepare($sql); //sendind the query to the database
$result -> bind_param("iis", $code, $eid, $password);
$result -> execute();
$result -> bind_result($userId, $userName, $branchId, $userBranchName);

$exists = false;

$_SESSION["userName"] = 0;
$_SESSION["branchName"] = 0;
$_SESSION["userId"] = 0;
$_SESSION["branchId"] =0;

while ($result -> fetch()){
    $exists = true;
    $_SESSION["userName"] = $userName;
    $_SESSION["branchName"] = $userBranchName;
    $_SESSION["userId"] = $userId;
    $_SESSION["branchId"] =$branchId;
}

if($exists){
    header("Location: ../home.php");
} else {
    header("Location: ../../html/login.html");
}
