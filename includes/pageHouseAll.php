<?php 
    $houses = getFetchArray("select * from houses");
    
?>
<!doctype html>
<html lang="en">
	<?php echo getHead("Houses", "", "")?>
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
			<div class="container marketing">
				<div class="blog-post">
					<div class="table-responsive">
						<!-- main table -->
    					<table class="table table-hover">
    						<thead>
    							<tr>
    								<th>Name</th>    															
    								<th>Total</th>
    								<th>Advance</th>
    							</tr>
    						</thead>
    						<tbody>
    						<?php
    						    $totalRent=0;
    						    $totalAdvance=0;
                                foreach ($houses as $row) {
                                    $total_rent = getSingleValue("select sum(rent) from apartments where house_id = ".$row['id']);
                                    $total_advance = getSingleValue("select sum(advance) from apartments where house_id = ".$row['id']);
                                    $totalRent+=$total_rent;
                                    $totalAdvance+=$total_advance;
                            ?>					 
    						<tr>
    							<td><a href='house.php?id=<?php echo $row['id'];?>'><?php echo $row['house_name'];?></a></td>    														
    							<td><?php echo $total_rent;?></td>
    							<td><?php echo $total_advance;?></td>
    						</tr>
    						<?php } ?>									
    						</tbody>
    					</table>
						<!-- main table -->
					</div>				
					
					<?php
					   echo '<div class="table-responsive">
                        <div class="col-md-3" style="background:#f2f2f2;margin-top:50px;">
                             <table class="table table-sm">
                                <tbody>
							         <tr>
							 	       <th>Total Rent</th><td>'.$totalRent.'</td></tr>
                                    <tr>
								        <th>Total Advance</th><td>'.$totalAdvance.'</td></tr>
                                </tbody>                            
                            </table>
                        </div>
                      </div>';
					   
					?>
					<br>
				</div>
			</div>
            <!-- /.container -->
			<?php echo getFooter();?>
		</main>
	<?php echo getJavaScript();?>
	</body>
</html>