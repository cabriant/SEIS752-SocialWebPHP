<?php
	include_once 'bsol-session.php';

	$access_token = getFromSession('user_access_token', true);
	if (isset($access_token) && !empty($access_token)) {

		$headers = array(
		    'Accept: application/json',
		    'Content-Type: application/json',
		    'Authorization: Bearer ' . $access_token
		);

		// Get primary calendar
		// https://www.googleapis.com/calendar/v3/users/me/calendarList
		$channel = curl_init();

		curl_setopt($channel, CURLOPT_URL, 'https://www.googleapis.com/calendar/v3/users/me/calendarList');
		curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($channel, CURLOPT_HEADER, 0);

		$result = curl_exec($channel);

		curl_close($channel);

		$resultArray = json_decode($result, true);

		$calendar_id = '';
		$calendar_items = $resultArray['items'];
		foreach ($calendar_items as $item) {
			if ($item['primary']) {
				$calendar_id = $item['id'];
				break;
			}
		}

		if (!empty($calendar_id)) {
			// Get Google Calendars
			// https://www.googleapis.com/calendar/v3/calendars/{calendar_id}/events
			$channel = curl_init();

			curl_setopt($channel, CURLOPT_URL, 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events');
			curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
	    	curl_setopt($channel, CURLOPT_HEADER, 0);

			$result = curl_exec($channel);

			curl_close($channel);

			echo "<h3>Google Calendar Events for Primary Calendar</h3>";
			echo "<div style='margin: 10px 50px;'>" . $result . "</div>";
		}

		// Get 100 files in Google Drive
		// https://www.googleapis.com/drive/v2/files
		$channel = curl_init();

		curl_setopt($channel, CURLOPT_URL, 'https://www.googleapis.com/drive/v2/files');
		curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($channel, CURLOPT_HEADER, 0);

		$result = curl_exec($channel);

		curl_close($channel);

		echo "<h3>Google Drive Top 100 Files</h3>";
		echo "<div style='margin: 10px 50px;'>" . $result . "</div>";
	}

?>