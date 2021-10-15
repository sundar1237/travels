<!doctype html>
<html lang="en">
	<?php
	$current=1;
	if(isset($_GET['p'])){
	    $current=$_GET['p'];
	}
	$limit=NO_OF_AIRLINES_PER_PAGE;
	if($current>1){
	    $start=($current*$limit);
	}else{
	    $start=1;
	}
	
	$rowCounts = getCount("select * from all_airlines");
	if($rowCounts>$limit){
	    $totalpage = round (($rowCounts / $limit)) + 1;
	}else{
	    $totalpage = round (($rowCounts / $limit)) ;
	}
	$airports = getFetchArray("select * from all_airlines order by id limit ".$start.",".$limit);
	echo getHead("Airlines", "", "")?>
	
	<body>
		<?php echo getNavigationMenu()?>
		<main role="main">
			<div class="container">
				
					<p class="h1">Airlines</p>
					<div class="row">
    					<div class="col-8">
						</div>
    					<div class="col-4">
    						<form class="form-inline my-2 my-lg-0 justify-content-end">
              					<input class="form-control" type="search" placeholder="Search" aria-label="Search">
              					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            				</form>
            			</div>
  					</div>
					
					<!-- start pagination -->					
					<div class="row justify-content-end">						
						
    						<nav aria-label="Page navigation example">
                              <ul class="pagination justify-content-end">
                              	<?php if ($current>1){
                              	     echo '<li class="page-item"><a class="page-link" href="index.php?action=view_airports&p='.($current-1).'">Previous</a></li>';    
                              	}
                              	for($i = 1; $i <= $totalpage; $i ++) {
                              	    echo '<li class="page-item"><a class="page-link" href="index.php?action=view_airports&p='.$i.'">'.$i.'</a></li>';
                              	}
                              	?>
                                <?php if ($totalpage>$current){
                                    echo '<li class="page-item"><a class="page-link" href="index.php?action=view_airports&p='.($current+1).'">Next</a></li>';    
                              	}?>
                                
                                <li class="page-item"> <h6><span class="badge badge-secondary"><?php echo $totalpage?></span></h6></li>
                              </ul>  
    						</nav>
						
					</div>
					<!-- end pagination -->
					<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">Id</th>
								<th scope="col">Name</th>
								<th scope="col">Code</th>
								<th scope="col">Code Number</th>
								<th scope="col">ICAO</th>
								<th scope="col">Country</th>
									
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($airports as $row) {
							    
                            ?>					 
							<tr>
								<td>
									<?php echo $row['id']?>
								</td>
								<td>
									<?php echo $row['name']?>
								</td>
								<td>
									<?php echo $row['code']?>
								</td>

								<td>
									<?php echo $row['code_no']?>
								</td>
																<td>
									<?php echo $row['icao']?>
								</td>

								<td>
									<?php echo $row['country']?>
								</td>
							</tr>
							<?php } ?>								
						</tbody>
					</table>
					</div>
				
			</div>
			
			
			
			<!-- /.container -->
			<!-- FOOTER -->
			<?php echo getFooter();?>
		</main>
		<?php echo getJavaScript();?>
		
		</body>
	</html>