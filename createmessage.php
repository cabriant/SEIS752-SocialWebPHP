<?php
	include_once 'bsol-session.php';
	include_once 'bsol-global.php';
	include_once 'bsol-r-global.php';
	include_once 'bsol-db.php';
	require_once "twilio-php-master/Services/Twilio.php";

	ensureUserIsLoggedIn();

	$user = getUserFromSession();

	if(isset($_POST['action']) && !empty($_POST['action'])) {
	    $action = $_POST['action'];
	    $phoneNum = (isset($_POST['phone_number']) ? $_POST['phone_number'] : '');
	    $message = (isset($_POST['phone_message']) ? $_POST['phone_message'] : '');
	    switch($action) {
	        case 'send_message' :
	        	if (!empty($phoneNum) && !empty($message)) {

					// set your AccountSid and AuthToken from www.twilio.com/user/account
					$AccountSid = "AC77dcbb4783bca7a45856955f8f203167";
					$AuthToken = "049c61c3a1b508d4b4ce2ee35f52fb84";
					$client = new Services_Twilio($AccountSid, $AuthToken);
					$sms = $client->account->sms_messages->create(
						"+16122605412", // From this number
						$phoneNum, // To this number
						$message
					);

	        		storeInSession('send_success', 'Your message has been sent to ' . $phoneNum . '.');
	        	}
	        	else {
	        		storeInSession('send_error', 'You must provide a phone number and message to send.');
	        	}
	        	break;
	    }
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php getMainTitle(); ?> Create Message</title>
	<?php renderHead(); ?>
</head>
<body>
	<?php renderMyFriendsNavBar(); ?>

	<div class="main-content">
		<div class="container">
			<h3>
				Create a Message
			</h3>
			<div>
				<form action="createmessage.php" method="POST" class="form-horizontal col-md-12">
					<input type="hidden" name="action" value="send_message">
					<div class="form-group">  
						<label for="phoneNumField" class="control-label">Phone Number:</label>
						<input id="phoneNumField" class="form-control" type="tel" name="phone_number" placeholder="Phone number..." style="width: 320px;">
					</div>
					<div class="form-group">
						<label for="phoneMessageField" class="control-label">Phone Number:</label>
						<textarea id="phoneMessageField" class="form-control" name="phone_message" placeholder="Message to send..."></textarea>
					</div>
					<div class="form-group">
						<button type="submit" title="Send Message" class="btn btn-primary">Send Message</button>
					</div>
				</form>
			</div>
			<?php renderErrorMessage('send_error'); ?>
			<?php renderInfoMessage('send_success'); ?>
		</div>
	</div>
	
	<?php renderJSLinks(); ?>
</body>
</html>