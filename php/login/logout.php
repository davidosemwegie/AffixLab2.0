<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 2018-04-02
 * Time: 8:18 PM
 */
session_start();
session_destroy();
header('Location: login.html');
exit();