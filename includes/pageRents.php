<?php
$billMonths = getFetchArray("select * from bill_months order by bill_month_no");
?>
<!doctype html>
<html lang="en">
	<?php echo getHead("Rents", "", "")?>
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
			<div class="container marketing">
				<div class="blog-post">
					<p class="h1">Monthly Rents</p>
					<a
							href="rents.php?action=addRent"
							style="float: right; margin-bottom: 10px;"
							class="btn btn-primary" title="Add rent" role="button">Add
							Rent</a>
					<table class="table table-sm" >
						<thead>
							<tr>
								<th scope="col" style="border: none;">Month</th>								
								<th scope="col" style="border: none;">Expected</th>
								<th scope="col" style="border: none;">Actual</th>
								<th scope="col" style="border: none;">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
					$count=1;
					if(!empty($billMonths)){
					foreach ($billMonths as $row) {
					    $count++;
        ?>					 
							<tr>
								<td style="border: none;">
									<?php echo $row['name'];?>
								</td>
								<td style="border: none;">
									<a class="btn btn-secondary btn-sm" data-toggle="collapse" href="#expected<?php echo $count;?>" role="button" aria-expanded="false" aria-controls="collapseExample">
										<?php echo $row['expected'];?>
									</a>
								</td>
								<td style="border: none;">
									<?php if ($row['actual'] > 0){?>
									    <a class="btn btn-primary btn-sm" data-toggle="collapse" href="#actual<?php echo $count;?>" role="button" aria-expanded="false" aria-controls="collapseExample">
									    	<?php echo $row['actual'];?>
										</a>
									<?php  }else{
									    echo $row['actual'];
									 }?>
								</td>								
								<td style="border: none;"><?php 
								if ("Completed"==$row['status']){
								    echo '<span class="badge badge-success">'.$row['status'].'</span>';
								}else if("Pending"==$row['status']){
								    echo '<button class="btn btn-warning btn-sm btn-action" title="View Pending Tenanats" 
                                            data-url="rents.php?action=viewPending&id=' . $row['id'] . '" 
                                            id="view_pending" type="button">'.$row['status'].'</button>';
								}else{
								    echo '<button class="btn btn-info btn-sm btn-action" title="View Pending Tenanats" 
                                            data-url="rents.php?action=viewPending&id=' . $row['id'] . '" 
                                            id="view_pending" type="button">'.$row['status'].'</button>';
								}?></td>
							</tr>

							<tr>
								<td colspan="4" style='border: none;'><div class="collapse" id="actual<?php echo $count;?>">
										<div class="card card-body">
											<table class="table table-sm" >
												<thead>
													<tr>
														<th>Name</th>
														<th>Amount</th>
														<th>Balance</th>
														<th>#</th>

													</tr>
												</thead>
												<tbody>
													<?php
													$rxAmount=0;
					   $payment_details = getFetchArray("select tenant_id,sum(amount) a1 from payments where parent_id=".$row['id']." and action='Paid' group by tenant_id order by tenant_id");
					   foreach ($payment_details as $item){
					       $tenant = getFetchArray("select * from tenants where id = ".$item['tenant_id']);
					       $balance = getFetchArray("select balance from payments where tenant_id = ".$item['tenant_id']." and action='Paid' and parent_id = ".$row['id']." order by id desc ");
					       $fully_paid='<i style="color:green;" class="fa fa-smile-o" aria-hidden="true"></i>';
					       if($balance[0]['balance']>0){
					           $fully_paid='<i style="color:red;" class="fa fa-frown-o" aria-hidden="true"></i>';
					       }
					       $rxAmount=$rxAmount+$item['a1'];
					       echo '<tr>
							<td><a target="blank" href="tenant.php?id='.$tenant[0]['id'].'">'.$tenant[0]['first_name']." ".$tenant[0]['last_name'].'</a></td>
							<td>'.$item['a1'].'</td>
                            <td>'.$balance[0]['balance'].'</td>
                            <td>'.$fully_paid.'</td>
						</tr>';
					   }
					?>
												</tbody>
											</table>
												
										</div>
									</div></td>
							</tr>

							<tr>
								<td colspan="4" style='border: none;'><div class="collapse" id="expected<?php echo $count;?>">
										<div class="card card-body">
										<?php $exAmount=0;?>
											<div class="table-responsive">
											<table class="table table-sm" >
												<thead>
													<tr>
														<th>Name</th>
														<th>Amount</th>
													</tr>
												</thead>
												<tbody>
													<?php
					   $payment_details = getFetchArray("select * from payments where action='Added' and parent_id =".$row['id']);
					   foreach ($payment_details as $item){
					       $tenant = getFetchArray("select * from tenants where id = ".$item['tenant_id']);
					       $exAmount= ($exAmount + $item['amount']);
					       echo '<tr>
							<td><a target="blank" href="tenant.php?id='.$tenant[0]['id'].'">'.$tenant[0]['first_name']." ".$tenant[0]['last_name'].'</a></td>
							<td>'.$item['amount'].'</td>
                            
						</tr>';
					   }
					   
					   echo "<tr><td><strong>Total</strong></td><td><strong>".$exAmount."</strong></td></tr>";
					?>
												</tbody>
											</table>											
												</div>
							
										</div>
									</div>
									
									</td>
							</tr>
							
							<?php
					}
}
    ?>									
						</tbody>
					</table>
				</div>
			</div><!-- /.container -->
			
			
			<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Pending Tenants <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->
			
			<?php echo getFooter();?>
		</main>
		<?php echo getJavaScript();?>
		
		
		        <script>
$(document).ready(function() {
    $("[id=view_pending]").click(function () {
        var url1 = $(this).data("url");
        $.ajax({
            dataType: "html",
            type: "GET",
            url: url1,
            success: function(msg){                
                $(".modal-body").html(msg);
                $("#myModal").modal("show");
            }
        });
    });
});
</script>
		
		
	</body>
</html>