<?php
	include_once 'bsol-session.php';
	include_once 'bsol-global.php';
	include_once 'bsol-r-global.php';
	include_once 'bsol-login.php';

	$user = getUserFromSession();
	if (!empty($user))
	{
		header('location: profile.php');
		exit();
	}

	if(isset($_POST['action']) && !empty($_POST['action'])) {
	    $action = $_POST['action'];
	    $username = $_POST['username'];
	    $password = $_POST['password'];
	    switch($action) {
	        case 'logon' : 
	        	if (empty($username) == false && empty($password) == false) {
	        		doLogin($username, $password);
	        	} else {
	        		storeInSession('login_error', 'You must provide a username and password to log in.');
	        		header('Location: login.php');
	        		exit();
	        	}
	        	break;
	    }
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php getMainTitle(); ?> Login</title>
		<?php renderHead(); ?>
	</head>
	<body>
		<?php renderEmptyNavBar(); ?>

		<div class="main-content">
			<div class="container">
				<div>
					<div class="login-form">
						<?php renderErrorMessage('login_error'); ?>

						<form action="login.php" method="post">
							<input type="hidden" name="action" value="logon" />
							<input class="form-control" type="text" name="username" placeholder="Username" />
							<input class="form-control" type="password" name="password" placeholder="Password" />
							<div class="text-center">
								<button type="submit" title="Login" class="btn btn-primary center">Login</button>
							</div>
						</form>

						<a href="https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=244384673234-vfh4f1dnmsl31o124osebabnmio30jqe.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Fbriantsolutions.com%2Foauth%2Fv2%2Fauthentication.php&scope=profile%20https://www.googleapis.com/auth/drive%20https://www.googleapis.com/auth/calendar">
							Login with Google
						</a>
					</div>
				</div>
			</div> <!-- /container -->
		</div>
		
		<?php renderJSLinks(); ?>
	</body>
</html>