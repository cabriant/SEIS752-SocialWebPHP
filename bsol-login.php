<?php
	include_once 'bsol-db.php';

	function doLogin($username, $password) {
		// if successful, store user information in session
		$login_user = getUser($username, $password);
		if (!empty($login_user)) {
			setUserForSession($login_user);
			header('Location: profile.php');
			exit();
		}
		else {
			storeInSession('login_error', 'An invalid username or password was provided. Please try again.');
			header('Location: login.php');
			exit();
		}
	}
?>