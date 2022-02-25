<?php


include 'includes/cons.php';

if (! isset($_SESSION['user'])) {
    include_once 'funcs/login.php';
    displayLoginPage('');
}
else if(isset($_POST['action']) && "monthly_report"==$_POST['action'])
{

    include_once 'funcs/Utils.php';
    include_once 'includes/pageReportResult.php';
}
else if(isset($_POST['action']) && "yearly_report"==$_POST['action'])
{
    
    include_once 'funcs/Utils.php';
    include_once 'includes/pageReportResult.php';
}
else if(isset($_GET['action']) && "export"==$_GET['action'])
{
    
    echo exportExcel();
}
else{
    include_once 'funcs/Utils.php';
    include_once 'includes/pageReport.php';
}


function exportExcel(){
    $filter = "";
    
    $year = $_GET['year'];
    if (isset($_GET['month'])) {
        $month = $_GET['month'];
        $filter = " where DATE_FORMAT(bdate, '%Y-%M') ='" . $year . "-" . $month . "'";
        $fileName="monthly_report-".$month."-".$year;
    } else {
        $filter = " where DATE_FORMAT(bdate, '%Y') ='" . $year . "'";
        $fileName="anual_report"."-".$year;
    }
    
    $sql = "select * from orders " . $filter;
    // echo $sql;
    $orders = getFetchArray($sql);
    if($orders!=null){
        $count = 1;
        $rows="";
        $table='<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">S.No</th>
							<th scope="col">Id</th>
							<th scope="col">Reference</th>
							<th scope="col">Date</th>
							<th scope="col">Customer</th>
							<th scope="col">Airlines</th>
							<th scope="col">Origin</th>
							<th scope="col">Destination</th>
							<th scope="col">Total</th>
							<th scope="col">Paid</th>
							<th scope="col">Balance</th>
            
						</tr>
					</thead>
					<tbody>';
        foreach ($orders as $row) {
            $cname = "";
            if ($row['customer_id'] != null) {
                $cname = getSingleValue("select first_name from customers where id=" . $row['customer_id']);
            }
        $rows=$rows.'
<tr>
<td>'.$count.'</td>
<td>'.$row['id'].'</td>
<td>'.$row['reference'].'</td>
<td>'.$row['bdate'].'</td>
<td>'.$cname.'</td>
<td>'.$row['airlines'].'</td>
<td>'.$row['origin'].'</td>
<td>'.$row['destination'].'</td>
<td>'.$row['total_price'].'</td>
<td>'.$row['total_paid'].'</td>
<td>'.$row['total_balance'].'</td>';
        $count++;
        }
        $table=$table.$rows."</tbody>"."</table>";
    }
    
    $file=$fileName.".xls";
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$file");
    echo $table;
    
}



?>