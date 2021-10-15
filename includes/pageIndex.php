<!doctype html>
<html lang="en">
	<?php
	$current=1;
	if(isset($_GET['p'])){
	    $current=$_GET['p'];
	}
	$limit=NO_OF_INV_PER_PAGE;
	if($current>1){
	    $start=($current*$limit);
	}else{
	    $start=0;
	}
	
	$rowCounts = getCount("select * from orders");
	if($rowCounts>$limit){
	    $totalpage = round (($rowCounts / $limit)) + 1;
	}else{
	    $totalpage = round (($rowCounts / $limit)) ;
	}
	$orders = getFetchArray("select * from orders order by id desc limit ".$start.",".$limit);
	echo getHead("Home", "", "")?>
	
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
			<div class="container">
					<p class="h1">Invoices</p>
					<div class="row">
    					<div class="col-8">
    						 <button 
							class="btn btn-primary btn-sm" title="Add new Invoice"
							data-url="index.php?action=new"
							id="new_invoice" type="button"><i class="fa fa-plus"></i> New Invoice</button> 
							<!-- <a href="showFormNewInvoice.php" class="btn btn-primary btn-sm btn-action1" title="Add new Invoice">New Invoice</a> -->
							
						</div>
    					<div class="col-4">
    						
            			</div>
  					</div>
					
					<!-- start pagination -->					
					<div class="row justify-content-end">						
						
    						<nav aria-label="Page navigation example">
                              <ul class="pagination justify-content-end">
                              	<?php if ($current>1){
                              	     echo '<li class="page-item"><a class="page-link" href="#">Previous</a></li>';    
                              	}
                              	for($i = 1; $i <= $totalpage; $i ++) {
                              	    echo '<li class="page-item"><a class="page-link" href="index.php?p='.$i.'">'.$i.'</a></li>';
                              	}
                              	?>
                                <?php if ($totalpage>$current){
                              	     echo '<li class="page-item"><a class="page-link" href="#">Next</a></li>';
                              	}?>
                                
                                <li class="page-item"> <h6><span class="badge badge-secondary"><?php if($totalpage>0){echo $totalpage;}?></span></h6></li>
                              </ul>  
    						</nav>
						
					</div>
					<!-- end pagination -->
					<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
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
								<th scope="col">#</th>
								<th scope="col"></th>	
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($orders as $row) {
							    $cname="";
							    if($row['customer_id']!=null){$cname = getSingleValue("select first_name from customers where id=".$row['customer_id']);}
							    
							    //$inv_trans=getFetchArray("select * from inv_txns where inv_id=".$row['inv_id'])
                            ?>					 
							<tr>
								<td>
									<a href='order.php?id=<?php echo $row['id'];?>'>
										<?php echo $row['id']?>
									</a>
								</td>
								<td>
									<a href='order.php?id=<?php echo $row['id'];?>'>
										<?php echo $row['reference'];?>
									</a>
								</td>
								<td>
									<a href='order.php?id=<?php echo $row['id'];?>'>
										<?php echo $row['bdate'];?>
									</a>
								</td>

								<td>
									<a href='customer.php?id=<?php echo $row['customer_id'];?>'>
										<?php echo $cname;?>
									</a>
								</td>
								
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
									<?php echo $row['total_price'];?>
								</td>
								<td>
									<?php echo $row['total_paid'];?>
								</td>
								<td>
									<?php if($row['total_balance']>0){echo $row['total_balance'];}?>
								</td>
								<td>
									<?php if($row['total_balance']==0){echo '<i class="fa fa-check-circle" style="color:green"></i>';}else {echo '<i style="color:red" class="fa fa-times"></i>';}?>
								</td>							
								<td>
                                    <a href='' title='Export to PDF'><i class="fa fa-file"></i></a>
                                    <?php if($row['total_balance']>0){?>
                                    	<button style="padding:0px;margin: 0px;" class="btn btn-link" title="Pay" data-url="order.php?action=getBalance&id=<?php echo $row['id']?>" id="pay_invoice" type="button"><i class='fa fa-rocket'></i></button>
                                    <?php }?>
                                    <a href="order.php?action=delete&id=<?php echo $row['id']?>" onClick="return confirm('Are you sure to delete?')" title='Delete'><i class='fa fa-times' style='color:red'></i></a>
                                    
                                    
                                    
								</td>
							</tr>
							<?php
							
    }
    
    ?>								
						</tbody>
					</table>
					</div>
				
			</div>
			
			<!-- Modal for pay invoice-->
		<div class="modal fade" id="myModal2" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="order.php" method="POST" name="pay_invoice"
						enctype="multipart/form-data">
						<input type="hidden" name="action" value="pay_invoice">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Pay Invoice <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="pay_invoice"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->
		
		<!-- Modal for new invoice-->
		<div class="modal fade" id="myModal1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="order.php" method="POST" name="new_invoice"
						enctype="multipart/form-data">
						<input type="hidden" name="action" value="add_new_invoice">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								New Invoice <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body">
							<div class="row" style='margin: 10px;'>
							<div class="col-12">								
        						<div class="form-group">
        							<label for="in1">Enter Sabre Output</label> 
        							<textarea rows="10" cols="100" class="form-control" id="in1" name="sabre_output"></textarea>
        						</div>
							</div>
							</div>
							</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="add_new_invoice"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
			
			<!-- /.container -->
			<!-- FOOTER -->
			<?php echo getFooter();?>
		</main>
		<?php echo getJavaScript();?>
		
		
		<script>
$(document).ready(function() {
    $("[id=pay_invoice]").click(function () {
        var url1 = $(this).data("url");
        $.ajax({
            dataType: "html",
            type: "GET",
            url: url1,
            success: function(msg){                
                $(".modal-body").html(msg);
                $("#myModal2").modal("show");
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    $("[id=new_invoice]").click(function () {
    	$("#myModal1").modal("show");
     });
});
</script>
		
	<script>
	$(document).ready(function() {
	    $('.js-example-basic-single').select2();
	});
</script>
	
	
		
		</body>
	</html>