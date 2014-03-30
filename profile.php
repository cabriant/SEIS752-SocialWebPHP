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
	    $message = $_POST['message'];
	    switch($action) {
	        case 'post_message' : 
	        	if (!empty($message)) {
	        		if (!empty($id))
	        			addMessageToUserStream($user['id'], $id, $message);
	        		else
	        			addMessageToUserStream($user['id'], $user['id'], $message);
	        	}

	        	//storeInSession('login_error', 'You must provide a username and password to log in.');
	        	if (!empty($id))
	        		header('Location: profile.php?id=' . $id);
	        	else
	        		header('Location: profile.php');
	        	exit();
	        	break;
	    }
	}

	$u_id = null;
	$q_id = (isset($_GET['id']) ? $_GET['id'] : '');
	if (!empty($q_id)) {
		$u_id = $q_id;
	} else {
		$u_id = $user['id'];
	}

	$user_stream = getUserStream($u_id);
	$blast_stream = getBlastStream();
	$profileUser = getUserInfo($u_id);
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php getMainTitle(); ?> Profile</title>
	<?php renderHead(); ?>
</head>
<body>
	<?php renderProfileNavBar(); ?>

	<div class="main-content">
		<div class="container">
			<div>
				<form action="profile.php" method="post">
					<input type="hidden" name="action" value="post_message" />
					<?php if (!empty($q_id)) { ?>
						<input type="hidden" name="id" value='<?php echo $q_id; ?>'>
					<?php } ?>
					<input class="form-control" type="text" name="message" placeholder="Stream message..." />
					<div class="text-center">
						<button type="submit" title="Post" class="btn btn-primary center">Post</button>
					</div>
				</form>
			</div>
			<?php
				if (!empty($blast_stream)) {
			?>
					<h3>
						Blast Stream
					</h3>
					
					<?php
						foreach ($blast_stream as $blast_item) {
							$dumpContent = $blast_item['dump'];
							$dumpContentArray = json_decode($dumpContent, TRUE);
							$phoneNumber = $dumpContentArray['From'];
							$displayNumber = "***-***-" . substr($phoneNumber, strlen($phoneNumber) - 4);
					?>
							<div>
								<div>
									<?php echo $displayNumber; ?>
								</div>
								<div>
									<?php echo $blast_item['ts']; ?>
								</div>
								<div>
									<?php echo $dumpContentArray['Body']; ?>
								</div>
							</div>
							<br>
					<?php
						}		
				}
			?>
			</h3>
			<h3>
				<?php if (isset($profileUser) && strcasecmp($profileUser['id'], $user['id']) != 0) { ?>
					<?php echo $profileUser['name']; ?>'s Stream
				<?php } else { ?>
					My Stream
				<?php } ?>
			</h3>
			<div>
				<?php 
					if (empty($user_stream)) {
						echo "No messages in the stream.";
					} else {
						foreach ($user_stream as $user_stream_item) {
?>
							<div>
								<?php 
									if (strcasecmp($user_stream_item['postedFromUser'], $user_stream_item['postedToUser']) == 0) { 
										// User posted this message to themselves (i.e. status update)
								?>
										<div>
											<a href="profile.php?id=<?php echo $profileUser['id']; ?>"><?php echo $profileUser['name']; ?></a>
										</div>
										<div>
											<?php echo $user_stream_item['date']; ?>
										</div>
										<div>
											<?php echo $user_stream_item['message']; ?>
										</div>
								<?php
									} else {

								?>
										<div>
											<?php 
												$postedFromUser = getUserInfo($user_stream_item['postedFromUser']);
												$postedToUser = getUserInfo($user_stream_item['postedToUser']);
											?>

											<a href="profile.php?id=<?php echo $postedFromUser['id']; ?>"><?php echo $postedFromUser['name']; ?></a>
											--> 
											<a href="profile.php?id=<?php echo $postedToUser['id']; ?>"><?php echo $postedToUser['name']; ?></a>
											
										</div>
										<div>
											<?php echo $user_stream_item['date']; ?>
										</div>
										<div>
											<?php echo $user_stream_item['message']; ?>
										</div>
								<?php
									}
								?>
							</div>
							<br>
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