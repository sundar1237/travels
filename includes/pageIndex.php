<!doctype html>
<html lang="en">
	<?php
	$tenants = getFetchArray("select t.* from tenants t, apartments a where t.apartment_id=a.id and a.status='Occupied' order by id");
	echo getHead("Home", "", "")?>
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
			<div class="container">
				<div class="blog-post">
					<p class="h1">Tenants</p>
					<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Pending</th>
								<th scope="col">Rent</th>								
								<th scope="col">#</th>	
							</tr>
						</thead>
						<tbody>
							<?php
							 $count=1;
							 $totalPending=0;$totalRent=0;
					           foreach ($tenants as $row) {
					               $apartment=getFetchArray("select * from apartments where id = ".$row['apartment_id']);
					               $houseId=getSingleValue("select house_id from apartments where id = ".$row['apartment_id']);
					               $houseName=getSingleValue("select house_name from houses where id = ".$houseId);
                            ?>					 
							<tr>
								<td>
									<a href='tenant.php?id=<?php echo $row['id'];?>'>
										<?php echo $count.". ".$row['first_name']." ".$row['last_name']." (".$houseName.")";?>
									</a>
								</td>
								<td>
									<?php echo $row['pending_amount'];
									$totalPending+=$row['pending_amount'];
									?>
								</td>

								<td>
									<?php echo $apartment[0]['rent'];
									$totalRent+=$apartment[0]['rent'];
									?>
								</td>
								<td>
									<?php if ($row['pending_amount']<=0){
							             echo '<i class="fa fa-smile-o" aria-hidden="true" style="color:green;"/>';
							         }else {
							             echo '<i class="fa fa-frown-o" aria-hidden="true" style="color:red;"/>';
							         }?>
								</td>
							</tr>
							<?php
							$count+=1;
    }
    echo '<tr style="background:#f2f2f2;color:#000;"><td>Total</td><td>'.$totalPending.'</td><td>'.$totalRent.'</td><td></td></tr>';
    ?>								
						</tbody>
					</table>
					</div>
				</div>
			</div>
			<!-- /.container -->
			<!-- FOOTER -->
			<?php echo getFooter();?>
		</main>
		<?php echo getJavaScript();?>
	</html>