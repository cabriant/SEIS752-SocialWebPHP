<?php
	
	include_once('../../bsol-db.php');

	if (isset($_POST['AccountSid']) && strcasecmp('AC77dcbb4783bca7a45856955f8f203167', $_POST['AccountSid']) == 0)
	{
		$keyValueArray = array();

		foreach ($_POST as $k => $v) {
			$keyValueArray[$k] = $v;
		}

		$dumpContent = json_encode($keyValueArray);

		insertTwilioDump($dumpContent);
	}
?>