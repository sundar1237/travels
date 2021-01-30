<?php
?>
<?php
include 'includes/cons.php';

if (isset($_GET['action']) && "addpay" == $_GET['action']) {
    #show form
    $tenantId=$_GET['id'];
    $tenants = getFetchArray("select * from tenants where id =".$tenantId." order by id");
    $pending_amount=getSingleValue("select pending_amount from tenants where id = ".$tenantId);
    //is he have any pending?
    //yes - how much and display to payhim
    //no - only current_month
    //first select pending months
    $msg="Current Month Rent";
    $bill_months = getFetchArray("select * from bill_months where status !='Completed' order by id ");
    $options="";
    
    foreach ($bill_months as $row){
        $sql = "select max(id)c1 from payments where parent_id=".$row['id']." and tenant_id = ".$tenantId;
        $lastRowId =getSingleValue($sql);
        if(!empty($lastRowId)){
            $balance = getSingleValue("select balance from payments where id = ".$lastRowId);
            if($balance>0){
                $options.="<option value='".$row['id']."'>".$row['name']." (".$balance.") </option>";
            }
        }
    }
    
    echo '
<form method="post" action="pay.php">
    <input type="hidden" name="action" value="pay_rent">
    <input type="hidden" name="id" value="'.$tenantId.'">
<input type="hidden" name="currentBalance" value="'.$balance.'">
Tenant Name  <input type="text" readonly value="'.$tenants[0]['first_name']." ".$tenants[0]['last_name'].'" class="form-control" name="tenant_name">
<br>
Rent Month
<select class="form-control" name="monthId">
'.$options.'
</select>
<br>
<strong>'.$msg.'</strong> <input type="number" placeholder='.$pending_amount.' required class="form-control" name="amount" max='.$pending_amount.' >
<br>
Payment Mode <select class="form-control form-control-sm" id="pmode" name="payment_mode">
    <option value="gpay">Google Pay</option>
    <option value="online_banking">Online Banking</option>
    </select>
    
<br>
Payment details
<textarea class="form-control" id="pdetails" name="payment_details" rows="" cols=""></textarea>
<br>
Comments
<textarea class="form-control" id="comments" name="comments" rows="" cols=""></textarea>
<br>
</form>';
    
}else if (isset($_POST['action']) && "pay_rent" == $_POST['action']) {
    $tenantId=$_POST['id'];
    $action="'Paid'";
    $amount=$_POST['amount'];
    $monthId=$_POST['monthId'];
    $reason="Rent";
    $payment_mode=$_POST['payment_mode'];
    $payment_details=$_POST['payment_details'];
    $comments=$_POST['comments'];
    $currentBalance=$_POST['currentBalance'];
    $newBalance = $currentBalance - $amount;
    $fullyPaid="'Partial'";
    if($newBalance==0){
        $fullyPaid="'Yes'";
    }
    
    $sql = "INSERT INTO payments(tenant_id, amount, payment_mode, payment_details, paid_date, comments, action, reason, balance, paid_after, fully_paid, parent_id)
    VALUES (".$tenantId.",".$amount.",".cheSNull($payment_mode).",".cheSNull($payment_details).",CURRENT_TIMESTAMP(),".cheSNull($comments).",".$action.",
".cheSNull($reason).",".$newBalance.",NULL,".$fullyPaid.",".$monthId.")";
    
    
    /*$sql="INSERT INTO payments(tenant_id, amount,rent_month,month_no, payment_mode, payment_details, paid_date, comments) 
    VALUES (".$tenantId.",".$rent_amount.",".cheSNull($rent_month).",".cheNull($month_no).",".cheSNull($payment_mode).",".cheSNull($payment_details).",CURRENT_TIMESTAMP(),".cheSNull($comments)." )";*/
    executeSQL($sql);
    
    $pending_amount=getSingleValue("select pending_amount from tenants where id=".$tenantId);
    $current_p_amount=$pending_amount-$amount;
    $sql="UPDATE tenants SET pending_amount=".cheNull($current_p_amount)." WHERE id=".$tenantId;
    executeSQL($sql);
    
    $apartment_id = getSingleValue("select apartment_id from tenants where id=".$tenantId);
    $apartment = getFetchArray("select * from apartments where id = ".$apartment_id);
    $pending_amount=getSingleValue("select pending_amount from tenants where id=".$tenantId);
    $lag =number_format( (($apartment[0]['advance'] - $pending_amount)/$apartment[0]['rent']), 2);
    
    $sql="UPDATE tenants SET lag_percent=".$lag." WHERE id=".$tenantId;
    executeSQL($sql);
    
    $actual = getSingleValue("select actual from bill_months where id = ".$monthId);
    $sql="UPDATE bill_months SET actual=".($actual+$amount)." WHERE id=".$monthId;
    executeSQL($sql);
    $actual = getSingleValue("select actual from bill_months where id = ".$monthId);
    $expeted = getSingleValue("select expected from bill_months where id = ".$monthId);
    if($expeted == $actual){
        $sql="UPDATE bill_months SET status='Completed' WHERE id=".$monthId;
        executeSQL($sql);
    }
    
    header('Location: index.php');
}else if (isset($_POST['action']) && "addamount" == $_POST['action']) {
    $tenantId=$_POST['id'];
    $amount=$_POST['ramount'];
    $month=$_POST['rent_month'];
    $payment_mode=$_POST['payment_mode'];
    $comments=$_POST['comments'];
    $month_no=$monthNo[$month];
    $sql="INSERT INTO payments(tenant_id, amount,rent_month,month_no, payment_mode, payment_details, paid_date, comments)
    VALUES (".$tenantId.",".cheNull($amount).",".cheSNull($month).",".cheNull($month_no).",".cheSNull($payment_mode).",NULL,CURRENT_TIMESTAMP(),".cheSNull($comments).")";
    executeSQL($sql);
    $pending_amount=getSingleValue("select pending_amount from tenants where id=".$tenantId);
    $current_p_amount=$pending_amount+$amount;
    $sql="UPDATE tenants SET pending_amount=".cheNull($current_p_amount)." WHERE id=".$tenantId;
    executeSQL($sql);
    header('Location: index.php');
}