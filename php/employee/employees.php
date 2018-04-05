<?php include "../login/checkIfLoggedIn.php";
include "../database/db.php" ?>
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

    <style>


    </style>
</head>
<body>
<div class="container-fluid topnav">
    <div class="topBar">
        <a href="#" class="barText" id="pageInfo">Employee Information</a>
        <button type="button" class="btn btn-info btn-lg" id="newButton">Add New Employee</button>
        <button type="button" class="btn btn-info btn-lg" id="payrollButton">Check Payroll</button>
    </div>
</div>
<div id="rightSide">
    <?php
    include "../checkIfAdmin.php";

    if ($isAdmin) {

        ?>
        <div class="container well pageForm" id="payrollBox">
            <form action="payroll.php" method="post">
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
        <div class="container well pageForm" id="addEmpBox">
            <form action="addEmployee.php" method="post">
                <h1 class="text-center">Add New Employee</h1>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">First and Last Name</label>
                        <input type="text" class="form-control" placeholder="First and Last Name" id="name"
                               name="ename">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" placeholder="Temporary Password" id="password"
                               name="empPassword">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" placeholder="Address" id="address" name="empAddy">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" placeholder="Email Address" id="email" name="empEmail">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" placeholder="Phone Number" id="phone" name="empPhone">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="branch">Branch</label>
                        <div>
                            <select name="branch" id="branch" class="selectpicker">
                                <?php
                                $branchList = "SELECT branchId, branchName FROM Branch";

                                $bListResult = $con->query($branchList);
                                if ($bListResult->num_rows > 0) {
                                    while ($row = $bListResult->fetch_assoc()) {
                                        ?>
                                        <option
                                        value="<?php echo $row['branchId'] ?>"><?php echo $row['branchName'] ?></option><?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isManager">Is this person a manager?</label>
                        <input type="radio" class="form-control col-md-6" value="1" name="isManager" id="isManager">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sDate">Start Day</label>
                        <input type="date" class="form-control" name="startDate" id="sDate">
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-lg btn-primary btn-block" value="Add Employee">
                </div>
            </form>
        </div>
        <div class="container-fluid">
            <div class="card mb-3" id="card">
                <div class="card-header">
                    <h1>List of all Employees</h1>
                </div>
                <div class="card-body">
                    <div class="tableContainter table-responsive">
                        <table class="table table-striped" id="pageTable" cellspacing="0">
                            <thead class="tHead">
                            <tr>
                                <td>Employee Id</td>
                                <td>Employee Name</td>
                                <td>Address</td>
                                <td>Phone Number</td>
                                <td>Branch Name</td>
                                <td># of Hours Worked</td>
                                <td>Total Sales</td>
                                <td>Employment Length</td>
                            </tr>
                            </thead>
                            <tbody class="tBody">
                            <?php
                            $listEmployeeQuery = "SELECT Employee.eid, ename, address, branchName, phoneNumber, coalesce(sum(hoursWorked),0) AS sumHours, coalesce(sum(price),0) AS sumSales, timestampdiff(WEEK, startDate, curdate()) AS workedSince
                                FROM Employee
                                LEFT JOIN Branch ON Employee.branch = Branch.branchId
                                LEFT JOIN WorkedOn ON WorkedOn.eid = Employee.eid
                                LEFT JOIN Quote ON Quote.eid = Employee.eid AND qid IN (SELECT qid FROM Sale)
                                GROUP BY Employee.eid;";

                            $listOfEmployeeResult = $con->query($listEmployeeQuery);

                            if ($listOfEmployeeResult->num_rows > 0) {
                                while ($row = $listOfEmployeeResult->fetch_assoc()) {
                                    $started = $row["workedSince"];
                                    $workedSince = 0;
                                    if ($started == -1) {
                                        $workedSince = "starts next week";
                                    } elseif ($started < 0) {

                                        $workedSince = "starts in " . abs($started) . " weeks";
                                    } else {
                                        $workedSince = $started . " weeks agp";
                                    }


                                    ?>
                                    <tr>
                                        <td><?php echo $row["eid"] ?></td>
                                        <td><a href="#"><?php echo $row["ename"] ?></a></td>
                                        <td><?php echo $row["address"] ?></td>
                                        <td><?php echo $row["phoneNumber"] ?></td>
                                        <td><?php echo $row["branchName"] ?></td>
                                        <td><?php echo $row["sumHours"] ?></td>
                                        <td>$<?php echo $row["sumSales"] ?></td>
                                        <td><?php echo $workedSince ?></td>
                                    </tr>
                                    <?php
                                }
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
        })
    });
</script>
</body>
<script src="../../js/main.js"></script>
</html>