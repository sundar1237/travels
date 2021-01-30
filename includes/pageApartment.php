<?php
$apartmentId=$_GET['id'];
$apartment = getFetchArray("select * from apartments where id = ".$apartmentId);
$house = getFetchArray("select * from houses where id = ".$apartment[0]['house_id']);
$tenant = getFetchArray("select * from tenants where apartment_id = ".$apartmentId." order by id");
?>

<!doctype html>
<html lang="en">
<?php echo getHead("Apartment", "", "")?>
    <body>
  <?php echo getNavigationMenu()?>
<main role="main">
  <div class="container marketing">
    <div class="blog-post" style='margin-top: 50px;'>
    	<nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href='house.php?id=<?php echo $house[0]['id'];?>'><?php echo $house[0]['house_name'];?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href='apartment.php?id=<?php echo $apartment[0]['id'];?>'><?php echo $apartment[0]['apartment_name'];?></a></li>
          </ol>
        </nav>
        
        <button style="float:right;" 
									class="btn btn-info btn-sm btn-action1" 
									title="Edit Apartment" 
									data-url="apartment.php?action=edit&id=<?php echo $apartmentId?>" 
									id="edit_apartment" type="button">Edit</button>
    	
    <p class="h1">Apartment</p>
    	<table class="table table-hover" >
					<thead>
						<tr>
							<th scope="col">Id</th>
							<th scope="col">Name</th>
							<th scope="col">Rent</th>
							<th scope="col">Extra Cost</th>
							<th scope="col">Advance</th>
							<th scope="col">Status</th>
							<th scope="col">Images</th>							
						</tr>
					</thead>
					<tbody>
					<?php
					foreach ($apartment as $row) {        
        ?>					 
        			<tr>
        					<input id="apartment_id" name="apartment_id" value="<?php echo $row['id'];?>" type="hidden"></input>
							<td><a href='apartment.php?id=<?php echo $row['id'];?>'><?php echo $row['id'];?></a></td>
							<td><a href='apartment.php?id=<?php echo $row['id'];?>'><?php echo $row['apartment_name'];?></a></td>
							<td><?php echo $row['rent'];?></td>
							<td><?php echo $row['extra_cost'];?></td>
							<td><?php echo $row['advance'];?></td>
							<td>
								<div class="btn-group btn-group-toggle" data-toggle="buttons" id="mydialog">													
									<?php
									if($row['status']=="Empty"){
									    echo '<span class="badge bg-secondary" style="color:white;">'.$row['status'].'</span>';		    
									}else{
									    echo getHouseOccupationStatus($row['status']);
									}
									   
									?>
								</div>
							</td>
							<td><?php 
							$names="";
							$flag=false;
							$path = "images/apartments/".$row['id'];
							if(file_exists($path)){
							    $files = scandir($path);
							    if(count($files)>0){
							        foreach ($files as &$value) {
							            if (strlen($value)>2){							                
							                //echo "<img class='img-thumbnail' style='width:200px;height:200px;' src='images/apartments/".$row['id']."/".$value."' />";
							                $names.=$row['id']."/".$value.";";							             
							                $flag=true;
							            }
							        }
							        if($flag){
							            echo '<button 
									class="btn btn-warning btn-sm" 
									title="View Images" 
									data-url="'.$names.'" 
									id="view_images" type="button"><i class="fa fa-eye" aria-hidden="true"></i></button>';
							        }
							    }else{
							        echo "";
							    }
							}else{
							    echo "";
							}
							
							
							?></td>													
					</tr>
		<?php
    }
    ?>									
					</tbody>
				</table>
    	<?php if(!empty($tenant)){?>
    	 <p class="h1">Tenant</p>
    	<table class="table table-hover" >
					<thead>
						<tr>
							<th scope="col">Id</th>
							<th scope="col">First Name</th>
							<th scope="col">Last Name</th>
							<th scope="col">Mobile No 1</th>
							<th scope="col">Mobile No 2</th>
							<th scope="col">Pending Amount</th>
							<th scope="col">Occupied Since</th>
							<th scope="col">Occupation</th>
							<th scope="col">Aadhar No</th>							
						</tr>
					</thead>
					<tbody>
					<?php					
					    foreach ($tenant as $row) {
					        
        ?>					 
        <tr>
							<td><a href='tenant.php?id=<?php echo $row['id'];?>'><?php echo $row['id'];?></a></td>
							<td><a href='tenant.php?id=<?php echo $row['id'];?>'><?php echo $row['first_name'];?></a></td>
							<td><?php echo $row['last_name'];?></td>
							<td><?php echo $row['mobile_no_1'];?></td>
							<td><?php echo $row['mobile_no_2'];?></td>
							<td><?php echo $row['pending_amount'];?></td>
							<td><?php echo $row['occupied_since'];?></td>
							<td><?php echo $row['occupation'];?></td>
							<td><?php echo $row['aadhar_card_no'];?></td>							
						</tr>
		<?php
					    }
    }else{
        echo '<button 
									class="btn btn-success btn-sm btn-action1" 
									title="Add New Tenant" 
									data-url="apartment.php?action=add_tenant&id='.$apartment[0]['id'].'" 
									id="add_tenant" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add New Tenant</button>';
    }
    ?>									
					</tbody>
				</table>
    	
        	
  	  <hr class="featurette-divider">
	</div>
  </div><!-- /.container -->
  
  
  <!-- Modal for edit tenant-->
		<div class="modal fade" id="AddTenantForm" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="tenant.php" method="POST" name="add_tenant">
						<input type="hidden" name="action" value="add_tenant" />
						<input type="hidden" name="id" value="<?php echo $apartment[0]['id']?>" />
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Add Tenant <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body">
						<table class="table table-sm table-hover">
					<tbody>
						<tr>
							<th scope="col">First Name *</th>
							<td><input type="text" name="first_name" placeholder="first name" required /></td>
						</tr>
						<tr>
							<th scope="col">Last Name</th>
							<td><input type="text" name="last_name" placeholder="last name" /></td>
						</tr>
						<tr>
							<th scope="col">Mobile No 1*</th>
							<td><input type="text" name="mobile_no_1" placeholder="mobile number 1" required /></td>
						</tr>
						<tr>
							<th scope="col">Mobile No 2*</th>
							<td><input type="text" name="mobile_no_2" placeholder="mobile number 2" /></td>
						</tr>
						<tr>
							<th scope="col">Occupation</th>
							<td><input type="text" name="occupation" placeholder="job" /></td>
						</tr>
						<tr>
							<th scope="col">Aadhaar No</th>
							<td><input type="text" name="aadhaar_no" placeholder="aadhaar number" /></td>
						</tr>
						
						<tr>
							<th scope="col">Occupied On</th>
							<td><input type="text" name="occupied_since" placeholder="type yyyy-mm-dd" /></td>
						</tr>

						<tr>
							<th scope="col">Comments</th>
							<td><textarea class="form-control form-control-sm"
								id="comments"	name="comments"></textarea></td>
						</tr>
					</tbody>
				</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" id="atc" style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->
  
  <!-- Modal for EditApartmentForm -->
		<div class="modal fade" id="EditApartmentForm" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="apartment.php" method="POST" name="edit_apartment">
						<input type="hidden" name="action" value="edit_apartment" />
						<input type="hidden" name="id" value="<?php echo $apartment[0]['id']?>" />
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Edit Apartment <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body">
						<table class="table table-sm table-hover">
					<tbody>
						<tr>
							<th scope="col">Name *</th>
							<td><input type="text" name="name" placeholder="Apartment Name" value="<?php echo $apartment[0]['apartment_name']?>" required /></td>
						</tr>
						<tr>
							<th scope="col">Rent *</th>
							<td><input type="number" name="rent" placeholder="Rent" value="<?php echo $apartment[0]['rent']?>" required /></td>
						</tr>
						<tr>
							<th scope="col">Advance *</th>
							<td><input type="number" name="advance" placeholder="Advance" value="<?php echo $apartment[0]['advance']?>" required /></td>
						</tr>
						</tbody>
				</table>
						</div>
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
			<div class="modal-dialog modal-lg" style="max-width: 1150px!important;max-height:100%;" >
				<div class="modal-content">
				
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								View Images <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body">
							<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  
  <div class="carousel-inner">
  	<div id="display_images">
  		
  	</div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" style='' data-dismiss="modal">Close</button>							
						</div>
					
				</div>
			</div>
		</div>
		<!-- Modal - show atc response -->


  <!-- FOOTER -->
  <footer class="container">
    <p class="float-right"><a href="#">Back to top</a></p>
    <p>&copy; Saran Solutions CH. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
  </footer>
