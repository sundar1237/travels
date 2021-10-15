<?php
include 'includes/cons.php';
include 'funcs/order.php';
if(isset($_POST['action']) && "pay_invoice" == $_POST['action']) {
    pay_invoice();
    header('Location: index.php');
}else if (isset($_POST['action']) && "add_new_invoice" == $_POST['action']) {
    
    include 'funcs/Utils.php';
    include 'includes/createNewInvoice.php';
}else if (isset($_GET['action']) && $_GET['action']=="getBalance" ) {
    $id=$_GET['id'];
    return getBalance($id);
}else if (isset($_GET['action']) && $_GET['action']=="delete" ) {
    deleteOrder();
    header('Location: index.php');
}else if (isset($_GET['action']) && $_GET['action']=="export" ) {
   header('Location: index.php');
}else if (isset($_GET['id'])) {
    include 'includes/pageOrder.php';
}?>