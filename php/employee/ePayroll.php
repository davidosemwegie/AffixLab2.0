<?php include "../login/checkIfLoggedIn.php";
include "../database/db.php";
$startDate = 0;
$endDate = 0;
if (isset($_GET['startDate']) || isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee</title>
    <link rel="stylesheet" href="../../css/style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="../../css/bootstrap.css">

    <link rel="stylesheet" href="../../css/body.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../../js/main.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script src="../../js/jquery.tabletoCSV.js"></script>
    <style>


    </style>
</head>
<body>
<div class="container-fluid topnav">
    <div class="topBar">
        <a href="#" class="barText" id="pageInfo">Employee Information</a>
        <button type="button" class="btn btn-info btn-lg" id="payrollButton">Check Payroll</button>
    </div>
</div>
<div id="rightSide">
    <?php
    include "../checkIfAdmin.php";

    if ($isAdmin) {
        ?>
        <div class="container well pageForm" id="payrollBox">
            <form action="ePayroll.php" method="get" id="payrollTable">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sDate">Start Date</label>
                        <input type="date" class="form-control" placeholder="Start Date" id="sDate" name="startDate">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="eDate">End Date</label>
                        <input type="date" class="form-control" placeholder="End Date" id="eDate" name="endDate">
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-lg btn-primary btn-block" value="Check Payroll">
                </div>
            </form>
        </div>
        <div class="container-fluid">
            <div class="card mb-3" id="card">
                <div class="card-header">
                    <h1><?php echo "Payroll: $startDate to $endDate" ?></h1>
                </div>
                <div class="card-body">
                    <div class="tableContainter table-responsive">
                        <table class="table table-striped" id="pageTable" cellspacing="0">
                            <thead class="tHead">
                            <tr>
                                <td>Name</td>
                                <td>Total Sales</td>
                                <td>Total Hours</td>
                                <td>Sales Pay</td>
                                <td>Labour Pay</td>
                                <td>Total Pay</td>
                            </tr>
                            </thead>
                            <tbody class="tBody">
                            <?php
                            $listOfEid = "SELECT DISTINCT e.eid, ename
                                        FROM Employee as e, Quote as q
                                        where e.eid in (SELECT eid FROM Sale) 
                                        or e.eid in (SELECT eid from WorkedOn)
                                        or apDate BETWEEN '$startDate' and '$endDate';";
                            $getList = $con->query($listOfEid);
                            if ($getList->num_rows > 0) {
                                while ($row = $getList->fetch_assoc()) {
                                    $eid = $row['eid'];
                                    $ename = $row['ename'];


                                    $totalSales = 0;
                                    $getTotalSales = "select sum(finalPrice)
                                                        from Sale as s, Quote as q
                                                        where s.qid = q.qid and s.eid = $eid and apDate BETWEEN '$startDate' and '$endDate'";
                                    $getTotalSalesResult = $con->query($getTotalSales);
                                    if ($getTotalSalesResult->num_rows > 0) {
                                        while ($row = $getTotalSalesResult->fetch_assoc()) {
                                            $totalSales = $row['sum(finalPrice)'];
                                        }
                                    }

                                    $TotalHours = 0;
                                    $getTotalHours = "select sum(hoursWorked)
                                                        from WorkedOn as w, Quote as q, Sale as s
                                                        where s.qid = q.qid and w.sid = s.sid and s.eid = $eid and apDate BETWEEN '$startDate' and '$endDate'";
                                    $getTotalHoursResult = $con->query($getTotalHours);
                                    if ($getTotalHoursResult->num_rows > 0) {
                                        while ($row = $getTotalHoursResult->fetch_assoc()) {
                                            $TotalHours = $row['sum(hoursWorked)'];
                                        }
                                    }

                                    $wage = 14;
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

                                    if ($payroll != 0){
                                        ?>
                                        <tr>
                                            <td><?php echo $ename ?></td>
                                            <td>$<?php echo $sales ?></td>
                                            <td><?php echo $hours ?></td>
                                            <td>$<?php echo $salesPay ?></td>
                                            <td>$<?php echo $labourPay ?></td>
                                            <td>$<?php echo $payroll ?></td>
                                        </tr>
                                        <?
                                    }
                                }
                            } else {
                                echo "<h1 class='pageName'>There is no payroll for this period</h1>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        include "../noAccessError.php";
    }
    ?>
</div>
<?php include "../reg/sidebar.php" ?>
<script>
    setActive("ei");

    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        $('#pageTable').DataTable();

        $('#newButton').click(function () {
            $("#addEmpBox").toggle();
        });

        $('#payrollButton').click(function () {
            $("#payrollBox").toggle();
        });

        $("#export").click(function () {
            $("#payrollTable").tableToCSV("<?php echo "Payroll($startDate to $endDate)"?>");
        });
    });
</script>
</body>
<script src="../../js/main.js"></script>
</html>