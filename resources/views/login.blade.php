<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login TPP</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="/login_tpp/image/png" href="images/icons/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/login_tpp/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/login_tpp/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/login_tpp/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/login_tpp/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/login_tpp/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/login_tpp/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/login_tpp/css/util.css">
	<link rel="stylesheet" type="text/css" href="/login_tpp/css/main.css">
	<!--===============================================================================================-->
	@toastr_css
	{!! NoCaptcha::renderJs() !!}
</head>

<body>

	<div class="limiter">
		<div class="container-login100" style="background-image: url('/login_tpp/images/img-01.jpg');">
			<div class="wrap-login100  p-t-100 p-b-30">
				<form method="post" class="login100-form validate-form" autocomplete="off" action="/login">
					@csrf
					<div class="login100-form-avatar">
						<img src="/login_tpp/images/icons/logo.png" alt="AVATAR">
					</div>

					<span class="login100-form-title p-t-20 p-b-5">
						Sistem Informasi
					</span>
					<div class="txt1 text-center w-full p-t-2 p-b-10 text-white">
						Tambahan Penghasilan PNS<br>
						Kota Banjarmasin
					</div>

					<div class="wrap-input100 validate-input m-b-10" data-validate="Username is required">
						<input class="input100" type="text" name="username" placeholder="NIP" autocomplete="off"
							value="{{old('username')}}">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-10" data-validate="Password is required">
						<input class="input100" type="password" name="password" placeholder="Password"
							autocomplete="off" value="{{old('password')}}">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
					</div>
					<div class="container-login100-form-btn p-t-10">
						{!! app('captcha')->display() !!}
					</div>
					<div class="container-login100-form-btn p-t-10">
						<button type="submit" class="login100-form-btn">
							Login
						</button>
					</div>

					<div class="text-center w-full p-t-25 p-b-230">
						<a href="#" class="txt1 text-white">
							Tim Programmer Diskominfotik<br /> Kota Banjarmasin
						</a>
					</div>

					<div class="text-center w-full">
						{{-- <a class="txt1" href="#">
							Create new account
							<i class="fa fa-long-arrow-right"></i>
						</a> --}}
					</div>
				</form>
			</div>
		</div>
	</div>




	<!--===============================================================================================-->
	<script src="/login_tpp/vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="/login_tpp/vendor/bootstrap/js/popper.js"></script>
	<script src="/login_tpp/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="/login_tpp/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="/login_tpp/js/main.js"></script>

	@toastr_js
	@toastr_render
</body>

</html>