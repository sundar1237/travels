<?php
include 'includes/cons.php';
if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
} else if (isset($_GET['action']) && "addamount" == $_GET['action']) {
    include 'funcs/tenant/addAmount.php';
    addAmount();
}else if (isset($_GET['action']) && "edit" == $_GET['action']) {
    $id = $_GET['id'];
    include 'funcs/tenant/utils.php';
    echo showFormToUpdateTenant($id);
}else if (isset($_POST['action']) && "update_tenant" == $_POST['action']) {
    $id=$_POST['id'];
    include 'funcs/tenant/utils.php';
    updateTenant($id);
    header('Location: tenant.php?id='.$id);
}else if (isset($_GET['id']) && isset($_GET['action']) && "delete_doc" == $_GET['action']) {
    include 'funcs/tenant/utils.php';
    $parent_id = deleteDocument($_GET['id']);
    header('Location: tenant.php?id='.$parent_id);
}else if (isset($_POST['action']) && "change_photo" == $_POST['action']) {
    include 'funcs/tenant/utils.php';
    $id=$_POST['id'];
    $isOk = upload($id, null, "./images/tenants/","Profile Picture");
    if($isOk==1){
        header('Location: tenant.php?id='.$id);
    }else{
        echo "error";
    }
}else if (isset($_POST['action']) && "add_document" == $_POST['action']) {
    include 'funcs/tenant/utils.php';
    $id=$_POST['id'];
    $table=$_POST['table'];
    $description=$_POST['description'];
    $isOk = upload($id, $table, "./images/documents/".$table."/".$id."/",$description);
    if($isOk==1){
        header('Location: tenant.php?id='.$id);
    }else{
        echo "error";
    }

}else if (isset($_POST['action']) && "add_tenant" == $_POST['action']) {
    $id=$_POST['id'];
    include 'funcs/tenant/utils.php';
    $tid=addTenant($id);
    header('Location: tenant.php?id='.$tid);
}else if (isset($_POST['action']) && "edit_amount" == $_POST['action']) {
    $id=$_POST['id'];
    include 'funcs/tenant/utils.php';
    $tid=addTenant($id);
    header('Location: tenant.php?id='.$tid);
}else if (isset($_GET['id'])) {
    include 'includes/pageTenant.php';
} ?>