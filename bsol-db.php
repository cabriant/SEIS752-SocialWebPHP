<?php
	function setupDbConnection() {
		// Set default timezone
		date_default_timezone_set('UTC');
		
		try {
			// Create (connect to) MySql database
			$dsn = 'mysql:host=localhost;dbname=seiscbriant';
			$username = 'root';
			$password = 'bS0lu68';
			$options = array(
			    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);

			return new PDO($dsn, $username, $password, $options);
		}
		catch(PDOException $e) {
			// Print PDOException message
			echo $e->getMessage();
		}
	}
	
	function getDbConnection() {
		return setupDbConnection();
	}

	function getUser($username, $password) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT * FROM user_info WHERE username = ? and password = ? LIMIT 1');
		$stmt->execute(array($username, $password));
		$result = $stmt->fetchAll();

		$db = null;

		if (!empty($result))
			return $result[0];
		return null;
	}

	function getUserInfo($username) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT * FROM user_info WHERE username = ?');
		$stmt->execute(array($username));
		$result = $stmt->fetchAll();

		$db = null;

		if (!empty($result))
			return $result[0];
		return null;
	}

	function getUserStream($user_id) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT * FROM messages WHERE postedToUsername = ? order by date desc');
		$stmt->execute(array($user_id));
		$result = $stmt->fetchAll();

		$db = null;

		if (!empty($result))
			return $result;
		return null;
	}

	function addMessageToUserStream($fromUserId, $toUserId, $message) {
		$db = getDbConnection();

		$stmt = $db->prepare('INSERT INTO messages (postedToUsername, postedFromUsername, message) 
								VALUES (?, ?, ?)');
		$stmt->execute(array($toUserId, $fromUserId, $message));
		$result = $stmt->fetchAll();

		$db = null;
	}

	function getAllUsers($user_id) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT friend_username FROM relationships WHERE username = ?');
		$stmt->execute(array($user_id));
		$friend_result = $stmt->fetchAll();

		$stmt = $db->prepare('SELECT * FROM user_info WHERE username != ?');
		$stmt->execute(array($user_id));
		$user_result = $stmt->fetchAll();

		$userArray = array();

		foreach ($user_result as $row) {
			$is_friend = false;
			foreach ($friend_result as $friendRow) {
			 	if (strcasecmp($row['username'], $friendRow['friend_username']) == 0) {
			 		$is_friend = true;
			 		break;
			 	}
			}
			$userRow = array('user' => $row, 'is_friend' => $is_friend);

			array_push($userArray, $userRow);
		}

		$db = null;

		return $userArray;
	}

	function getAllFriends($user_id) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT * FROM user_info WHERE username in (SELECT friend_username FROM relationships WHERE username = ?)');
		$stmt->execute(array($user_id));
		$user_result = $stmt->fetchAll();

		$userArray = array();

		foreach ($user_result as $row) {
			$userRow = array('user' => $row, 'is_friend' => true);
			array_push($userArray, $userRow);
		}

		$db = null;

		return $userArray;
	}

	function addUserAsFriend($current_user, $new_friend) {
		$db = getDbConnection();

		$stmt = $db->prepare('INSERT INTO relationships (username, friend_username) VALUES (?, ?)');
		$stmt->execute(array($current_user, $new_friend));
		$result = $stmt->fetchAll();

		$db = null;
	}

	function removeUserAsFriend($current_user, $old_friend) {
		$db = getDbConnection();

		$stmt = $db->prepare('DELETE FROM relationships  WHERE username = ? AND friend_username = ?');
		$stmt->execute(array($current_user, $old_friend));
		$result = $stmt->fetchAll();

		$db = null;
	}
?>