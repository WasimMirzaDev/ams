<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V16</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{asset('login-assets/images/icons/favicon.ico')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('login-assets/vendor/bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('login-assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('login-assets/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('login-assets/css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100" style="background-image: url('{{asset('login-assets/images/bg-01.jpg')}}');">
			<div class="wrap-login100 p-t-30 p-b-50">
				<span class="login100-form-title p-b-41">
					Account Login
				</span>
				<form method="post" class="login100-form validate-form p-b-33 p-t-5" action="{{ route('login') }}">
                    @csrf
					<div class="wrap-input100 validate-input" data-validate = "Enter username">
                        <input class="input100 @error('email') is-invalid @enderror" type="email" name="email"  value="{{ old('email') }}" placeholder="shazman@yahoo.com" autofocus autocomplete="off">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>
					@error('email')
							<span class="invalid-feedback d-block m-b-10 m-t-10 text-center" role="alert">
									<strong>{{ $message }}</strong>
							</span>
					@enderror
					@error('password')
							<span class="invalid-feedback d-block m-b-10 m-t-10 text-center" role="alert">
									<strong>{{ $message }}</strong>
							</span>
					@enderror
					<div class="container-login100-form-btn m-t-10">
						<button class="login100-form-btn">
							Login
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="{{asset('login-assets/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('login-assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('login-assets/js/main.js')}}"></script>
<!--===============================================================================================-->
</body>
</html>



