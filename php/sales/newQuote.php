<?php include "../login/checkIfLoggedIn.php";
include "../database/db.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Quote</title>
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
        #formBox {
            display: block;
        }
    </style>
</head>
<body>
<div class="container-fluid topnav">
    <div class="topBar">
        <a href="#" class="barText" id="pageInfo">Add New Quote/Sale</a>
    </div>
</div>
<div id="rightSide">
    <?php
    include "../checkIfAdmin.php";

    if ($isAdmin) {
        ?>
        <div class="container well pageForm" id="formBox">
            <form action="" method="post">
                <h1 class="text-lg-center">Customer Information</h1>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cname">Customer Name</label>
                        <input type="text" id="cname" class="form-control" name="customerName"
                               placeholder="Customer Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="addy">Address</label>
                        <input type="text" id="addy" class="form-control" name="address" placeholder="Customer Address">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="community">Community</label>
                        <div class="container">
                            <select name="community" id="community" class="selectpicker">
                                <?php
                                $EmployeeList = "SELECT communityId, communityName FROM Community";

                                $eListResult = $con->query($EmployeeList);
                                if ($eListResult->num_rows > 0) {
                                    while ($row = $eListResult->fetch_assoc()) {
                                        ?>
                                        <option
                                        value="<?php echo $row['communityId'] ?>"><?php echo $row['communityName'] ?></option><?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="text" class="form-control" name="cphonenumber" placeholder="Customer Phone Number">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email">Customer Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Customer Email Address">
                    </div>
                </div>
                <h1 class="text-lg-center">Sale Information</h1>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="qDate">Quote Date</label>
                        <input type="date" class="form-control" name="qDate" id="qDate">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="apDate">Appointment Date</label>
                        <input type="date" class="form-control" name="apDate" id="apDate">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="apTime">Appointment Time</label>
                        <input type="time" class="form-control" name="apTime" id="apTime">
                    </div>
                </div>
            </form>
        </div>
        <?php
    } else {
        include "../noAccessError.php";
    }
    ?>
</div>
<?php include "../reg/sidebar.php" ?>
<script>
    setActive("qs");

    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        $('#pageTable').DataTable();
        $('#saleTable').DataTable();
    });
</script>
</body>
<script src="../../js/main.js"></script>
</html>