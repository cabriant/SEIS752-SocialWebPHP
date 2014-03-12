<?php
	include_once 'bsol-session.php';
	include_once 'bsol-global.php';
	include_once 'bsol-r-global.php';
	include_once 'bsol-db.php';

	ensureUserIsLoggedIn();

	$user = getUserFromSession();

	if(isset($_POST['action']) && !empty($_POST['action'])) {
	    $action = $_POST['action'];
	    switch($action) {
	        case 'query_user' :
	        	if (isset($_POST['user_query']) && !empty($_POST['user_query'])) {
	        		$user_query = $_POST['user_query'];
	        		$query_results = searchUsers($user_query);
	        	} else {
	        		storeInSession('search_error', 'You must provide a name to search.');
	        	}
				break;
	    }
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php getMainTitle(); ?> Profile</title>
	<?php renderHead(); ?>
</head>
<body>
	<?php renderSearchNavBar(); ?>

	<div class="main-content">
		<div class="container">
			<h3>
				User Search
			</h3>
			<div>
				<?php renderErrorMessage('search_error'); ?>
				<form action="search.php" method="post">
					<input type="hidden" name="action" value="query_user" />
					<input class="form-control" type="text" name="user_query" placeholder="User's name..." value="<?php if (isset($user_query) && !empty($user_query)) { echo $user_query; } ?>" />
					<div class="text-center">
						<button type="submit" title="Search" class="btn btn-primary center">Search</button>
					</div>
				</form>
			</div>
			<div>
				<?php 
					if (isset($query_results))
					{
?>
						<h4>Results</h4>
<?php
						if (!empty($query_results))
						{
				?>
							<?php foreach ($query_results as $userRow) { ?>

								<div style="margin-bottom: 10px;">
									<a href="profile.php?id=<?php echo $userRow['id']; ?>"><?php echo $userRow['name']; ?></a>
								</div>
								
							<?php } ?>
				<?php
						}
						else 
						{
				?>
							No users found matching '<?php echo $user_query; ?>'.
				<?php
						}
					}
				?>
			</div>
		</div>
	</div>
	
	<?php renderJSLinks(); ?>
</body>
</html>