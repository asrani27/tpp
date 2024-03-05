<!DOCTYPE html>
<html lang="en">
<head>
	<title>MAINTENANCE MODE</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="/errors/503/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/errors/503/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/errors/503/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/errors/503/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/errors/503/vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/errors/503/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/errors/503/css/util.css">
	<link rel="stylesheet" type="text/css" href="/errors/503/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	
	<div class="bg-g1 size1 flex-w flex-col-c-sb p-l-15 p-r-15 p-t-55 p-b-35 respon1">
		<span></span>
		<div class="flex-col-c p-t-50 p-b-50">
			<h3 class="l1-txt1 txt-center p-b-10">
				MAINTENANCE MODE
			</h3>

			<p class="txt-center l1-txt2 p-b-60">
				Website Sedang Dalam Perbaikan
			</p>

			<button class="flex-c-m s1-txt2 size3 how-btn"  data-toggle="modal" data-target="#subscribe">
				KAMI AKAN SEGERA KEMBALI
			</button><br/>

			<button class="flex-c-m s1-txt2 size3 how-btn"  id="demo" data-toggle="modal" data-target="#subscribe">
				
			</button>
		</div>

		<span class="s1-txt3 txt-center">
			@ 2021 Tim Programmer Diskominfotik Kota Banjarmasin
		</span>
		
	</div>
    

	

<!--===============================================================================================-->	
	<script src="/errors/503/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="/errors/503/vendor/bootstrap/js/popper.js"></script>
	<script src="/errors/503/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="/errors/503/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="/errors/503/vendor/countdowntime/moment.min.js"></script>
	<script src="/errors/503/vendor/countdowntime/moment-timezone.min.js"></script>
	<script src="/errors/503/vendor/countdowntime/moment-timezone-with-data.min.js"></script>
	<script src="/errors/503/vendor/countdowntime/countdowntime.js"></script>
	<script>

	</script>
<!--===============================================================================================-->
	<script src="/errors/503/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
        
	</script>
<!--===============================================================================================-->
	<script src="/errors/503/js/main.js"></script>


	<script>
		// Set the date we're counting down to
		var countDownDate = new Date("Mar 6, 2024 14:37:25").getTime();
		
		// Update the count down every 1 second
		var x = setInterval(function() {
		
		  // Get today's date and time
		  var now = new Date().getTime();
			
		  // Find the distance between now and the count down date
		  var distance = countDownDate - now;
			
		  // Time calculations for days, hours, minutes and seconds
		  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
			
		  // Output the result in an element with id="demo"
		  document.getElementById("demo").innerHTML = hours + " Jam "
		  + minutes + " Menit " + seconds + " Detik ";
			
		  // If the count down is over, write some text 
		  if (distance < 0) {
			clearInterval(x);
			document.getElementById("demo").innerHTML = "EXPIRED";
		  }
		}, 1000);
		</script>
</body>
</html>