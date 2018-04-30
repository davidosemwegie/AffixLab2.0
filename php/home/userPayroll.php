<?php session_start();

include '../database/db.php';

//stores the variable that have been submitted by the form

$startDate = 0;
$endDate = 0;
$uid = (int)$_SESSION["userId"];
//check to see if the startData and endDate $_GET variables have been set
if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
}


$totalSales = 0; //initializing a totalSales variable

//query that is getting the total Sale for that user from the database
$getTotalSales = "select sum(finalPrice)
from Sale as s, Quote as q
where s.qid = q.qid and s.eid = $uid and apDate BETWEEN '$startDate' and '$endDate'";

$getTotalSalesResult = $con->query($getTotalSales);
if ($getTotalSalesResult->num_rows > 0) {
    while ($row = $getTotalSalesResult->fetch_assoc()) {
        $totalSales = $row['sum(finalPrice)'];
    }
}
// The total Sales for the user has been stored in the the $totalSales variable.


/*initializing a variable called TotalHours that will stores the total Hours that the user has worked in the
specified time period
*/
$TotalHours = 0;
$getTotalHours = "select sum(hoursWorked)
from WorkedOn as w, Quote as q, Sale as s
where s.qid = q.qid and w.sid = s.sid and w.eid = $uid and apDate BETWEEN '$startDate' and '$endDate'";
$getTotalHoursResult = $con->query($getTotalHours);
if ($getTotalHoursResult->num_rows > 0) {
    while ($row = $getTotalHoursResult->fetch_assoc()) {
        $TotalHours = $row['sum(hoursWorked)'];
    }
}

$wage = 0; //created a variable called wage that will store the user's wage
$wageSQL = "select wage from Employee where eid = $uid";
$getWage = $con->query($wageSQL);
if ($getWage->num_rows > 0) {
    while ($row = $getWage->fetch_assoc()) {
        $wage = $row['wage']; //the wage variable has been set to what is is stored as in the database for that user.
    }
}

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

$doublePayroll = number_format((float)$payroll, 2, '.', '');

echo "<h1 class='text-center'>$" . $payroll . "</h1>";
?>
    <button class="btn btn-lg btn-primary" id="showBreakdown">Show Breakdown</button>
    <div class="card-header sBox">
        <h1>List of all of the Sale you made</h1>
    </div>
    <div class="card-body sBox">
        <div class="tableContainter table-responsive">
            <table class="table table-striped" cellspacing="0" id="userSalesTable">
                <thead class="tHead">
                <tr>
                    <td>Sale Id</td>
                    <td>Customer Name</td>
                    <td>Address</td>
                    <td>Price</td>
                    <td>Earned</td>
                </tr>
                </thead>
                <tbody>
                <?php
                $getSaleSQL = "SELECT sid, cname, c.address, finalPrice
                                FROM Sale AS s, Employee AS e, Quote AS q, Customer AS c
                                WHERE s.eid = e.eid AND s.qid = q.qid AND c.cid = q.cid AND s.eid = $uid and apDate BETWEEN '$startDate' and '$endDate';";
                $listOfSales = $con->query($getSaleSQL);
                if ($listOfSales->num_rows > 0) {
                    while ($row = $listOfSales->fetch_assoc()) {
                        $sid = $row['sid'];
                        $cname = $row['cname'];
                        $address = $row['address'];
                        $price = (double)$row['finalPrice'];
                        $earned = $price * $commision;
                        $doubleEarned = number_format((float)$earned, 2, '.', '');
                        ?>
                        <tr>
                            <td><?php echo $sid ?></td>
                            <td><?php echo $cname ?></td>
                            <td><?php echo $address ?></td>
                            <td>$<?php echo $price ?></td>
                            <td>$<?php echo $doubleEarned ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
                <script>
                    $('#userSalesTable').DataTable();
                </script>
            </table>
        </div>
    </div>
    <div class="card-header sBox">
        <h1>List of all of the houses that you have washed</h1>
    </div>
    <div class="card-body sBox">
        <div class="tableContainter table-responsive">
            <table class="table table-striped" id="workedOnTable" cellspacing="0">
                <thead class="tHead">
                <tr>
                    <td>Sale Id</td>
                    <td>Customer Name</td>
                    <td>Address</td>
                    <td>Employee Name</td>
                    <td>Hours worked</td>
                    <td>Earned</td>
                </tr>
                </thead>
                <tbody>
                <?php
                $getWorkedOn = "SELECT s.sid, cname, e.ename, c.address, hoursWorked
                                FROM Sale AS s, Employee AS e, Quote AS q, Customer AS c, WorkedOn as w
                                WHERE s.eid = e.eid AND s.qid = q.qid AND c.cid = q.cid and w.sid = s.sid
                                AND w.eid = $uid and apDate BETWEEN '$startDate' and '$endDate';";
                $workedOnList = $con->query($getWorkedOn);
                if ($workedOnList->num_rows > 0) {
                    while ($row = $workedOnList->fetch_assoc()) {
                        $sid = $row['sid'];
                        $cname = $row['cname'];
                        $address = $row['address'];
                        $ename = $row['ename'];
                        $hours = (double)$row['hoursWorked'];
                        $earned = $hours * $wage;

                        ?>
                        <tr>
                            <td><?php echo $sid ?></td>
                            <td><?php echo $cname ?></td>
                            <td><?php echo $address ?></td>
                            <td><?php echo $ename ?></td>
                            <td><?php echo $hours ?></td>
                            <td>$<?php echo $earned ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
                <script>
                    $('#workedOnTable').DataTable();
                </script>
            </table>
        </div>
    </div>
    <script>
        $('.sBox').hide();

        $('#showBreakdown').click(function () {
            $('.sBox').toggle();
        });
    </script>
<?php
