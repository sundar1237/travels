<?php

function insertOrder()
{
    $bdate = $_POST['booking_date'];
    $reference = $_POST['booking_reference'];
    $ticket_status = $_POST['ticket_status'];
    $total_passengers = $_POST['total_passengers'];
    $startingFrom = $_POST['startingFrom'];
    $finalDestination = $_POST['finalDestination'];
    $tripType = $_POST['tripType'];
    $airwaysName = $_POST['airwaysName'];
    $baggae = $_POST['baggae'];
    $cancel_charge = $_POST['cancel_charge'];
    $sabre_output = $_POST['sabre_output'];
    $existing_customer_id = $_POST['existing_customer'];
    if ($existing_customer_id == null) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $mobile = $_POST['mobile'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $insertSQL = "INSERT INTO `customers` (`id`, `first_name`, `last_name`, `address`, `city`, `zip`, `mobile`)
        VALUES (NULL, " . cheSNull($first_name) . ", " . cheSNull($last_name) . ", " . cheSNull($address) . ", " . cheSNull($city) . ", " . cheNull($zip) . ", " . cheSNull($mobile) . ")";
        //echo $insertSQL;
        //echo "<br>";
        $existing_customer_id = insertSQL($insertSQL);
    }
    $insertSQL = "INSERT INTO `orders`    (`id`, `reference`, `bdate`, `airlines`, `origin`, `destination`, `baggage`, `cancel_charge`, `customer_id`, `ticket_status`, `total_iata_price`, `total_price`, `gain`, `payment_status`, `content`, `trip_type`, `no_of_passengers`) 
                                VALUES  (NULL, " . cheSNull($reference) . ", " . cheSNull($bdate) . ", " . cheSNull($airwaysName) . ", " . cheSNull($startingFrom) . ", " . cheSNull($finalDestination) . ", " . cheSNull($baggae) . ", " . cheSNull($cancel_charge) . ", " . cheNull($existing_customer_id) . ", " . cheSNull($ticket_status) . ", NULL, NULL, NULL, 'Unpaid', " . cheSNull($sabre_output) . ", " . cheSNull($tripType) . ", " . cheSNull($total_passengers) . ")";
    $id = insertSQL($insertSQL);
    // echo $insertSQL;
    // echo "<br>";
    $totalPrice = 0;
    $totalIATA = 0;
    $totalVisa = 0;
    $totalCharge = 0;
    for ($i = 1; $i <= $total_passengers; $i ++) {
        $prefix = $_POST['prefix' . $i];
        $first_name = $_POST['first_name' . $i];
        $last_name = $_POST['last_name' . $i];
        $extra = $_POST['extra' . $i];
        $eticketNumber = $_POST['eticketNo' . $i];

        $price = $_POST['price' . $i];
        $totalPrice += $price;
        $charge = $_POST['ticket_charge' . $i];
        $totalCharge += $charge;
        $visa = $_POST['visa' . $i];
        if ($visa != null) {
            $totalVisa += $visa;
        }
        $IATA_price = $_POST['IATA_price' . $i];
        $totalIATA += $IATA_price;

        $insertSQL = "INSERT INTO `passengers`    (`id`, `prefix`, `first_name`, `last_name`, `extra`, `e_ticket_number`, `price`, `ticket_charge`, `visa_charge`, `IATA_charge`, `parent_id`) 
                                        VALUES  (NULL, " . cheSNull($prefix) . ", " . cheSNull($first_name) . ", " . cheSNull($last_name) . ", " . cheSNull($extra) . ", " . cheSNull($eticketNumber) . ", " . cheSNull($price) . ", " . cheNull($charge) . ", " . cheSNull($visa) . ", " . cheSNull($IATA_price) . ", " . cheNull($id) . ")";
        insertSQL($insertSQL);
    }
    $total = $totalPrice + $totalVisa + $totalCharge;
    $gain = $total - $totalIATA;
    $updateSQL = "update `orders` set total_price=" . $totalPrice . ", total_iata_price=" . $totalIATA . ",gain=" . $gain . " where id=" . $id;
    executeSQL($updateSQL);

    if (isset($_POST["fully_paid"])) {
        $updateSQL = "update `orders` set total_paid=" . $totalPrice . ", total_balance=0 where id=" . $id;
    } else {
        $updateSQL = "update `orders` set total_paid=0, total_balance=" . $totalPrice . " where id=" . $id;
    }
    executeSQL($updateSQL);
    $routes_count = $_POST['routes_count'];
    for ($i = 1; $i <= $routes_count; $i ++) {
        $start_date = $_POST['start_date' . $i];
        $start_date = date('Y-m-d H:i', strtotime($start_date));
        $from = $_POST['from' . $i];
        $to = $_POST['to' . $i];
        $land_date = $_POST['land_date' . $i];
        $land_date = date('Y-m-d H:i', strtotime($land_date));
        $insertSQL = "INSERT INTO `routes`    (`id`, `start_date`, `origin`, `destination`, `land_date`, `parent_id`)
                                VALUES  (NULL, " . cheSNull($start_date) . ", " . cheSNull($from) . ", " . cheSNull($to) . ", " . cheSNull($land_date) . ", " . $id . ")";
        insertSQL($insertSQL);
    }
}

function getBalance($id)
{
    $rows = getFetchArray("select * from orders where id = " . $id);
    foreach ($rows as $row) {
        $totalPrice = $row["total_price"];
        $totalPaid = $row["total_paid"];
        $totalBalance = $row["total_balance"];
        ?>
        <input type="hidden" name="id" value="<?php echo $id?>">
<div class="col-md-5">
	<div class="form-group">
		<label for="in21">Total Price</label>
		<input name="total_price" type="number" class="form-control" id="in21"
			value="<?php echo $totalPrice;?>" readonly>
	</div>
	<div class="form-group">
		<label for="in22">Paid Amount</label>
		<input name="paid_amount" type="number" class="form-control" id="in22"
			value="<?php echo $totalPaid;?>" readonly>
	</div>
	<div class="form-group">
		<label for="in23">Balance</label>
		<input name="balance" type="number" class="form-control" id="in23"
			value="<?php echo $totalBalance;?>" readonly>
	</div>
	<div class="form-group">
		<label for="in24">Amount to Pay</label>
		<input name="amount" type="number" class="form-control" id="in24"
			value="" min="10" max="<?php echo $totalBalance?>">
	</div>
</div>

<?php
    }
}

function pay_invoice(){
    $id=$_POST['id'];
    $amount=$_POST['amount'];
    $balance=$_POST['balance'];
    $paid_amount=$_POST['paid_amount'];
    $new_balance=$balance-$amount;
    $new_paid_amount=$paid_amount+$amount;
    $updateSQL="update `orders` set total_paid=".$new_paid_amount.", total_balance=".$new_balance." where id=".$id;
    //echo $updateSQL."<br>";
    executeSQL($updateSQL);
    
}

function deleteOrder(){
    $id=$_GET['id'];
    $deleteSQL="delete from routes where parent_id=".$id;
    executeSQL($deleteSQL);
    $deleteSQL="delete from passengers where parent_id=".$id;
    executeSQL($deleteSQL);
    $deleteSQL="delete from orders where id=".$id;
    executeSQL($deleteSQL);
}
