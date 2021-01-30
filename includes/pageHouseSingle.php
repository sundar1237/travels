<?php 
$houseId=$_GET['id'];
$houses = getFetchArray("select * from houses where id = ".$houseId);
$apartments = getFetchArray("select * from apartments where house_id = ".$houseId." order by id");
?>
<!doctype html>
<html lang="en">
	<?php echo getHead("House", "", "")?>
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
			<div class="container marketing">
				<div class="blog-post">
					<ul class="nav nav-tabs" id="tabs">
    					<li class="nav-item"><a
    						class="nav-link small text-uppercase active" data-target="#tab1"
    						data-toggle="tab" href="" id='mytabheaders'>Details</a></li>
    					<li class="nav-item"><a class="nav-link small text-uppercase"
    						data-target="#tab2" data-toggle="tab" href=""
    						id='mytabheaders'>Expenditures</a></li>
					</ul>
					<div class="tab-content" id="tabsContent">
					<div class="tab-pane fade active show" id="tab1">
					<br>
					<button 
									class="btn btn-info btn-sm btn-action1" 
									title="Edit House" 
									data-url="house.php?action=edit&id=<?php echo $_GET['id']?>" 
									id="edit_house" type="button">Edit</button>
					
					<table class="table table-sm">
						<tbody>
							<?php
foreach ($houses as $row) {
    ?> 
							<tr>
								<td>Name</td><td ><a href='house.php?id=<?php echo $row['id'];?>'><?php echo $row['house_name'];?></a></td></tr><tr>
								<td>Address</td><td><?php echo $row['address'];?></td></tr><tr>
								<td>Apartments</td><td ><?php echo $row['no_of_apartments'];?></td></tr>
								<tr><td>Ward Number</td><td ><?php echo $row['ward_no'];?></td></tr>
								<tr><td>EB Service Number</td><td><?php echo $row['eb_service_no'];?></td></tr>
								<tr><td>Google Map</td><td><?php if($row['google_map_src'] != null){
					    echo '<iframe src="'.$row['google_map_src'].'" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';    
								}else{
								    echo "";
								}?></td></tr>
							<?php
}
?>
						</tbody>
					</table>
					
					<p class="h1">Apartments</p>
					<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Rent</th>
								
								<th scope="col">Advance</th>
								<th scope="col">Status</th>
								<th scope="col">Since</th>
							</tr>
						</thead>
						<tbody>
							<?php
foreach ($apartments as $row) {
    ?>					 
							<tr>
								<td><a href='apartment.php?id=<?php echo $row['id'];?>'><?php echo $row['apartment_name'];?></a></td>
								<td><?php echo $row['rent'];?></td>								
								<td><?php echo $row['advance'];?></td>
								<td><?php if("Occupied"==$row['status']){
								    echo '<span class="badge badge-success">'.$row['status'].'</span>';
								} else{
								    echo '<span class="badge badge-danger">'.$row['status'].'</span>';
								}?></td>
								<td><?php if("Empty"==$row['status']){
								    $since=getSingleValue("SELECT datediff(CURRENT_DATE, modified_date) FROM apartments WHERE id=".$row['id']);
								    echo '<span class="badge badge-warning">'.$since.' days</span>';
								} else{
								    echo '';
								}?></td>
							</tr>
							<?php
}
?>		
						</tbody>
					</table>
					</div>
					<hr class="featurette-divider">
					</div>
					<div class="tab-pane fade" id="tab2">
						Expenditures
					</div>
					</div>
					</div>
				</div>
				<!-- /.container -->
				
				<!-- Modal for edit tenant-->
		<div class="modal fade" id="myModal1" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="house.php" method="POST" name="edit_house">

						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Edit House <i class="fa fa-check-square" aria-hidden="true"></i>
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
				
				<?php echo getFooter();?>
			</main>
			<?php echo getJavaScript();?>
			<script>
$(document).ready(function() {
    $("[id=edit_house]").click(function () {
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
			
			</body>
		</html>

		
		