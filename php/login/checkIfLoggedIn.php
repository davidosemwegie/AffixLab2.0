<?php
session_start();
$loggedin = true;
if (!isset($_SESSION["userName"])) {
    $loggedin = false;
}

if(!$loggedin){
    header("Location: login/login.html");
}

