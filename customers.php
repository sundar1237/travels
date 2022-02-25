<?php
include 'includes/cons.php';

if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
}
else if(isset($_GET['action']) && "edit"==$_GET['action'])
{
    return showEditCustomerForm();
}
else if(isset($_POST['action']) && "update"==$_POST['action'])
{
    $id=$_POST["id"];
    updateCustomer();
    header('Location: customers.php?id='.$id);
}
else if(isset($_POST['action']) && $_POST['action']=="add_new_customer")
{   
    $id=insertCustomer();
    header('Location: customers.php?id='.$id);
}else if(isset($_GET['id']))
{
    include 'includes/pageSingleCustomer.php';
}else{	
    include_once 'funcs/Utils.php';
    include_once 'includes/pageCustomer.php';
}



function insertCustomer(){
    $first_name=$_POST["first_name"];
    $last_name=$_POST["last_name"];
    $address=$_POST["street"];
    $city=$_POST["city"];
    $zip=$_POST["zip"];
    $mobile=$_POST["mobile"];
    
    $insertSQL="INSERT INTO `customers`     (`id`, `first_name`, `last_name`, `address`, `city`, `zip`, `mobile`)
                                    VALUES  (NULL, ".cheSNull($first_name).", ".cheSNull($last_name).", ".cheSNull($address).", ".cheSNull($city).", ".cheSNull($zip).", ".cheSNull($mobile).")";
    $id= insertSQL($insertSQL);
    return $id;
}


function showEditCustomerForm()
{
    $id = $_GET["id"];
    $row = getFetchArray("select * from customers where id = " . $id)[0];
    ?>
<div class="col-md-7">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<div class="form-group">
		<label for="i1">First Name</label> <input type="text"
			class="form-control" id="i1" name="first_name" value="<?php echo $row['first_name'];?>">
	</div>
	<div class="form-group">
		<label for="i2">Last Name</label> <input type="text"
			class="form-control" id="i2" name="last_name" value="<?php echo $row['last_name'];?>">
	</div>
	<div class="form-group">
		<label for="i3">Street, House Number</label> <input type="text"
			class="form-control" id="i3" name="street" value="<?php echo $row['address'];?>">
	</div>
	<div class="form-group">
		<label for="i4">Mobile</label> <input type="text" class="form-control"
			id="i4" name="mobile" value="<?php echo $row['mobile'];?>">
	</div>
	<div class="form-group">
		<label for="i3">City</label> <input type="text" class="form-control"
			id="i5" name="city" value="<?php echo $row['city'];?>">
	</div>
	<div class="form-group">
		<label for="i4">Zip</label> <input type="text" class="form-control"
			id="i6" name="zip" value="<?php echo $row['zip'];?>">
	</div>
</div>
<?php
}

function updateCustomer(){
    $first_name=$_POST["first_name"];
    $last_name=$_POST["last_name"];
    $address=$_POST["street"];
    $city=$_POST["city"];
    $zip=$_POST["zip"];
    $mobile=$_POST["mobile"];
    $id=$_POST["id"];
    $updateSQL="UPDATE `customers` SET `first_name`=".cheSNull($first_name).",`last_name`=".cheSNull($last_name).",`address`=".cheSNull($address).",`city`=".cheSNull($city).",`zip`=".cheNull($zip).",`mobile`=".cheSNull($mobile)." WHERE id=".$id;
    executeSQL($updateSQL);
}
