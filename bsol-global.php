<?php
	function ensureUserIsLoggedIn() {
		$user = getUserFromSession();
		if (empty($user)) {
			header('location: login.php');
			exit();
		}
	}

	function getUserFromSession() {
		$user = getFromSession('userProfile', true);
		return $user;
	}

	function setUserForSession($user) {
		storeInSession('userProfile', $user);
	}

	function logUserOff() {
		// remove user from session by retrieving and not persisting the value
		$user = getFromSession('userProfile', false);
		header('location: login.php');
		exit();
	}
?>