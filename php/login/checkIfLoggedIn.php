<?php
session_start();
$loggedin = true;
if (!isset($_SESSION["userName"])) {
    $loggedin = false;
}

$name = 0;
$uid = 0;

if(!$loggedin){
    header("Location: login/login.html");
} else {
    $name = $_SESSION["userName"];
    $uid = $_SESSION["userId"];
}

