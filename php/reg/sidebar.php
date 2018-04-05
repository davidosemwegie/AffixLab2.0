<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>AFFIX LAB</h3>
        </div>
        <ul class="list-unstyled components">
            <li id="home"><a href="../home/home.php">Home</a></li>
            <?php include "../checkIfAdmin.php";
            if ($isAdmin) {

                ?>
                <li id="qs">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Sales and Quotes</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li><a href="../sales/sales.php">View Sales/Quotes</a></li>
                        <li><a href="../sales/newQuote.php">Add New Quote/Sale</a></li>
                    </ul>
                </li>
                <li id="ei"><a href="../employee/employees.php">Employee Information</a></li>
                <li id="ci"><a href="../customer/customers.php">Customer Information</a></li>
                <?php
            }
            ?>
            <li>
                <button class="btn btn-lg btn-primary btn-block" id="logoutButton"><a
                            href="../login/logout.php">Logout</a></button>
            </li>
        </ul>
    </nav>
</div>