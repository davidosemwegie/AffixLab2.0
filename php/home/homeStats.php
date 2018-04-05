<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 2018-04-03
 * Time: 1:38 PM
 */
include "../database/db.php";

$userId = (int) $uid;



//Average Sales / Week (Calculated Since their first day of work
$averageSalesPerWeek = 0;
$sql = "select sum(finalPrice)/timestampdiff(WEEK, startDate, curdate()) as result
from Quote as q, Sale as s, Employee as e
where q.qid = s.qid and q.eid = e.eid and e.eid = $userId;";

$result = $con->query($sql);
if ($result -> num_rows > 0){
    while ($row = $result -> fetch_assoc()) {
        $averageSalesPerWeek = number_format((float)$row['result'], 2, '.', '');;
    }
}


//Average Number of Sale Per week
$averageNumberSalesPerWeek = 0;
$sql = "select count(sid)/timestampdiff(WEEK, startDate, curdate()) as result
from Quote as q, Sale as s, Employee as e
where q.qid = s.qid and q.eid = e.eid and e.eid = $userId;";

$result = $con->query($sql);
if ($result -> num_rows > 0){
    while ($row = $result -> fetch_assoc()) {
        $averageNumberSalesPerWeek = number_format((float)$row['result'], 2, '.', '');;
    }
}

//Average number of quotes per week
$averageQuotesPerWeek = 0;
$sql = "select count(qid)/timestampdiff(WEEK, startDate, curdate()) as result
from Quote as q, Employee as e
where q.eid = e.eid and e.eid = $userId;";

$result = $con->query($sql);
if ($result -> num_rows > 0){
    while ($row = $result -> fetch_assoc()) {
        $averageQuotesPerWeek = number_format((float)$row['result'], 2, '.', '');;
    }
}

//Total number of hours worked
$hoursWorked = 0;
$sql = "SELECT coalesce(sum(hoursWorked),0) as result
from WorkedOn
where eid = $userId;";

$result = $con->query($sql);
if ($result -> num_rows > 0){
    while ($row = $result -> fetch_assoc()) {
        $hoursWorked = number_format((float)$row['result'], 2, '.', '');;
    }
}

$con -> close();