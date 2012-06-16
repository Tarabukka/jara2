<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		login.php - Shows login frontend and logs in user
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('classes/All.php');
	
	$messages = array();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$result = User::Login($_POST['username'], $_POST['password']);
		}
		catch(DBConnectionException $ex) {
			$messages[] = 'A database connection error occurred. Please try again in a few moments.';
		}
		catch(DBQueryException $ex) {
			$messages[] = 'A database query error occurred. Please try again in a few moments.';
		}
		
		if($result) {
			header('Location: index.php');
			exit;
		}
		else {
			if(count($messages) == 0) {
				$messages[] = 'You entered an incorrect username or password.';
			}
		}
	}
	
	if(isset($_REQUEST['message'])) {
		$messages[] = $_REQUEST['message'];
	}
	
	HTML::Header('Login');
	
	HTML::Login($messages);
	
	HTML::Footer();
?>