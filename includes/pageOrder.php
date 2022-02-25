<?php
$id = $_GET['id'];
$order = getFetchArray("select * from orders where id = " . $id . " order by id")[0];
$customer=getFetchArray("select * from customers where id=".$order["customer_id"])[0];
$routes=getFetchArray("select * from routes where parent_id=".$order["id"]);
$passengers=getFetchArray("select * from passengers where parent_id=".$order["id"]);
?>
<!doctype html>
<html lang="en">
<head>
<?php echo getHead("Invoice", "", "")?>

</head>
<body>
	<?php echo getNavigationMenu()?>

	<main role="main">
		<div class="container marketing">
			<div class="blog-post" style='margin-top: 50px;'>
				<ul class="nav nav-tabs" id="tabs">
					<li class="nav-item"><a
						class="nav-link small text-uppercase active" data-target="#tenant"
						data-toggle="tab" href="" id='mytabheaders'>Invoice</a></li>

				</ul>
				<br>
				<div class="tab-content" id="tabsContent">
					<div class="tab-pane fade active show" id="tenant">
						<p class="h1">Invoice</p>

						<a href='order.php?action=delete&id=<?php echo $order['id']?>' 
							onClick="return confirm('Are you sure to delete?')" 
							style="float: right;" class="btn btn-danger btn-sm" 
							title="Delete" type="button">Delete</a>
							
						<?php if($order['total_balance']>0){?>
						<button style="float: right; margin-right: 2px;"
							class="btn btn-warning btn-sm" title="Pay"
							data-url="order.php?action=getBalance&id=<?php echo $order['id']?>" 
							id="pay_invoice" type="button">Pay</button>
						<?php }?>
							
						<a target="_blank" href='export.php?id=<?php echo $order['id']?>'
							class="btn btn-primary btn-sm">Export</a>
		<?php

