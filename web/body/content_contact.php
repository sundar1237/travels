<?php
?>

<div class="container">
			<div class="row">
				<div class="col-6 py-5">
					<ul class="list-unstyled">
						<li><h4>Contact Us</h4></li>
						<li><strong>Navazideen Kajah</strong></li>
						<li>49, Kasiyapillai nagar</li>
						<li>4098, Belp</li>
						<li>089877878</li>
						<li>solutions@sin.com</li>						
					</ul>
					
				</div>
				<div class="col-6 py-5">
					<div id="googleMap"></div>

					<script>
        function myMap() {
          var mapProp = {
            center: new google.maps.LatLng(46.934568, 7.415910),
            zoom: 17,
          };
          var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        }
      </script>

					<script
						src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNtRx4_bzdH9E80UopARaE6UAw3iTmn9E&callback=myMap"></script>
				</div>
			</div>
			<!-- row-->
		</div>