<?php
include 'includes/cons.php';
if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
}else if (isset($_GET['id'])){
    include 'funcs/Utils.php';
    include 'includes/pageApartment.php';
}else if (isset($_POST['action']) && "edit_apartment" == $_POST['action']){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $rent = $_POST['rent'];
    $advance = $_POST['advance'];
    $sql="update apartments set apartment_name=".cheSNull($name).", advance=".cheNull($advance).", rent=".cheNull($rent)." where id=".$id;
    executeSQL($sql);
    header('Location: apartment.php?id='.$id);
}else if (isset($_POST['action']) && "updateApartmentStatus" == $_POST['action']){
    $id = $_POST['id'];
    $status = $_POST['status'];
    if ("Empty"==$status){
        $tid=getSingleValue("SELECT id from tenants where apartment_id= ".$id);
        executeSQL("delete from tenants where id = ".$tid);
    }
    executeSQL("update apartments set status=".cheSNull($status).", modified_date=CURRENT_DATE where id=".$id);
    
}/*else if (isset($_POST['action']) && "add_tenant" == $_POST['action']){
    $apartment_id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mobile_no_1 = $_POST['mobile_no_1'];
    $mobile_no_2 = $_POST['mobile_no_2'];
    $occupatation = $_POST['occupatation'];
    $occupied_since = $_POST['occupied_since'];
    $comments = $_POST['comments'];
    
}*/
?>