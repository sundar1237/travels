<?php
include 'includes/cons.php';
if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
}else if (isset($_GET['action']) && "addRent" == $_GET['action']){
    $latestDate = getSingleValue("select max(bill_month) from bill_months");    
    if($latestDate==null){
        $latestDate = date('Y-m-d',strtotime('2020-05-01'));
        //echo $mayMonthDate."<br>";   
    }else{
        executeSQL("update bill_months set status='Pending' where bill_month='".$latestDate."'");
    }
    $date = date('Y-m-d', strtotime('+1 month', strtotime($latestDate)));
    //echo $date;
    $name = date('M-Y', strtotime($date));
    //$name=strtotime('M-Y',$date);
    $monthNo = date('m', strtotime($date));
    
    //collect all rents
    $expected=getSingleValue("select sum(rent)ex from apartments a, tenants t where t.apartment_id = a.id and a.status='Occupied'");
    $actual=0;
    //insert bill_month
    executeSQL("INSERT INTO bill_months(name, bill_month, bill_month_no, expected, actual, status, completed_in) 
        VALUES ('".$name."','".$date."',".$monthNo.", ".$expected." ,".$actual.",'Open',NULL)");
    
    $billMonthId=getSingleValue("select max(id) from bill_months");
    $name=getSingleValue("select name from bill_months where id=".$billMonthId);
    //collect all tenants and their rent
    $items = getFetchArray("select a.advance, a.rent, t.id, t.first_name,t.pending_amount from apartments a, tenants t where t.apartment_id = a.id and a.status='Occupied'");
    
        foreach ($items as $row){
            executeSQL("INSERT INTO payments( tenant_id, amount, payment_mode, payment_details, paid_date, comments, action, reason, balance, paid_after, fully_paid, parent_id)
            VALUES (".$row['id'].",".$row['rent'].",NULL,NULL,CURRENT_TIMESTAMP(),'Rent is Added for ".$name."','Added','Rent',".$row['rent'].",NULL,'NO',".$billMonthId.")");
            $lag = number_format((($row['advance']-$row['pending_amount'])/$row['rent']),2);
            executeSQL("UPDATE tenants SET pending_amount=".($row['rent'] + $row['pending_amount']).", lag_percent=".$lag." WHERE id = ".$row['id']);
        }
    
    
    header('Location: rents.php');
}else if (isset($_GET['action']) && "viewPending" == $_GET['action']){
    $content="";
    $bill_month=$_GET['id'];
    $bill_month_name= getSingleValue("select name from bill_months where id=".$bill_month);
    $sql="select p.tenant_id, t.first_name,t.last_name,p.amount from payments p,tenants t where t.id=p.tenant_id and p.tenant_id
not in ( SELECT p.tenant_id FROM `payments` p WHERE p.parent_id = ".$bill_month." and p.fully_paid in ('Yes','Partial') )  and parent_id= ".$bill_month."
UNION
select p.tenant_id, t.first_name,t.last_name, p.balance amount from payments p,tenants t where t.id=p.tenant_id and p.parent_id=".$bill_month." and p.fully_paid='Partial'
and p.tenant_id not in (SELECT p.tenant_id FROM `payments` p WHERE p.parent_id = ".$bill_month." and p.fully_paid in ('Yes'))";
    $tenants = getFetchArray($sql);
    $content =$content. '
<p class="h1" style="float:right;">'.$bill_month_name.'</p>
<table class="table table-hover">
				    <thead>
					   <tr>
					       <th scope="col">Name</th>
							<th scope="col">Balance</th>														
					   </tr>
				    </thead>
					<tbody>';
    $total=0;
    foreach ($tenants as $row){
        $total=$total+$row['amount'];
        $content=$content."<tr>
                    <td><a href='tenant.php?id=".$row['tenant_id']."'>".$row['first_name']." ".$row['last_name']."</a></td>
                    <td>".$row['amount']."</td>
                   </tr>";
    }
    $content=$content.'</tbody>
    </table>';
    
  $content=$content.'<button type="button" class="btn btn-primary btn-sm">
  Total <span class="badge badge-light">'.$total.'</span>
  <span class="sr-only">unread messages</span>
</button>';
    
    
    echo $content;
}
else{
    include 'includes/pageRents.php';
 } ?>