<?php
$tenantId = $_GET['id'];
$tenant = getFetchArray("select * from tenants where id = " . $tenantId . " order by id");
$apartment = getFetchArray("select * from apartments where id = " . $tenant[0]['apartment_id']);
$house = getFetchArray("select * from houses where id = " . $apartment[0]['house_id']);
$payments = getFetchArray("select p.*,(select name from bill_months where id = p.parent_id)bill_month from payments p where tenant_id = " . $tenantId . " order by id");
$docs = getFetchArray("select * from documents where parent_table='tenants' and parent_id=" . $tenantId);
?>
<!doctype html>
<html lang="en">
<head>
<?php echo getHead("Tenant", "", "")?>

</head>
<body>
	<?php echo getNavigationMenu()?>

	<main role="main">
		<div class="container marketing">
			<div class="blog-post" style='margin-top: 50px;'>
				<ul class="nav nav-tabs" id="tabs">
					<li class="nav-item"><a
						class="nav-link small text-uppercase active" data-target="#tenant"
						data-toggle="tab" href="" id='mytabheaders'>Tenant</a></li>
					<li class="nav-item"><a class="nav-link small text-uppercase"
						data-target="#pay_details" data-toggle="tab" href=""
						id='mytabheaders'>Payment Details</a></li>
					<li class="nav-item"><a class="nav-link small text-uppercase"
						data-target="#documents" data-toggle="tab" href=""
						id='mytabheaders'>Documents</a></li>
				</ul>
				<br>
				<div class="tab-content" id="tabsContent">
					<div class="tab-pane fade active show" id="tenant">
						<p class="h1">Tenant</p>

						<!-- <a
							href="tenant.php?action=addamount&id=<?php /*echo $tenant[0]['id'];*/ ?>"
							style="float: right; margin-bottom: 10px;"
							class="btn btn-warning" title="Add amount manually" role="button">Add
							Amount</a> <a
							href="tenant.php?action=addcompliants&id='<?php /*echo $tenant[0]['id'];*/ ?>"
							style="float: right; margin-right: 10px; margin-bottom: 10px;"
							class="btn btn-danger" title="Add Compliants" role="button">Add
							Complaints</a> <a
							href="tenant.php?action=requestVacate&id='<?php /*echo $tenant[0]['id'];*/ ?>"
							style="float: right; margin-right: 10px; margin-bottom: 10px;"
							class="btn btn-info" title="Add Compliants" role="button">Request
							for vacate</a> -->

						<button style="float: right;"
							class="btn btn-info btn-sm btn-action1" title="Edit Tenant"
							data-url="tenant.php?action=edit&id=<?php echo $tenant[0]['id']?>"
							id="edit_tenant" type="button">Edit</button>

						<button style="float: right; margin-right: 2px;"
							class="btn btn-warning btn-sm btn-action1" title="Change Photo"
							data-id="<?php echo $tenant[0]['id']?>" id="change_photo"
							type="button">Change Photo</button>

						<a target="_blank"
							href='exportTenant.php?id=<?php echo $tenant[0]['id'];?>'
							class="btn btn-primary btn-sm">Export</a>
						
		<?php

