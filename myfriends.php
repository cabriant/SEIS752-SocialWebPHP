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
	        case 'remove_friend' :
	        	if (!empty($id)) {
	        		removeUserAsFriend($user['id'], $id);
	        	}
	        	break;
	    }
	}

	$all_users = getAllFriends($user['id']);
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php getMainTitle(); ?> My Friends</title>
	<?php renderHead(); ?>
</head>
<body>
	<?php renderMyFriendsNavBar(); ?>

	<div class="main-content">
		<div class="container">
			<h3>
				My Friends
			</h3>
			<div>
				<?php foreach ($all_users as $userRow) {
					$rowUser = $userRow['user'];
				?>

					<div style="margin-bottom: 20px;">
						<a href="profile.php?id=<?php echo $rowUser['id']; ?>"><?php echo $rowUser['name']; ?></a>
						<form action="myfriends.php" method="POST">
							<input type="hidden" name="action" value="remove_friend">
							<input type="hidden" name="id" value="<?php echo $rowUser['id']; ?>">
							<button type="submit" title="Remove Friend" class="btn btn-danger">Remove Friend</button>
						</form>
					</div>
					
				<?php } ?>
			</div>
		</div>
	</div>
	
	<?php renderJSLinks(); ?>
</body>
</html>