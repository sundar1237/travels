<!doctype html>
<html lang="en">
	<?php
$sabreOutput = $_POST['sabre_output'];
$booking_date = $_POST['booking_date'];
$allcustomers = getAllCustomersForSelect("");
echo getHead("Home", "", "")?>
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
		<div class="fluid-container">
			
			<?php $output=parseTicket($sabreOutput,$booking_date);?>
			<form action="index.php" method="POST" name="confirm_new_invoice"
				enctype="multipart/form-data">

				<div class="row" style='margin: 10px;'>
					<div class="col-2">
						<input type="hidden" name="action" value="confirm_new_invoice">
						<div class="form-group">
							<label for="in1">Booking Date</label> <input type="date"
								class="form-control" id="in1" name="booking_date"
								value="<?php echo $output['booking_date_string']?>">
						</div>
						<div class="form-group">
							<label for="in2">Booking Reference</label> <input type="text"
								class="form-control" id="in2" name="booking_reference"
								value="<?php echo $output['booking_reference']?>">
						</div>

						<div class="form-group">
							<label for="in20">Ticket Status</label>
								<?php

        if ($output['isConfirmed']) {
            echo '<input type="text" name="ticket_status" class="form-control" id="in20" value="Confirmed">';
        } else {
            echo '<input type="text" name="ticket_status" class="form-control" id="in20" value="Reserved">';
        }
        ?>
							
						</div>

						<div class="form-group">
							<label for="in3">Number of Passengers</label> <input
								type="number" class="form-control" id="in3"
								name="total_passengers"
								value="<?php echo $output['totalPassengers']?>">
						</div>

						<div class="form-group">
							<label for="in4">Starting From</label> 
							<select class="form-control startingFrom" name="startingFrom">
								<?php echo getAllAirportsForEdit($output['startingFrom'])?>
							</select>
							
						</div>

						<div class="form-group">
							<label for="in5">Final Destination</label>
							<select class="form-control finalDesitnation" name="finalDestination">
								<?php echo getAllAirportsForEdit($output['finalDestination'])?>
							</select>
						</div>

						<div class="form-group">
							<label for="in6">Trip Type</label> <input type="text"
								class="form-control" id="in6" name="tripType"
								value="<?php echo $output['tripType']?>">
						</div>

						<div class="form-group">
							<label for="in7">Airlines Name</label> <input type="text"
								class="form-control" id="in7" name="airwaysName"
								value="<?php echo $output['airwaysName']?>">
						</div>

					</div>
					<div class="col-10">
						<!-- start of route row -->
						<h6>Routes</h6>
						<div class="row">

							<div class="col-md-10">


								<table class="table table-bordered table-sm">
									<thead>
										<tr>
											<th scope="col">Starting Date</th>
											<th scope="col">Origin</th>
											<th scope="col">Destination</th>
											<th scope="col">Landing Date</th>

										</tr>
									</thead>
									<tbody>
    							<?php
        $routes = $output['routes'];
        $i = 1;
        foreach ($routes as $route) {
            ?>
    							    <tr>
											<td style='width: 20%;'><input type="datetime"
												class="form-control form-control-sm"
												name="start_date<?php echo $i?>"
												value="<?php echo $route['startDate'];?>"></td>
											<td style='width: 30%;'>
												<select class="form-control form-control-sm" name="from<?php echo $i?>">
													<?php echo getAllAirportsForEdit($route['from'])?>
												</select>
											</td>
											<td style='width: 30%;'>
												<select class="form-control form-control-sm" name="to<?php echo $i?>">
													<?php echo getAllAirportsForEdit($route['to'])?>
												</select>												
											</td>
											<td style='width: 20%;'><input type="text"
												class="form-control form-control-sm"
												name="land_date<?php echo $i?>"
												value="<?php echo $route['landDate'];?>"></td>
										</tr>
    							    <?php
            $i ++;
        }
        echo '<input type="hidden" name="routes_count" value="'.($i-1).'">';
        ?>					 
    						</tbody>
								</table>

							</div>
						</div>
						<!-- end of route row -->

						<!-- start of passengers row -->
						<h6>Passengers</h6>
						<div class="row">
							<div class="col-md-10">
								<table class="table table-sm">
									<thead>
										<tr>
											<th scope="col">S.No</th>
											<th scope="col">Prefix</th>
											<th scope="col">First Name</th>
											<th scope="col">Last Name</th>
											<th scope="col">#</th>
											<th scope="col">E-Ticket</th>
											<th scope="col">Price*</th>
											<th scope="col">Charge</th>
											<th scope="col">Visa</th>
											<th scope="col">IATA*</th>
										</tr>
									</thead>
									<tbody>
    							<?php
        $passengers = $output['passengers'];
        $i = 1;
        $firstName=null;$lastName=null;
        foreach ($passengers as $passenger) {
            if ($i==1){
                $firstName=$passenger["first_name"];
                $lastName=$passenger["last_name"];
                $allcustomers = getCustomersForSelect($firstName, $lastName);
            }
            ?>
            <tr>
            								<td style='width: 2%;'><?php echo $i;?></td>
											<td style='width: 5%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "prefix".$i;?>"
												value="<?php echo $passenger["prefix"]?>"></td>
											<td style='width: 25%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "first_name".$i;?>"
												value="<?php echo $passenger["first_name"]?>"></td>
											<td style='width: 25%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "last_name".$i;?>"
												value="<?php echo $passenger["last_name"]?>"></td>
											<td style='width: 8%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "extra".$i;?>"
												value="<?php echo $passenger["extra"]?>"></td>
											<td style='width: 15%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "eticketNo".$i;?>"
												value="<?php echo $passenger["eTicketNumber"]?>"></td>
											<td style='width: 5%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "price".$i;?>" value="" required></td>
											<td style='width: 5%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "ticket_charge".$i;?>" value="20"></td>
											<td style='width: 5%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "visa".$i;?>" value=""></td>
											<td style='width: 5%;'><input type="text"
												class="form-control form-control-sm"
												name="<?php echo "IATA_price".$i;?>" value="" required></td>
										</tr>
	<?php $i++;} ?>					 
    			</tbody>
								</table>
							</div>
							
							<div class="col-md-10">
								<div class="form-group text-right">
									<label for="in31">is Fully Paid?</label>
									<input type="checkbox" name="fully_paid" id="in31"> 
								</div>
								
							
							</div>
							
						</div>
						<!-- end of passengers row -->

						<div class="row" style="margin-top: 10px;">
							<div class="col-md-2">
								<div class="form-group">
									<label for="in21">Baggage <span><small>in <mark>Kg</mark></small></span></label>
									<input name="baggae" type="text" class="form-control" id="in21"
										value="30">
								</div>
								<div class="form-group">
									<label for="in22">Cancel Charge <span><small>in <mark>CHF</mark></small></span></label>
									<input name="cancel_charge" type="text" class="form-control"
										id="in22" value="300">
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label for="in21" class="h5">Existing Customer</label> 
									<select
										class="form-control selectCustomers" name="existing_customer">										
										<?php echo $allcustomers;?>
									</select>
								</div>
								<div class="form-group row" style="margin: 5px;">
									<label for="in21" class="h5">New Customer</label>
								</div>
								<div class="form-group row">
									<label for="inputEmail" class="col-sm-2 col-form-label">First
										Name</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputEmail" name="first_name" value="<?php echo $firstName;?>">
									</div>
									<label for="inputPassword" class="col-sm-2 col-form-label">Last
										Name</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputPassword"
											name="last_name" value="<?php echo $lastName;?>">
									</div>
								</div>


								<div class="form-group row">
									<label for="inputPassword" class="col-sm-2 col-form-label">Address</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputPassword"
											name="address">
									</div>
									<label for="inputPassword" class="col-sm-2 col-form-label">Mobile</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputPassword"
											name="mobile">
									</div>
								</div>

								<div class="form-group row">
									<label for="inputEmail" class="col-sm-2 col-form-label">City</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputEmail"
											name="city">
									</div>
									<label for="inputPassword" class="col-sm-2 col-form-label">Zip</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputPassword"
											name="zip">
									</div>
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-md-10 text-right">
								<a onclick="return confirm('Are you sure?')" href="index.php"
									class="btn btn-secondary btn-sm">Reject</a>
								<button type="submit" class="btn btn-primary btn-sm">Accept</button>
							</div>
						</div>
						<h6>Sabre Output</h6>
						<div class="row">
							<div class="col-md-8">
								<textarea name="sabre_output" rows="25" cols="100" readonly><?php echo $output["sabre_output"]?></textarea>
							</div>
						</div>
						<!-- end of passengers row -->
					</div>

				</div>

			</form>







		</div>
		<!-- /.container -->
		<!-- FOOTER -->
			<?php echo getFooter();?>
		</main>
		<?php echo getJavaScript();?>
		
		<script>
			$(document).ready(function() {
	    		$('.selectCustomers').select2();
			});
		</script>
		
		<script>
			$(document).ready(function() {
	    		$('.startingFrom').select2();
			});
		</script>
		<script>
			$(document).ready(function() {
	    		$('.finalDesitnation').select2();
			});
		</script>
		

</body>
</html>