if ($tenant[0]['pending_amount'] > 0 && $apartment[0]['status'] == "Occupied") {

    echo '<button class="btn btn-primary btn-action" title="Pay rent" data-url="pay.php?action=addpay&id=' . $tenant[0]['id'] . '" id="pay_rent" type="button">Pay</button>';
}
?>
	
    	<table class="table table-sm table-hover" style="margin-top: 10px;">
							<tbody>
								<tr>
									<th scope="col" colspan="2" style='background-color: #f2f2f2'>Tenant
										Details</th>
								</tr>

								<tr>
									<th scope="col">Id</th>
									<td><a href='tenant.php?id=<?php echo $tenant[0]['id'];?>'><?php echo $tenant[0]['id'];?></a></td>
								</tr>
								<tr>
									<th scope="col">Picture</th>
									<td><a href="#" id="pop"><img
											src="<?php echo $tenant[0]['img_path'];?>"
											id="imageresource" style="width: 100px; height: 100px;"
											class="img-thumbnail" alt="Tenant"></a></td>
								</tr>
								<tr>
									<th scope="col">First Name</th>
									<td><a href='tenant.php?id=<?php echo $tenant[0]['id'];?>'><?php echo $tenant[0]['first_name'];?></a></td>
								</tr>
								<tr>
									<th scope="col">Last Name</th>
									<td><?php echo $tenant[0]['last_name'];?></td>
								</tr>
								<tr>
									<th scope="col">Mobile No 1</th>
									<td><?php echo $tenant[0]['mobile_no_1'];?></td>
								</tr>
								<tr>
									<th scope="col">Mobile No 2</th>
									<td><?php echo $tenant[0]['mobile_no_2'];?></td>
								</tr>
								<tr>
									<th scope="col">Pending Amount</th>
									<td><?php echo $tenant[0]['pending_amount'];?></td>
								</tr>
								<tr>
									<th scope="col">Occupied Since</th>
									<td><?php echo $tenant[0]['occupied_since'];?></td>
								</tr>
								<tr>
									<th scope="col">Occupation</th>
									<td><?php echo $tenant[0]['occupation'];?></td>
								</tr>
								<tr>
									<th scope="col">Aadhar No</th>
									<td><?php echo $tenant[0]['aadhar_card_no'];?></td>
								</tr>
								<tr>
									<th scope="col">Comments</th>
									<td><textarea class="form-control"><?php echo $tenant[0]['comments'];?></textarea></td>
								</tr>
								<tr>
									<th scope="col" colspan="2" style='background-color: #f2f2f2'>Apartment
										Details</th>
								</tr>
								<tr>
									<th scope="col">Apartment</th>
									<td><a
										href="apartment.php?id=<?php echo $apartment[0]['id'];?>"><?php echo $apartment[0]['apartment_name'];?></a></td>
								</tr>
								<tr>
									<th scope="col">Rent</th>
									<td><?php echo $apartment[0]['rent'];?></td>
								</tr>
								<tr>
									<th scope="col">Extra Cost</th>
									<td><?php echo $apartment[0]['extra_cost'];?></td>
								</tr>
								<tr>
									<th scope="col">Advance</th>
									<td><?php echo $apartment[0]['advance'];?></td>
								</tr>
								<tr>
									<th scope="col">Status</th>
									<td><?php echo $apartment[0]['status'];?></td>
								</tr>
								<tr>
									<th scope="col" colspan="2" style='background-color: #f2f2f2'>House
										Details</th>
								</tr>
								<tr>
									<th scope="col">House</th>
									<td><a href="house.php?id=<?php echo $house[0]['id'];?>"><?php echo $house[0]['house_name'];?></a></td>
								</tr>
								<tr>
									<th scope="col">Address</th>
									<td><?php echo $house[0]['address'];?></td>
								</tr>
								<tr>
									<th scope="col">No of Apartments</th>
									<td><?php echo $house[0]['no_of_apartments'];?></td>
								</tr>
							</tbody>
						</table>

					</div>
					<div class="tab-pane fade" id="pay_details">
						<p class="h1">Payments</p>
						<table class="table table-sm table-hover">
							<thead>
								<tr>
									<th scope="col">Amount</th>
									<th scope="col">Month</th>
									<th scope="col">Mode</th>
									<th scope="col">Details</th>
									<th scope="col">Date</th>
									<th scope="col">Comments</th>
									<th scope="col">Balance</th>
									<th scope="col">#</th>

								</tr>
							</thead>
							<tbody>
								
								<?php
        foreach ($payments as $row) {
            ?>
            <?php if("Added"==$row['action']){?>
								    <tr style="background-color: #e6f2ff;">    
								<?php }else{?>
								    
								
								
								<tr style="background-color: #e6ffe6;">
								    <?php }?>
									<td><?php echo $row['amount'];?></td>
									<td><?php echo $row['bill_month'];?></td>
									<td><?php echo $row['payment_mode'];?></td>
									<td>
									<?php if($row['payment_details']!=null){?>
									    <i class="fa fa-info-circle" data-container="body"
										data-toggle="popover" data-placement="top"
										data-content="<?php echo $row['payment_details'];?>"></i>
									<?php

} else {
                echo "";
            }
            ?>
									</td>
									<td><?php echo $row['paid_date'];?></td>
									<td>
									
									<?php if($row['comments']!=null){?>
									    <i class="fa fa-info-circle" data-container="body"
										data-toggle="popover" data-placement="top"
										data-content="<?php echo $row['comments'];?>"></i>
									<?php

} else {
                echo "";
            }
            ?>
										</td>

									<td><?php echo $row['balance'];?></td>
									<td>
										<?php

if ($row['fully_paid'] == "Yes") {
                echo '<i class="fa fa-smile-o" aria-hidden="true" style="color:green;"/>';
            } else if ($row['fully_paid'] == "Partial") {
                echo '<i class="fa fa-frown-o" aria-hidden="true" style="color:red;"/>';
            } else {
                echo '';
            }
            ?>
									</td>

								</tr>
								<?php }?>
							</tbody>
						</table>



					</div>
					<!-- tab payment details completed -->
					<div class="tab-pane fade" id="documents">
						<p class="h1">Documents</p>
						<button style="float: right; margin-right: 2px; margin-bottom:10px;"
							class="btn btn-danger btn-sm btn-action1" title="Add Document"
							data-id="<?php echo $tenant[0]['id']?>" id="add_document"
							type="button">Add Document</button>
							
						<table class="table table-sm table-hover" >
							<thead>
								<tr>
									<th scope="col">Id</th>
									<th scope="col">Description</th>
									<th scope="col">Action</th>
									<th scope="col">Image</th>
								</tr>
							</thead>
							<tbody>
								
	<?php
        if ($docs != null) {
            foreach ($docs as $doc) {
                ?>
                <tr>
									<td scope="col"><?php echo $doc['id']?></td>
									<td scope="col"><?php echo $doc['description']?></td>
									<td scope="col"><a onclick="return confirm('Are you sure?')" href="tenant.php?action=delete_doc&id=<?php echo $doc['id'] ?>"><i title="Delete this image" class="fa fa-times" aria-hidden="true"></i></a></td>
									<td scope="col"><img src="<?php echo $doc['img_path'];?>"></td>
								</tr>
								
            <?php 	    
			}
        }?>
			</tbody>
		</table>

					</div>
					<!-- tab documents completed -->
				</div>

			</div>
		</div>
		<!-- /.container -->


		<!-- Creates the bootstrap modal where the image will appear -->
		<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog"
			aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel"></h4>
					</div>
					<div class="modal-body">
						<img src="" id="imagepreview" style="width: 700px; height: 564px;">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="pay.php" method="POST" name="pay_rent">

						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Pay rent <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="atc" style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->


		<!-- Modal for edit tenant-->
		<div class="modal fade" id="myModal1" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="tenant.php" method="POST" name="edit_tenant">

						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Edit Tenant <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="atc" style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->

		<!-- Modal for change photo-->
		<div class="modal fade" id="myModal2" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="tenant.php" method="POST" name="change_photo"
						enctype="multipart/form-data">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Upload Photo <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="change_photo1"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->

		<!-- Modal for change photo-->
		<div class="modal fade" id="myModal3" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="tenant.php" method="POST" name="add_document"
						enctype="multipart/form-data">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Add Document <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="add_document1"
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
$("#pop").on("click", function() {
   $("#imagepreview").attr("src", $("#imageresource").attr("src")); // here asign the image to the modal when the user click the enlarge link
   $("#imagemodal").modal("show"); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
});
</script>


	<script>
