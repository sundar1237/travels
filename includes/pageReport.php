<!doctype html>
<html lang="en">
	<?php



echo getHead("Customers", "", "")?>
	
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
		<div class="container">

			<p class="h1" style="padding-top: 20px;">Reports</p>
			
			<div class="row" style="padding-top: 40px;padding-bottom: 40px;">
				<div class="col-8">
					
					<button class="btn btn-primary btn-sm" title="Monthly Report"
						data-url="reports.php?action=newMR" id="monthly_report"
						type="button">
						<i class="fa fa-plus"></i> Monthly Report
					</button>
					
					
					<button class="btn btn-warning btn-sm" title="Yearly Report"
						data-url="reports.php?action=newYR" id="yearly_report"
						type="button">
						<i class="fa fa-plus"></i> Yearly Report
					</button>
					
				</div>				
			</div>
			
			<div class="row" style="padding-top: 40%;">
			</div>


		</div>
		<!-- /.container -->


		<!-- Modal for new invoice-->
		<div class="modal fade" id="myModal1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="reports.php" method="POST" name="monthly_report_form"
						enctype="multipart/form-data">
						<input type="hidden" name="action" value="monthly_report">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Monthly Report <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body">
							<div class="col-md-7">
								<div class="form-group">
									<label for="i1">Month *</label> 
									<select class="form-control" id="i1" name="month" required>
										<option value=""></option>
										<option value="January">January</option>
										<option value="February">February</option>
										<option value="March">March</option>
										<option value="April">April</option>
										<option value="May">May</option>
										<option value="June">June</option>
										<option value="July">July</option>
										<option value="August">August</option>
										<option value="September">September</option>
										<option value="October">October</option>
										<option value="November">November</option>
										<option value="December">December</option>
									</select>
								</div>
								<div class="form-group">
									<label for="i2">Year *</label> 
									<select class="form-control" id="i1" name="year" required>
										<option value=""></option>
										<option value="2022">2022</option>
										<option value="2023">2023</option>
										<option value="2024">2024</option>
										<option value="2025">2025</option>
										<option value="2026">2026</option>
										<option value="2027">2027</option>
										<option value="2028">2028</option>
										<option value="2029">2029</option>
										<option value="2030">2030</option>
										<option value="2031">2031</option>
										<option value="2032">2032</option>
										<option value="2033">2033</option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<a href="customers.php" class="btn btn-secondary"
								data-dismiss="modal">Close</a>
							<button class="btn btn-primary" type="submit" id="monthly_report"
								style=''>Proceed</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		
		<!-- Modal for yearly report -->
		<div class="modal fade" id="yearly_report_modal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="reports.php" method="POST" name="yearly_report_form"
						enctype="multipart/form-data">
						<input type="hidden" name="action" value="yearly_report">
						<div class="modal-header" style='border: none;'>
							<h5 class="modal-title">
								Monthly Report <i class="fa fa-check-square" aria-hidden="true"></i>
							</h5>
							<button aria-hidden="true" class="close" data-dismiss="modal"
								type="button">×</button>
						</div>
						<div class="modal-body">
							<div class="col-md-7">
								
								<div class="form-group">
									<label for="i2">Year *</label> 
									<select class="form-control" id="i1" name="year" required>
										<option value=""></option>
										<option value="2022">2022</option>
										<option value="2023">2023</option>
										<option value="2024">2024</option>
										<option value="2025">2025</option>
										<option value="2026">2026</option>
										<option value="2027">2027</option>
										<option value="2028">2028</option>
										<option value="2029">2029</option>
										<option value="2030">2030</option>
										<option value="2031">2031</option>
										<option value="2032">2032</option>
										<option value="2033">2033</option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<a href="customers.php" class="btn btn-secondary"
								data-dismiss="modal">Close</a>
							<button class="btn btn-primary" type="submit" id="yearly_report"
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
    $("[id=monthly_report]").click(function () {
    	$("#myModal1").modal("show");
     });
});
</script>

<script>
$(document).ready(function() {
    $("[id=yearly_report]").click(function () {
    	$("#yearly_report_modal").modal("show");
    });
});
</script>

</body>
</html>