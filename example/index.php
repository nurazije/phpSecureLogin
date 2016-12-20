<?php
	require_once '../user.php';
	require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <title>Login and Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="css/style.css" rel="stylesheet" />
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="js/main.js" type="text/javascript"></script>
</head>
<body>
	<div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" method="post" role="form" style="display: block;">
									<?php $user->loginForm(); ?>
									<?php $user->activationForm(); ?>
								</form>
								<form id="register-form" method="post" role="form" style="display: none;">
									<?php $user->registerForm(); ?>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>