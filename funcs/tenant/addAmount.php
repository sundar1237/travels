<?php

function addAmount()
{
    $tenantId = $_GET['id'];
    $tenant = getFetchArray("select * from tenants where id = " . $tenantId);
    $apartment = getFetchArray("select * from apartments where id = " . $tenant[0]['apartment_id']);
    $house = getFetchArray("select * from houses where id = " . $apartment[0]['house_id']);

    ?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo MAIN_TITLE ?> | Add Amount</title>
<link href="css/bootstrap.min.css" rel="stylesheet">

<style>
.bd-placeholder-img {
	font-size: 1.125rem;
	text-anchor: middle;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

@media ( min-width : 768px) {
	.bd-placeholder-img-lg {
		font-size: 3.5rem;
	}
}
</style>
<!-- Custom styles for this template -->
<link href="css/carousel.css" rel="stylesheet">
<link href="css/blog.css" rel="stylesheet">
</head>
<body>
	<header>
		<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
			<a class="navbar-brand" href="#"><img src="images/logo_saran.png"></img></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse"
				data-target="#navbarCollapse" aria-controls="navbarCollapse"
				aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active"><a class="nav-link" href="/rent">Home <span
							class="sr-only">(current)</span></a></li>
				</ul>
				<!-- <form class="form-inline mt-2 mt-md-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form> -->
			</div>
		</nav>
	</header>

	<main role="main">
		<div class="container marketing">
			<div class="blog-post" style='margin-top: 50px;'>
				<p class="h1">Add Amount</p>
				<form method="POST" action="pay.php">
				<input type="hidden" id="original_rent" name="original_rent" value="<?php echo $apartment[0]['rent'];?>">
				<input type="hidden" name="id" value="<?php echo $tenant[0]['id'];?>">
				<input type="hidden" name="action" value="addamount">
				<table class="table table-sm table-hover">
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
							<th scope="col">First Name</th>
							<td><a href='tenant.php?id=<?php echo $tenant[0]['id'];?>'><?php echo $tenant[0]['first_name']." ".$tenant[0]['last_name'];?></a></td>
						</tr>
						<tr>
							<th scope="col">Reason</th>
							<td><select class="form-control form-control-sm" id="pmode"
								name="payment_mode">
									<option value="rent">Rent</option>
									<option value="water_bill">Water Bill</option>
									<option value="broken_cost">Broken cost</option>
							</select></td>
						</tr>
						<tr>
							<th scope="col">Amount</th>
							<td><input value="<?php echo $apartment[0]['rent'];?>"
								class="form-control form-control-sm" id="ramount" name="ramount"></input>
							</td>
						</tr>
						
						<tr>
							<th scope="col">Added into month</th>
							<td><select class="form-control form-control-sm" name="rent_month">
    <option value="APR-2020">APR-2020</option>
    <option value="MAY-2020">MAY-2020</option>
    <option value="JUN-2020">JUN-2020</option>
    <option value="JUL-2020">JUL-2020</option>
    <option value="AUG-2020">AUG-2020</option>
    <option value="SEP-2020">SEP-2020</option>
    <option value="OCT-2020">OCT-2020</option>
    <option value="NOV-2020">NOV-2020</option>
    <option value="DEC-2020">DEC-2020</option>    
    </select>
							</td>
						</tr>
						
						
						

						<tr>
							<th scope="col">Comments</th>
							<td><textarea class="form-control form-control-sm"
								id="comments"	name="comments"></textarea></td>
						</tr>

						<tr>
							<td colspan="2">
								<a href="tenant.php?id=<?php echo $tenant[0]['id'];?>" role="button" class="btn btn-secondary btn-sm">Cancel</a>
								<button style="float: right;" type="submit" class="btn btn-primary btn-sm">Submit</button>
							</td>
						</tr>

					</tbody>
				</table>
				</form>
			</div>
		</div>
		<!-- /.container -->

		<!-- FOOTER -->
		<footer class="container">
			<p class="float-right">
				<a href="#">Back to top</a>
			</p>
			<p>
				&copy; Saran Solutions CH. &middot; <a href="#">Privacy</a> &middot;
				<a href="#">Terms</a>
			</p>
		</footer>
	</main>


	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/typeahead.bundle.js" type="text/javascript"></script>
	<script src="js/jquery.cookie.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>


	<script>
    $(document).on('change', '#pmode', function(){
    	var pmode=$('#pmode').val();
    	if(pmode!='rent'){
    		$('#ramount').val("0");
    		$('#comments').text("Explain the cost...");	
        }else{
            var origional_rent=$('#original_rent').val();
        	$('#ramount').val(origional_rent);
        	$('#comments').text("");
        }
    });
        </script>

</html>

<?php    
}

?>
