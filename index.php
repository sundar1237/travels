<?php
include 'includes/cons.php';
if (isset($_POST['action']) && "verifyUser" == $_POST['action']) {
    include 'funcs/Utils.php';
    verifyUser();
}else if (isset($_POST['action']) && "confirm_new_invoice" == $_POST['action']) {
    include 'funcs/order.php';
    insertOrder();
    header('Location: index.php');
}else if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
}else if (isset($_GET['action']) && $_GET['action']=="view_airports" ) {
    include_once 'includes/pageAirports.php';
}else if (isset($_GET['action']) && $_GET['action']=="view_airlines" ) {
    include_once 'includes/pageAirlines.php';
}else{
    include_once 'funcs/Utils.php';
    include_once 'includes/pageIndex.php';
}?>