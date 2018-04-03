<?php include "login/checkIfLoggedIn.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/bootstrap.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="../js/main.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="topBar">
        <a href="sales.php" class="barText" id="pageInfo">Total Sales: <?php echo "$" . $totalSales ?></a>
        <a href="home.php" class="barText" id="homeButton">HOME</a>
    </div>
</div>
<?php include "sidebar.php" ?>

</body>
<script>
    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

    });

    setActive("home");
</script>
</html>