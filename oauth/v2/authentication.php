<?php
	include_once '../../bsol-session.php';

	if (isset($_GET['code']) && !empty($_GET['code'])) {

		$auth_token = $_GET['code'];
		$params = array(
		    'code'=>$auth_token,
		    'client_id'=>'244384673234-vfh4f1dnmsl31o124osebabnmio30jqe.apps.googleusercontent.com',
		    'client_secret'=>'oZashkISDKVOTV5ef3yQE9tY',
		    'redirect_uri'=>'http://briantsolutions.com/oauth/v2/authentication.php',
		    'grant_type'=>'authorization_code'
		);

		$postContent='';
		$separator='';
		foreach($params as $k=>$v)
		{
		        $postContent .= $separator . urlencode($k) . '=' . urlencode($v);
		        $separator = '&';
		}

		$channel = curl_init();

		curl_setopt($channel,CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
		curl_setopt($channel,CURLOPT_POST, count($params));
		curl_setopt($channel,CURLOPT_POSTFIELDS, $postContent);
		curl_setopt($channel,CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($channel);

		curl_close($channel);

		$resultArray = json_decode($result, true);

		storeInSession('user_access_token', $resultArray['access_token']);

		header('Location: /oauth-info.php');
		exit();

	} else if (isset($_GET['error']) && !empty($_GET['error'])) {
		// error
		
	}
	
	header('Location: login.php');
	exit();
?>