<?php


function verifyUser(){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $user= getFetchArray("select * from users where username=".cheSNull($username))[0];
    $result = password_verify($password, $user['password']);    
    //$id = getSingleValue("select id from users where username=".cheSNull($username)." and password = ".cheSNull($password));
    if($result==1){
        //print(" username ".$username." and password ".$password." and id is ".$id);
        //$role = getSingleValue("select role from users where id=".$id);
        $_SESSION['uid']=$user['id'];
        $_SESSION['user']=$user['username'];
        $_SESSION['role']=$user['role'];
        header('Location: index.php');
    }else{
        include_once 'funcs/login.php';
        displayLoginPage("Invalid entry");
    }
}


function sendMail(){
    
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $mobile_no=$_POST['mobile_no'];
    $email=$_POST['email'];
    $message=$_POST['message'];
    
    ini_set("SMTP", SMTP_HOST);
    ini_set("smtp_port", SMTP_PORT);
    ini_set("sendmail_from", MAIL_FROM_ADDRESS);
    
    $toAddress=MAIL_TO_ADDRESS; 
    $today = date("d-m-Y H:i:s");
    $subject="[Enquiry]:: ".$first_name." ".$last_name;
    $content="<html><head><style>
table {
  border-collapse: collapse;
}
        
table, th, td {
  border-bottom: 1px solid #ddd;
}
th{
    background-color: #4CAF50;
    color: white;
    border-right: 1px solid white;
    font-weight: bold;
}
th, td {
  padding: 15px;
  text-align: left;
  font-size:14px;
  font-family: 'Lucida Console', Courier, monospace;
}
</style></head><body>
<table>
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Date</th>
		<th>Mobile Number</th>
		<th>Email</th>
        <th>Message</th>
	</tr>
	<tr>
		<td>".$first_name."</td>
		<td>".$last_name."</td>
		<td>".$today."</td>
        <td>".$mobile_no."</td>
		<td>".$email."</td>
		<td>".$message."</td>
	</tr>
</table></body></html>";
    
    $headers = "From: ";
    $headers = "From: " . strip_tags(MAIL_FROM_ADDRESS) . "\r\n";
    $headers .= "Reply-To: ". strip_tags(MAIL_FROM_ADDRESS) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
    mail($toAddress, $subject, $content, $headers);
    //echo "Check your email now....&lt;BR/>";
    
}


function showFormToUpdateHouse($id)
{
    $tenants = getFetchArray("select * from houses where id = " . $id);
    return '<form method="post" action="house.php">
    <input type="hidden" name="action" value="update_house">
    <input type="hidden" name="id" value="' . $tenants[0]['id'] . '">
        
Name <input type="text" value="' . $tenants[0]['house_name'].'" class="form-control" name="house_name">
<br>
Address <input type="text" value="' . $tenants[0]['address'] . '" class="form-control" name="address">
<br>
No Of Apartments <input type="text" value="' . $tenants[0]['no_of_apartments'] . '" class="form-control" name="no_of_apartments">
<br>

Ward Number <input type="text" value="' . $tenants[0]['ward_no'] . '" class="form-control" name="ward_no">
<br>

Google Map Src <textarea class="form-control" name="google_map_src">' . $tenants[0]['google_map_src'] . '</textarea>
<br>

EB Service No <input type="text" value="' . $tenants[0]['eb_service_no'] . '" class="form-control" name="eb_service_no">
<br>
    
</form>';
}

function updateHouse($id){
    
    $house_name=$_POST['house_name'];
    $address=$_POST['address'];
    $no_of_apartments=$_POST['no_of_apartments'];
    
    $ward=$_POST['ward_no'];
    $google_map_src=$_POST['google_map_src'];
    $eb_service_no=$_POST['eb_service_no'];
    
    executeSQL("update houses set 
        house_name=".cheSNull($house_name).", 
        address=".cheSNull($address).", 
        
        no_of_apartments=".cheNull($no_of_apartments)."
,ward_no=".cheSNull($ward)."
,google_map_src=".cheSNull($google_map_src)."
,eb_service_no=".cheSNull($eb_service_no)." where id = ".$id);

}

function getHouseOccupationStatus($houseStatus){
    $response = '';
    $statusValues = array("Empty", "Occupied");
    foreach ($statusValues as $row){
        if ($row == $houseStatus){
            $response .= '<label class="btn btn-info btn-sm active"><input autocomplete="off" checked id="'. $houseStatus . '" name="status" type="radio"> ' . $houseStatus . '</label>';
        }else{
            $response .= '<label class="btn btn-outline-info btn-sm"><input autocomplete="off" id="' . $row . '" name="status" type="radio">' . $row . '</label>';
        }
    }
    return $response;
}

?>