?>
		<div class="col-md-12">

							<table class="table table-sm table-hover" 
								style="margin-top: 10px;">
								<tbody>
									<tr>
										<th scope="col" colspan="2" style='background-color: #f2f2f2'>Order
											Details</th>
									</tr>

									<tr>
										<th scope="col">Id</th>
										<td><?php echo $order['id'];?></td>
									</tr>
									<tr>
										<th scope="col">Booked Date</th>
										<td><?php echo $order['bdate'];?></td>
									</tr>
									<tr>
										<th scope="col">Booking Reference</th>
										<td><?php echo $order['reference'];?></td>
									</tr>
									<tr>
										<th scope="col">Number of Passengers</th>
										<td><?php echo $order['no_of_passengers'];?></td>
									</tr>
									<tr>
										<th scope="col">Airways</th>
										<td><?php echo $order['airlines'];?></td>
									</tr>
									<tr>
										<th scope="col">Starting From</th>
										<td><?php echo getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $order['origin'] . "'");?></td>
									</tr>
									<tr>
										<th scope="col">Final Destination</th>
										<td><?php echo getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $order['destination'] . "'");?></td>
									</tr>
									<tr>
										<th scope="col" colspan="2" style='background-color: #f2f2f2'>Payment Details
										</th>
									</tr>
									<tr>
										<th scope="col">Total Price</th>
										<td><?php echo $order["total_price"];?></td>
									</tr>
									<tr>
										<th scope="col">Total Paid</th>
										<td><?php echo $order["total_paid"];?></td>
									</tr>
									<tr>
										<th scope="col">Total Balance</th>
										<td><?php if($order['total_balance']>0){ echo $order["total_balance"];}?></td>
									</tr>
									<tr>
										<th scope="col">Payment Status</th>
										<td><?php if($order['total_balance']==0){echo '<i class="fa fa-check-circle" style="color:green"></i> Paid';}else {echo '<i style="color:red" class="fa fa-times"></i> Unpaid';}?></td>
									</tr>
								</tbody>
							</table>

							<table class="table table-sm table-hover" >
								<tbody>
									<tr>
										<th scope="col" colspan="4" style='background-color: #f2f2f2'>Routes
										</th>
									</tr>
									<tr>
										<th>Start date</th>
										<th>Origin</th>
										<th>Destination</th>
										<th>Land date</th>
									</tr>
									<?php foreach($routes as $route){?>
										<tr>
    										<td><?php echo date('d-m-Y, l H:i', strtotime($route["start_date"]));?></td>
    										<td><?php echo getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $route["origin"] . "'");?></td>
    										<td><?php echo getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $route["destination"] . "'"); ?></td>
    										<td><?php echo date('d-m-Y, l H:i', strtotime($route["land_date"]))?></td>
										</tr>
									<?php }?>
									
								</tbody>
							</table>
							<!-- table-sm table-hover -->
							<table class="table table-sm table-hover" >
								<tbody>
									<tr>
										<th scope="col" colspan="10" style='background-color: #f2f2f2'>Passengers
										</th>
									</tr>
									<tr>
										<th>Prefix</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>#</th>
										<th>E-Ticket</th>
										<th>Price</th>
										<th>Charge</th>
										<th>Visa</th>
										<th>Total</th>
										<th>IATA_Price</th>
									</tr>
									<?php foreach($passengers as $passenger){?>
									<tr>
										<td><?php echo $passenger["prefix"]?></td>
										<td><?php echo $passenger["first_name"]?></td>
										<td><?php echo $passenger["last_name"]?></td>
										<td><?php echo $passenger["extra"]?></td>
										<td><?php echo $passenger["e_ticket_number"]?></td>
										<td><?php echo $passenger["price"]?></td>
										<td><?php echo $passenger["ticket_charge"]?></td>
										<td><?php echo $passenger["visa_charge"]?></td>
										<td><?php echo $passenger["price"]+$passenger["ticket_charge"]+$passenger["visa_charge"]?></td>
										<td><?php echo $passenger["IATA_charge"]?></td>
									</tr>
									<?php }?>
								</tbody>
							</table>
							
							<table class="table table-sm table-hover" >
								<tbody>
									<tr>
										<th scope="col" colspan="2" style='background-color: #f2f2f2'>Customer Details
										<button id="edit_customer" data-url="customers.php?action=edit&id=<?php echo $customer["id"]?>" style="float:right" type="button" class="btn btn-secondary btn-sm">Edit</button>
										</th>
									</tr>									
									<tr>
										<th scope="col">First Name</th>
										<td><a target="_blank" href="customers.php?id=<?php echo $customer["id"]; ?>"><?php echo $customer["first_name"];?></a></td>
									</tr>
									<tr>
										<th scope="col">Last Name</th>
										<td><?php echo $customer["last_name"];?></td>
									</tr>
									<tr>
										<th scope="col">Address</th>
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
							
							<table class="table table-sm table-hover" >
								<tbody>
									<tr>
										<th scope="col" colspan="2" style='background-color: #f2f2f2'>Other Details</th>
									</tr>									
									<tr>
										<th scope="col">Baggage</th>
										<td><?php echo $order["baggage"];?></td>
									</tr>
									<tr>
										<th scope="col">Cancel Charge</th>
										<td><?php echo $order["cancel_charge"];?></td>
									</tr>
									<tr>
										<th scope="col">Trip Type</th>
										<td><?php echo $order['trip_type'];?></td>
									</tr>
									<tr>
										<th scope="col" colspan="2" style='background-color: #f2f2f2'>Sabre Output</th>
									</tr>
									<tr>
										<td colspan="2"><textarea rows="25" cols="100"><?php echo $order['content'];?></textarea></td>
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
							<button class="btn btn-primary" type="submit" id="add_new_invoice"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->
		
		
		<!-- Modal for edit customer details-->
		<div class="modal fade" id="myModa21" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="customers.php" method="POST" name="update"
						enctype="multipart/form-data">
						<input type="hidden" name="action" value="update">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Update Customer <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="update_customer"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal for edit customer details -->

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
    $("[id=edit_customer]").click(function () {
        var url = $(this).data("url");
        $.ajax({
            dataType: "html",
            type: "GET",
            url: url,
            success: function(msg){                
                $(".modal-body").html(msg);
                $("#myModa21").modal("show");
            }
        });
    });
});
</script>


</body>
</html>
