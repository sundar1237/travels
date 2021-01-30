<?php
include 'includes/cons.php';
if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
} else if (isset($_GET['action']) && "edit" == $_GET['action']) {
    $id = $_GET['id'];
    include 'funcs/Utils.php';
    echo showFormToUpdateHouse($id);
}else if (isset($_POST['action']) && "update_house" == $_POST['action']) {
    $id=$_POST['id'];
    include 'funcs/Utils.php';
    updateHouse($id);
    header('Location: house.php?id='.$id);
}else if (isset($_GET['id'])){
    include 'includes/pageHouseSingle.php';
}else{
    include 'includes/pageHouseAll.php';
} ?>