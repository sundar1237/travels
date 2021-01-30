<?php
include 'funcs/db.php';
$latestDate = getSingleValue("select max(bill_month) from bill_months");
if($latestDate==null){
    $latestDate = date('Y-m-d',strtotime('2020-05-01'));
    //echo $mayMonthDate."<br>";
}
$date = date('Y-m-d', strtotime('+1 month', strtotime($latestDate)));
echo $date."<br>";
$name = date('M-Y', strtotime($date));
//$name=strtotime('M-Y',$date);
echo $name."<br>";
$monthNo = date('m', strtotime($date));
echo $monthNo."<br>";

//collect all rents
$expected=getSingleValue("select sum(rent)ex from apartments a, tenants t where t.apartment_id = a.id and a.status='Occupied'");
$actual=0;

//insert bill_month
//executeSQL();
$sql = "INSERT INTO bill_months(name, bill_month, bill_month_no, expected, actual, status, completed_in)
        VALUES ('".$name."','".$date."',".$monthNo.", ".$expected." ,".$actual.",'Open',NULL)";
echo $sql;