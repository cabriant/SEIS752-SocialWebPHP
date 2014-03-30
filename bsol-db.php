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

		$stmt = $db->prepare('SELECT * FROM users WHERE username = ? and password = ? LIMIT 1');
		$stmt->execute(array($username, $password));
		$result = $stmt->fetchAll();

		$db = null;

		if (!empty($result))
			return $result[0];
		return null;
	}

	function getUserInfo($user_id) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
		$stmt->execute(array($user_id));
		$result = $stmt->fetchAll();

		$db = null;

		if (!empty($result))
			return $result[0];
		return null;
	}

	function getUserStream($user_id) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT * FROM messages WHERE postedToUser = ? order by date desc');
		$stmt->execute(array($user_id));
		$result = $stmt->fetchAll();

		$db = null;

		if (!empty($result))
			return $result;
		return null;
	}

	function addMessageToUserStream($fromUserId, $toUserId, $message) {
		$db = getDbConnection();

		$stmt = $db->prepare('INSERT INTO messages (postedToUser, postedFromUser, message) 
								VALUES (?, ?, ?)');
		$stmt->execute(array($toUserId, $fromUserId, $message));
		$result = $stmt->fetchAll();

		$db = null;
	}

	function getAllUsers($user_id) {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT friend_user_id FROM relationships WHERE user_id = ?');
		$stmt->execute(array($user_id));
		$friend_result = $stmt->fetchAll();

		$stmt = $db->prepare('SELECT * FROM users WHERE id != ?');
		$stmt->execute(array($user_id));
		$user_result = $stmt->fetchAll();

		$userArray = array();

		foreach ($user_result as $row) {
			$is_friend = false;
			foreach ($friend_result as $friendRow) {
			 	if (strcasecmp($row['id'], $friendRow['friend_user_id']) == 0) {
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

		$stmt = $db->prepare('SELECT * FROM users WHERE id in (SELECT friend_user_id FROM relationships WHERE user_id = ?)');
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

		$stmt = $db->prepare('INSERT INTO relationships (user_id, friend_user_id) VALUES (?, ?)');
		$stmt->execute(array($current_user, $new_friend));
		$result = $stmt->fetchAll();

		$db = null;
	}

	function removeUserAsFriend($current_user, $old_friend) {
		$db = getDbConnection();

		$stmt = $db->prepare('DELETE FROM relationships  WHERE user_id = ? AND friend_user_id = ?');
		$stmt->execute(array($current_user, $old_friend));
		$result = $stmt->fetchAll();

		$db = null;
	}

	function searchUsers($user_query) {
		$db = getDbConnection();

		$stmt = $db->prepare("SELECT * FROM users WHERE name like ?");
		$stmt->execute(array('%'.$user_query.'%'));
		$user_result = $stmt->fetchAll();

		$db = null;

		return $user_result;
	}

	function insertTwilioDump($content) {
		$db = getDbConnection();

		$stmt = $db->prepare('INSERT INTO twiliodump (dump) VALUES (?)');
		$stmt->execute(array($content));
		$result = $stmt->fetchAll();

		$db = null;
	}

	function getBlastStream() {
		$db = getDbConnection();

		$stmt = $db->prepare('SELECT * FROM twiliodump order by ts desc limit 5');
		$stmt->execute();
		$result = $stmt->fetchAll();

		$db = null;

		return $result;
	}
?>