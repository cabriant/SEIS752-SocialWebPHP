<?php
	include_once 'bsol-session.php';
	include_once 'bsol-global.php';
	include_once 'bsol-r-global.php';
	include_once 'bsol-db.php';

	ensureUserIsLoggedIn();

	$user = getUserFromSession();

	if(isset($_POST['action']) && !empty($_POST['action'])) {
	    $action = $_POST['action'];
	    $id = (isset($_POST['id']) ? $_POST['id'] : '');
	    switch($action) {
	        case 'add_friend' : 
	        	if (!empty($id)) {
	        		addUserAsFriend($user['username'], $id);
	        	}
	        	break;
	        case 'remove_friend' :
	        	if (!empty($id)) {
	        		removeUserAsFriend($user['username'], $id);
	        	}
	        	break;
	    }
	}

	$all_users = getAllUsers($user['username']);
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php getMainTitle(); ?> All Users</title>
	<?php renderHead(); ?>
</head>
<body>
	<?php renderAllUsersNavBar(); ?>

	<div class="main-content">
		<div class="container">
			<h3>
				All Users
			</h3>
			<div>
				<?php foreach ($all_users as $userRow) {
					$rowUser = $userRow['user'];
					$rowUserIsFriend = $userRow['is_friend'];
				?>

					<div style="margin-bottom: 20px;">
						<a href="profile.php?id=<?php echo $rowUser['username']; ?>"><?php echo $rowUser['displayname']; ?></a>
						<?php if ($rowUserIsFriend) { ?>
							<form action="allprofiles.php" method="POST">
								<input type="hidden" name="action" value="remove_friend">
								<input type="hidden" name="id" value="<?php echo $rowUser['username']; ?>">
								<button type="submit" title="Remove Friend" class="btn btn-danger">Remove Friend</button>
							</form>
						<?php } else { ?>
							<form action="allprofiles.php" method="POST">
								<input type="hidden" name="action" value="add_friend">
								<input type="hidden" name="id" value="<?php echo $rowUser['username']; ?>">
								<button type="submit" title="Add Friend" class="btn btn-primary">Add Friend</button>
							</form>
						<?php } ?>
					</div>
					
				<?php } ?>
			</div>
		</div>
	</div>
	
	<?php renderJSLinks(); ?>
</body>
</html>