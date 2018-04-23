<?php include "../login/checkIfLoggedIn.php";
include "../database/db.php";
include "userTotalSales.php";
include "homeStats.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
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

    <style>
        #payrolBox {
            display: none;
        }

    </style>
</head>
<body>
<div class="container-fluid topnav">
    <div class="topBar">
        <a href="#" class="barText" id="pageInfo">Your Total Sales: $<?php echo $totalSales ?></a>
    </div>
</div>
<div id="rightSide" style="background-color: grey">
    <?php
    include "../checkIfAdmin.php";
    ?>
    <div class="container-fluid" id="homeBox">
        <div class="container card">
            <div class="card-header">
                <h1 class="text-lg-center"><?php echo $name ?></h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="card mb-3 homegrid">
                        <div class="card-header">Average Sales</div>
                        <div class="card-body text-md-center">$<?php echo $averageSalesPerWeek ?>/Week</div>
                    </div>
                    <div class="card mb-3 homegrid">
                        <div class="card-header">Average Number of Sales</div>
                        <div class="card-body text-md-center"><?php echo $averageNumberSalesPerWeek ?>/Week</div>
                    </div>
                    <div class="card mb-3 homegrid">
                        <div class="card-header">Average Number of Quotes</div>
                        <div class="card-body text-md-center"><?php echo $averageQuotesPerWeek ?>/Week</div>
                    </div>
                    <div class="card mb-3 homegrid">
                        <div class="card-header">Total Hours Worked</div>
                        <div class="card-body text-md-center"><?php echo $hoursWorked ?></div>
                    </div>
                </div>
                <div class="container well">
                    <form action="#" method="get" id="payrollForm">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sDate">Start Date</label>
                                <input type="date" class="form-control" placeholder="Start Date" id="sDate"
                                       name="startDate">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="eDate">End Date</label>
                                <input type="date" class="form-control" placeholder="End Date" id="eDate"
                                       name="endDate">
                            </div>
                        </div>
                        <div class="form-group">
                            <!--                            <input type="submit" class="btn btn-lg btn-primary btn-block" value="Check Payroll">-->
                            <button id="#checkButton" class="btn btn-lg btn-primary btn-block">Check payroll</button>
                        </div>
                    </form>
                </div>
                <div class="container well" id="payrolBox">
                    <p id="userPayroll" class="text-center text-info"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../reg/sidebar.php" ?>
<script>
    setActive("home");

    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        $('#pageTable').DataTable();

        $('#payrollForm').submit(function (e) {

            var startDate = $('#sDate').val();
            var endDate = $('#eDate').val();

            var results = $.get("userPayroll.php?startDate=" + startDate +"&endDate=" +endDate);
            results.done(function (data) {
                //id = "#commentList" + pid;
                //console.log(id);
                var payRollBox = $('#payrolBox');
                payRollBox.show();

                var resp = "Your payroll for the period you have selected is: <br>";

                $('#userPayroll').html(resp+"$"+data);
                console.log(data);
            });
            results.fail(function (jqXHR) {
                console.log("Error: " + jqXHR.status);
            });
            results.always(function () {
                //console.log("done");
            });

            e.preventDefault();
        });

    });
</script>
</body>
<script src="../../js/main.js"></script>
</html>
