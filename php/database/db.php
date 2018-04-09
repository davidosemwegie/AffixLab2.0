<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 2018-04-02
 * Time: 6:18 PM
 */

ini_set('display_errors',1);
error_reporting(E_ALL);

//This is connection to the database
//$con = new mysqli("localhost", "root", "root", "AffixLab");
$con = new mysqli("138.197.169.152", "root", "Ronaldinho123!", "AffixLab");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}