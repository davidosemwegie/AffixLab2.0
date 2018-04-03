<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 2018-04-03
 * Time: 1:21 AM
 */

include "../database/db.php";

$name = $_POST['ename'];
$address = $_POST['empAddy'];
$phone = $_POST['empPhone'];
$email = $_POST['empEmail'];
$password = $_POST['empPassword'];
$isManager = 0;
$branch = (int)$_POST["branch"];
if (isset($_POST['isManager'])){
    $isManager = 1;
}

$sql = "INSERT INTO Employee (ename, address, phoneNumber, email, password, branch, isManager) VALUES (?, ?, ?, ?, sha1(?), ?, ?)";

$insertIntoEmployee = $con->prepare($sql);
$insertIntoEmployee->bind_param("sssssii", $name, $address, $phone, $email, $password, $branch, $isManager);

$worked = false; //this will be set to true if the query is execute correctly.

if ($insertIntoEmployee->execute()) {
    $worked = 1;
}

if ($worked) {
    header("Location: employees.php");
} else {
    echo "Tears";
}

