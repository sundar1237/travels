<?php
$id = $_GET['id'];
$customer = getFetchArray("select * from customers where id = " . $id . " order by id")[0];
?>
<!doctype html>
<html lang="en">
<head>
<?php echo getHead("Customer", "", "")?>

</head>
<body>
	<?php echo getNavigationMenu()?>

	<main role="main">
		<div class="container marketing">
			<div class="blog-post" style='margin-top: 50px;'>
				<ul class="nav nav-tabs" id="tabs">
					<li class="nav-item"><a
						class="nav-link small text-uppercase active" data-target="#tenant"
						data-toggle="tab" href="" id='mytabheaders'>Customer</a></li>

				</ul>
				<br>
				<div class="tab-content" id="tabsContent">
					<div class="tab-pane fade active show" id="delete">
						<a href='customers.php?action=delete&id=<?php echo $customer['id']?>' 
							onClick="return confirm('Are you sure to delete?')" 
							style="float: right;" class="btn btn-danger btn-sm" 
							title="Delete" type="button">Delete</a>
							
						<button class="btn btn-warning btn-sm" title="Edit" data-url="customers.php?action=edit&id=<?php echo $customer['id']?>" id="edit_customer" type="button">Edit</button>
						

		<?php

?>
		<div class="col-md-12">

							<table class="table table-sm table-hover" 
								style="margin-top: 10px;">
								<tbody>
									<tr>
										<th scope="col" colspan="2" style='background-color: #f2f2f2'>Customer
											Details</th>
									</tr>

									<tr>
										<th scope="col">Id</th>
										<td><?php echo $customer['id'];?></td>
									</tr>
									<tr>
										<th scope="col">First Name</th>
										<td><?php echo $customer['first_name'];?></td>
									</tr>
									<tr>
										<th scope="col">Last Name</th>
										<td><?php echo $customer['last_name'];?></td>
									</tr>
									<tr>
										<th scope="col">Street, House Number</th>
										<td><?php echo $customer['address'];?></td>
									</tr>
									<tr>
										<th scope="col">Mobile</th>
										<td><?php echo $customer['mobile'];?></td>
									</tr>
									<tr>
										<th scope="col">City</th>
										<td><?php echo $customer['city'];?></td>
									</tr>
									<tr>
										<th scope="col">Zip</th>
										<td><?php echo $customer['zip'];?></td>
									</tr>
								</tbody>
							</table>							
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /.container -->
		
		
		<!-- Modal for change photo-->
		<div class="modal fade" id="myModal2" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="customers.php" method="POST" name="edit_customer"
						enctype="multipart/form-data">
						<input type="hidden" name="action" value="update">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Edit Customer <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="add_new_invoice"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->

		<?php echo getFooter();?>
	</main>
<?php echo getJavaScript();?>

<script>
$(document).ready(function() {
    $("[id=edit_customer]").click(function () {
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


</body>
</html>
