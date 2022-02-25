<!doctype html>
<html lang="en">
	<?php
$current = 1;
if (isset($_GET['p'])) {
    $current = $_GET['p'];
}
$limit = NO_OF_CUSTOMER_PER_PAGE;
if ($current > 1) {
    $start = ($current * $limit);
} else {
    $start = 0;
}

$rowCounts = getCount("SELECT * FROM `customers`");
if ($rowCounts > $limit) {
    $totalpage = round(($rowCounts / $limit)) + 1;
} else {
    $totalpage = round(($rowCounts / $limit));
}

$filter="";
if(isset($_GET['find_by']) && isset($_GET['find_by_value']) ){
    $find_by=$_GET['find_by'];
    $find_by_value=$_GET['find_by_value'];
    $filter=" where ".$find_by." like '%".$find_by_value."%' ";

}


$customers = getFetchArray("SELECT * FROM `customers` ".$filter."order by id desc limit " . $start . "," . $limit);
echo getHead("Customers", "", "")?>
	
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
		<div class="container">

			<p class="h1">Customers</p>
			<div class="row">
				<div class="col-8">
					<button class="btn btn-primary btn-sm" title="Add new Customer"
						data-url="customers.php?action=new" id="new_customer"
						type="button">
						<i class="fa fa-plus"></i> New Customer
					</button>
				</div>
				
			</div>

			<!-- start pagination -->
			<div class="row justify-content-end">

				<nav aria-label="Page navigation example">
					<ul class="pagination justify-content-end">
                              	<?php

if ($current > 1) {
                                echo '<li class="page-item"><a class="page-link" href="index.php?action=view_airports&p=' . ($current - 1) . '">Previous</a></li>';
                            }
                            for ($i = 1; $i <= $totalpage; $i ++) {
                                echo '<li class="page-item"><a class="page-link" href="index.php?action=view_airports&p=' . $i . '">' . $i . '</a></li>';
                            }
                            ?>
                                <?php

if ($totalpage > $current) {
                                    echo '<li class="page-item"><a class="page-link" href="index.php?action=view_airports&p=' . ($current + 1) . '">Next</a></li>';
                                }
                                ?>
                                
                                <li class="page-item">
							<h6>
								<span class="badge badge-secondary"><?php echo $totalpage?></span>
							</h6>
						</li>
					</ul>
				</nav>

			</div>
			<!-- end pagination -->
			<div class="table-responsive">

				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">Id</th>
							<th scope="col">First Name <a href="#" id="fb_first_name"><i class="fa fa-filter"></i></a></th>
							<th scope="col">Last Name <a href="#" id="fb_last_name"><i class="fa fa-filter"></i></a></th>							
							<th scope="col">Street</th>
							<th scope="col">City</th>
							<th scope="col">Zip</th>
							<th scope="col">Mobile <a href="#" id="fb_mobile"><i class="fa fa-filter"></i></a></th>
							<th scope="col">#</th>

						</tr>
					</thead>
					<tbody>
							<?php
    foreach ($customers as $row) {
        ?>					 
							<tr>
							<td><a href="customers.php?id=<?php echo $row['id']?>"><?php echo $row['id']?></a>
							</td>
							<td><a href="customers.php?id=<?php echo $row['id']?>"><?php echo $row['first_name'] ?></a>
							</td>
							<td><a href="customers.php?id=<?php echo $row['id']?>"><?php echo $row['last_name']?></a>
							</td>
							<td>
									<?php echo $row['address']?>
								</td>

							<td>
									<?php echo $row['city']?>
								</td>
							<td>
									<?php echo $row['zip']?>
								</td>
							<td>
									<?php echo $row['mobile']?>
								</td>
							<td>
									<?php

									echo "<a href='' onclick='return confirm(\"Are you sure?\")' title='Delete'><i class='fa fa-times' style='color:red'></i></a>"?>
								</td>
						</tr>
							<?php } ?>								
						</tbody>
				</table>
			</div>

		</div>
		<!-- /.container -->


		<!-- Modal for new invoice-->
		<div class="modal fade" id="myModal1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="customers.php" method="POST" name="new_customer"
						enctype="multipart/form-data">
						<input type="hidden" name="action" value="add_new_customer">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								New Invoice <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body">
							<div class="col-md-7">
								<div class="form-group">
									<label for="i1">First Name</label> <input type="text"
										class="form-control" id="i1" name="first_name">
								</div>
								<div class="form-group">
									<label for="i2">Last Name</label> <input type="text"
										class="form-control" id="i2" name="last_name">
								</div>
								<div class="form-group">
									<label for="i3">Street,House Number</label> <input type="text"
										class="form-control" id="i3" name="street">
								</div>
								<div class="form-group">
									<label for="i4">Mobile</label> <input type="text"
										class="form-control" id="i4" name="mobile">
								</div>
								<div class="form-group">
									<label for="i3">City</label> <input type="text"
										class="form-control" id="i5" name="city">
								</div>
								<div class="form-group">
									<label for="i4">Zip</label> <input type="text"
										class="form-control" id="i6" name="zip">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<a href="customers.php" class="btn btn-secondary"
								data-dismiss="modal">Close</a>
							<button class="btn btn-primary" type="submit" id="add_customer"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- Modal for filter fb_first_name -->
		<div class="modal fade" id="modal_fb_first_name" role="dialog">
			<div class="modal-dialog modal-xs">
				<div class="modal-content">
					<form action="customers.php" method="GET" name="modal_fb_first_name">
						<input type="hidden" name="find_by" value="first_name">
						<div class="modal-footer">
							First Name * <input type="text" id="first_name" name="find_by_value" required>
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="fb_first_name_submit"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->
		
		<!-- Modal for filter invoices -->
		<div class="modal fade" id="modal_fb_mobile" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="customers.php" method="GET" name="modal_fb_mobile">
						<input type="hidden" name="find_by" value="mobile">
						<div class="modal-footer">
							Mobile Number * <input type="text" id="mobile" name="find_by_value" required>
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="fb_mobile_submit"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->
		
		
		<!-- Modal for filter fb_last_name -->
		<div class="modal fade" id="modal_fb_last_name" role="dialog">
			<div class="modal-dialog modal-xs">
				<div class="modal-content">
					<form action="customers.php" method="GET" name="modal_fb_last_name">
						<input type="hidden" name="find_by" value="last_name">
						<div class="modal-footer">
							First Name * <input type="text" id="last_name" name="find_by_value" required>
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="fb_last_name_submit"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->
		
		

		<!-- FOOTER -->
			<?php echo getFooter();?>
		</main>
		<?php echo getJavaScript();?>
		
		<script>
$(document).ready(function() {
    $("[id=new_customer]").click(function () {
    	$("#myModal1").modal("show");
     });
});
</script>

<script>
$(document).ready(function() {
    $("[id=fb_first_name]").click(function () {
    	$("#modal_fb_first_name").modal("show");
    });
});
</script>

<script>
$(document).ready(function() {
    $("[id=fb_last_name]").click(function () {
    	$("#modal_fb_last_name").modal("show");
    });
});
</script>

<script>
$(document).ready(function() {
    $("[id=fb_mobile]").click(function () {
    	$("#modal_fb_mobile").modal("show");
    });
});
</script>


</body>
</html>