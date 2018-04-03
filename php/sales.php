<?php include "login/checkIfLoggedIn.php";
include "../database/db.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/bootstrap.css">

    <link rel="stylesheet" href="../css/body.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <style>
        #rightSide {
            background-color: red;
            height: 93%;
            width: 87.5%;
            position: absolute;
            top: 7%;
            margin-left: 12.5%;
            z-index: -100;
        }

        .tableContainter {
            height: 600px;
            overflow: auto;
        }

    </style>
</head>
<body>
<div class="container-fluid topnav">
    <div class="topBar">
        <a href="#" class="barText" id="pageInfo">Sales</a>
    </div>
</div>
<div id="rightSide">
    <div class="container-fluid">
        <div class="card mb-3" id="dbtable">
            <div class="card-header">
                <i class="fa fa-table"></i> Data Table Example
            </div>
            <div class="card-body">
                <div class = "tableContainter">
                    <table class="table table-striped" id="pageTable" cellspacing="0">
                        <thead>
                        <tr>
                            <td>Customer Name</td>
                            <td>Address</td>
                            <td>Community</td>
                            <td>Phone Number</td>
                            <td>Revenue Provided</td>
                            <td>Membership Length</td>
                        </tr>
                        </thead>
                        <tbody style=" height = 600px; overflow: auto">
                        <?php
                        $listCustomerQuery = "SELECT c.cid, cname, c.address AS theAddress, communityName, c.phoneNumber AS phone, sum(price) AS rev, timestampdiff(YEAR, regDate, curdate()) AS membershipLength
                                FROM Customer AS c
                                LEFT JOIN Community AS cm ON c.community = cm.communityid
                                LEFT JOIN Quote AS q ON q.cid = c.cid AND qid IN (SELECT qid FROM Sale)
                                GROUP BY c.cid
                                ORDER BY rev DESC;";

                        $listOfCustomerResult = $con->query($listCustomerQuery);

                        if ($listOfCustomerResult->num_rows > 0) {
                            while ($row = $listOfCustomerResult->fetch_assoc()) {
                                $memLength = $row['membershipLength'] . " years";

                                if ($row['membershipLength'] == null) {
                                    $memLength = "Just became a member this season";
                                }

                                ?>
                                <tr>
                                    <td><a href="#"><?php echo $row["cname"] ?></a></td>
                                    <td><?php echo $row["theAddress"] ?></td>
                                    <td><?php echo $row["communityName"] ?></td>
                                    <td><?php echo $row["phone"] ?></td>
                                    <td>$<?php echo number_format((float)$row["rev"], 2, '.', ''); ?></td>
                                    <td><?php echo $memLength ?></td>
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
</div>
<?php include "sidebar.php" ?>
<script>
    setActive("qs");

    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

    });
</script>
</body>
<script src="../js/main.js"></script>
</html>