<?php
?>
<!doctype html>
<html lang="en">
	<?php
$filter = "";
$isMonthlyReport = false;
$year = $_POST['year'];
if (isset($_POST['month'])) {
    $month = $_POST['month'];
    $isMonthlyReport = true;
    $filter = " where DATE_FORMAT(bdate, '%Y-%M') ='" . $year . "-" . $month . "'";
} else {
    $filter = " where DATE_FORMAT(bdate, '%Y') ='" . $year . "'";
}

$sql = "select * from orders " . $filter;
// echo $sql;
$orders = getFetchArray($sql);

echo getHead("Home", "", "")?>
	
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
		<div class="container">
					<?php
    if ($isMonthlyReport) {
        echo "<p class='h1' style='padding-bottom:5px;'>Monthly Report - " . $month . " " . $year . "  </p>";
        echo '<a class="btn btn-primary btn-sm pull-right"
				title="Export" href="reports.php?action=export&month='.$month.'&year='.$year.'">
				<i class="fa fa-plus"></i> Export
			</a>';
    } else {
        echo "<p class='h1' style='padding-bottom:5px;'>Yearly Report - " . $year . "  </p>";
        echo '<a class="btn btn-primary btn-sm pull-right"
				title="Export" href="reports.php?action=export&year='.$year.'">
				<i class="fa fa-plus"></i> Export
			</a>';
    }
    ?>
    
    
    
					<?php if($orders!=null){ ?>
					 
			<div class="table-responsive" style="padding-top: 10px;">
				<table class="table table-hover">
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
					<tbody>
							<?php
        if ($orders != null) {
            

            $count = 1;
            $total = 0;
            $totalPaid = 0;
            $totalBalance = 0;
            
            foreach ($orders as $row) {
                $cname = "";
                if ($row['customer_id'] != null) {
                    $cname = getSingleValue("select first_name from customers where id=" . $row['customer_id']);
                }
                									
                ?>					 
						<tr>
							<td><?php echo $count;?></td>
							<td><a href='order.php?id=<?php echo $row['id'];?>'>
										<?php echo $row['id']?>
									</a></td>
							<td><a href='order.php?id=<?php echo $row['id'];?>'>
										<?php echo $row['reference'];?>
									</a></td>
							<td><a href='order.php?id=<?php echo $row['id'];?>'>
										<?php echo $row['bdate'];?>
									</a></td>

							<td><a target="_blank"
								href='customers.php?id=<?php echo $row['customer_id'];?>'>
										<?php echo $cname;?>
									</a></td>

							<td>
									<?php echo $row['airlines'];?>
								</td>
							<td>
									<?php echo $row['origin']?>
								</td>
							<td>
									<?php echo $row['destination']?>
								</td>
							<td>
									<?php echo $row['total_price'];$total=$total+$row['total_price'];?>
								</td>
							<td>
									<?php echo $row['total_paid']; $totalPaid=$totalPaid+$row['total_paid'];?>
								</td>
							<td>
									<?php if($row['total_balance']>0){echo $row['total_balance'];$totalBalance=$totalBalance+$row['total_balance'];}?>
								</td>

						</tr>
							<?php

                $count ++;
            }
        }
        ?>
						</tbody>
				</table>
				
				
				
			</div>

			<div class="row">
				<table class="table table-sm" style="margin-left: 70%;">
					<thead>
						<tr>
							<td class="table-active">Total</td>
							<td><span class="badge badge-primary"><?php echo $total; ?></span>
								<small>CHF</small></td>
						</tr>
						<tr>
							<td class="table-active">Total Paid</td>
							<td><span class="badge badge-primary"><?php echo $totalPaid; ?></span>
								<small>CHF</small></td>
						</tr>
						<tr>
							<td class="table-active">Total Balance</td>
							<td><span class="badge badge-primary"><?php echo $totalBalance; ?></span>
								<small>CHF</small></td>
						</tr>
						<tr>
							<td class="table-active">Total Transactions</td>
							<td><span class="badge badge-primary"><?php echo ($count-1);?></span>
							</td>
						</tr>
					</thead>
				</table>

			</div>
					<?php }else{
			         echo '<div class="row" style="padding-bottom: 50%;"><small>No records found</small></div>';		    
					}?>
				
			</div>
			

		<!-- /.container -->
		<!-- FOOTER -->
			<?php echo getFooter();?>
		</main>
		<?php echo getJavaScript();?>
		
	</body>
</html>