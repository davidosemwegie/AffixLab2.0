<?php
$isAdmin = false;
if ($_SESSION["isManager"] == 1) {
    $isAdmin = true;
}