</main>
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('input[type=radio][name=status]').change(function() {
            if (confirm('chanage house status ?')) {
                var newValue = $(this).attr('id');                
                var id = $("#apartment_id").val();
                $.ajax({
                    dataType: "html",
                    type: 'POST',
                    data:{id: id, status: newValue, action:'updateApartmentStatus'},                    
                    url: 'apartment.php',
                    success: function(msg){
						location.reload();
                    }                    
                });
            }
        });
    });
</script>

        <script>
$(document).ready(function() {
    $("[id=view_images]").click(function () {
        var text="";
        var data = $(this).data("url");
        var res = data.split(";");
        var i;
        for (i = 0; i < res.length; i++) {
            if(i==0){
            	text += "<div class='carousel-item active'><img class='d-block w-100' src='images/apartments/"+res[i]+"' alt='slide'></div>";
                }else{
                    if(res[i].length >2){
                    	text += "<div class='carousel-item'><img class='d-block w-100' src='images/apartments/"+res[i]+"' alt='slide'></div>";
                        }
                    }
        }
        $("#display_images").html(text);
        $("#myModal1").modal("show");
    });
});
</script>

        <script>
$(document).ready(function() {
    $("[id=add_tenant]").click(function () {
    	$("#AddTenantForm").modal("show");
    });
});
</script>

<script>
$(document).ready(function() {
    $("[id=edit_apartment]").click(function () {
    	$("#EditApartmentForm").modal("show");
    });
});
</script>


</html>