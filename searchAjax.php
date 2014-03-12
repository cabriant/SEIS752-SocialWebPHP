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
	        	echo "<h4>Results</h4>";

	        	if (isset($_POST['user_query']) && !empty($_POST['user_query'])) {
	        		$user_query = $_POST['user_query'];
	        		$query_results = searchUsers($user_query);

	        		if (!empty($query_results)) {
						foreach ($query_results as $userRow) { 
							echo	"<div style='margin-bottom: 10px;'>";
							echo	"<a href='profile.php?id=" . $userRow['id'] . "'>" . $userRow['name'] . "</a>";
							echo	"</div>";
						}
					}
					else {
						echo	"No users found matching '" . $user_query . "'.";
					}
				}
	        	exit();
				break;
	    }
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php getMainTitle(); ?> Search AJAX</title>
	<?php renderHead(); ?>
</head>
<body>
	<?php renderSearchAjaxNavBar(); ?>

	<div class="main-content">
		<div class="container">
			<h3>
				User Search
			</h3>
			<div>
				<?php renderErrorMessage('search_error'); ?>
				
				<input id="user_query" class="form-control" type="text" name="user_query" placeholder="User's name..." value="<?php if (isset($user_query) && !empty($user_query)) { echo $user_query; } ?>" onkeydown="if (event.keyCode == 13) document.getElementById('search_button').click()" />
				<div class="text-center">
					<input id="search_button" type="button" title="Search" class="btn btn-primary center" onclick="searchForUser(); return false;" value="Search" />
				</div>
			</div>
			<div id="search_results_container">
			</div>
		</div>
	</div>
	
	<?php renderJSLinks(); ?>
	<script type="text/javascript" src="js/searchAjax.js"></script>
</body>
</html>