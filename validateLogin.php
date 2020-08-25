<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
$errMsg = - 1;
if (isset($_POST['verifyUser'])) {
    require_once 'funcs/db/db.php';
    $errMsg = verifyUser($_POST['username'], $_POST['password']);
    echo "error mesg".$errMsg;
    print "msg ".$errMsg;
    if (is_numeric($errMsg )) {
        header("Location: /gogm/index.php");
    }else{
        header("Location: /login.php?code=red");
    }
}

function verifyUser($username, $password)
{

    $sql = "SELECT id FROM users WHERE username='" . $username . "' and password='" . $password . "' ";
    $id = getSingleValue($sql);
    print "user id is ".$id;
    if ($id && is_null($id)){
        return "invaldi username & password";
    }else{
        $_SESSION['user']=$username;
        $_SESSION['user_id']=$id;
        return $id;
    }
}
?>
