<?php include "../login/checkIfLoggedIn.php";
include "../database/db.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quotes and Sales</title>
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
        #quoteBox {
            display: none;
        }
    </style>
</head>
<body>
<div class="container-fluid topnav">
    <div class="topBar">
        <a href="#" class="barText" id="pageInfo">Quotes and Sale Information</a>
        <button type="button" class="btn btn-info btn-lg" id="viewSalesButton">Sales</button>
        <button type="button" class="btn btn-info btn-lg" id="viewQuotesButton">Quotes</button>
    </div>
</div>
<div id="rightSide">
    <?php
    include "../checkIfAdmin.php";

    if ($isAdmin) {
        ?>
        <div class="container-fluid" id="salesBox">
            <div class="card mb-3" id="card">
                <div class="card-header">
                    <h1>List of all Sales</h1>
                </div>
                <div class="card-body">
                    <div class="tableContainter table-responsive">
                        <table class="table table-striped" id="pageTable" cellspacing="0">
                            <thead>
                            <tr>
                                <td>Sale Id</td>
                                <td>Quote Date</td>
                                <td>Employee Name</td>
                                <td>Customer Name</td>
                                <td>Address</td>
                                <td>Price</td>
                                <td>Notes</td>
                            </tr>
                            </thead>
                            <tbody style=" height = 600px; overflow: auto">
                            <?php
                            $listQuoteQuery = "SELECT s.sid, qDate, ename, cname, c.address, price, apDate, apTime, notes, isSale
                                                FROM Quote AS q, Employee AS e, Customer AS c, Sale AS s
                                                WHERE q.eid = e.eid AND q.cid = c.cid AND s.qid = q.qid;";

                            $listOfQuoteResult = $con->query($listQuoteQuery);

                            if ($listOfQuoteResult->num_rows > 0) {
                                while ($row = $listOfQuoteResult->fetch_assoc()) {

                                    $apDate = $row['apDate'];
                                    $apTime = $row['apTime'];
                                    $isSale = $row['isSale'];
                                    ?>
                                    <tr>
                                        <td><?php echo $row['sid'] ?></td>
                                        <td><?php echo $row["qDate"] ?></td>
                                        <td><?php echo $row["ename"] ?></td>
                                        <td><?php echo $row["cname"] ?></td>
                                        <td><?php echo $row["address"] ?></td>
                                        <td><?php echo '$' . $row['price'] ?></td>
                                        <td><?php echo $row['notes'] ?></td>
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
        <div class="container-fluid" id="quoteBox">
            <div class="card mb-3" id="card">
                <div class="card-header">
                    <h1>List of all Quotes</h1>
                </div>
                <div class="card-body">
                    <div class="tableContainter table-responsive">
                        <table class="table table-striped" id="saleTable" cellspacing="0">
                            <thead>
                            <tr>
                                <td>Quote Id</td>
                                <td>Quote Date</td>
                                <td>Employee Name</td>
                                <td>Customer Name</td>
                                <td>Address</td>
                                <td>Price</td>
                                <td>Notes</td>
                            </tr>
                            </thead>
                            <tbody class="tbody">
                            <?php
                            $listQuoteQuery = "SELECT qid, qDate, ename, cname, c.address, price, apDate, apTime, notes, isSale
                                FROM Quote AS q, Employee AS e, Customer AS c
                                WHERE q.eid = e.eid AND q.cid = c.cid
                                ORDER BY qid DESC;";

                            $listOfQuoteResult = $con->query($listQuoteQuery);

                            if ($listOfQuoteResult->num_rows > 0) {
                                while ($row = $listOfQuoteResult->fetch_assoc()) {

                                    $apDate = $row['apDate'];
                                    $apTime = $row['apTime'];
                                    $isSale = $row['isSale'];
                                    ?>
                                    <tr style="background-color: <?php if ($isSale == 0) {
                                        echo "rgba(255, 255, 255, 1)";
                                    } else {
                                        echo "rgba(34, 214, 205, 0.5)";
                                    } ?>">
                                        <td><?php echo $row['qid'] ?></td>
                                        <td><?php echo $row["qDate"] ?></td>
                                        <td><?php echo $row["ename"] ?></td>
                                        <td><?php echo $row["cname"] ?></td>
                                        <td><?php echo $row["address"] ?></td>
                                        <td><?php echo '$' . $row['price'] ?></td>
                                        <td><?php echo $row['notes'] ?></td>
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
    setActive("qs");

    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        $('#pageTable').DataTable();
        $('#saleTable').DataTable();

        $('#viewSalesButton').click(function () {
            $("#salesBox").show();
            $('#quoteBox').hide();
        });

        $('#viewQuotesButton').click(function () {
            $("#salesBox").hide();
            $('#quoteBox').show();
        });
    });
</script>
</body>
<script src="../../js/main.js"></script>
</html>