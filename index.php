<?php
include 'includes/cons.php';
if (isset($_POST['action']) && "verifyUser" == $_POST['action']) {
    include 'funcs/Utils.php';
    verifyUser();
}else if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
}else{
    include_once 'includes/pageIndex.php';
}?>