$(document).ready(function() {
    $("[id=pay_rent]").click(function () {
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

	<script>
$(document).ready(function() {
    $("[id=edit_tenant]").click(function () {
        var url1 = $(this).data("url");
        $.ajax({
            dataType: "html",
            type: "GET",
            url: url1,
            success: function(msg){                
                $(".modal-body").html(msg);
                $("#myModal1").modal("show");
            }
        });
    });
});
</script>


	<script>
$(document).ready(function() {
    $("[id=change_photo]").click(function () {
    	var id = $(this).data("id");
    	var str = "Select image to upload: <input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" accept=\"image/*\">";
    	str += "<input type=\"hidden\" name=\"id\" value=\""+id+"\">"
    	str += "<input type=\"hidden\" name=\"action\" value=\"change_photo\">" 
    	$(".modal-body").html(str);
        $("#myModal2").modal("show");
    });
});
</script>

	<script>
$(document).ready(function() {
    $("[id=add_document]").click(function () {
    	var id = $(this).data("id");
    	var str = "Select image to upload: <input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" accept=\"image/*\">";
    	str += "<br><br>Document details : <textarea name=\"description\"></textarea>"
    	str += "<input type=\"hidden\" name=\"id\" value=\""+id+"\">"
    	str += "<input type=\"hidden\" name=\"table\" value=\"tenants\">"
    	str += "<input type=\"hidden\" name=\"action\" value=\"add_document\">" 
    	$(".modal-body").html(str);
        $("#myModal3").modal("show");
    });
});
</script>

	<script>
$(function () {
	  $('[data-toggle="popover"]').popover()
	})
</script>
</body>
</html>
