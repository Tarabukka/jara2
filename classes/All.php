<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/All.php - Loads all classes and performs system checks
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/Settings.php");
	Settings::LoadFromFile("LocalSettings.php");
	if(defined('jara2_not_installed')) {
		header('Location: install');
	}
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/DB.php");
	Settings::LoadFromDatabase();
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/User.php");
	User::Refresh();
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/HTML.php");
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/Post.php");
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/Comment.php");
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/Category.php");
	require_once(str_replace('\\', '/', dirname(__FILE__)) . "/Utilities.php");
	$check = Utilities::SystemCheck();
	if($check == 1) {
		try {
			DB::Get();
		}
		catch(DBConnectionException $ex) {
			echo '<html><head><title>Jara: Base Error</title><style type="text/css">html{background:#eee;font:12px Helvetica,Arial,sans-serif}body{width:760px;margin:0 auto;background:#fff;padding:10px}</style></head><body><h1>Jara: Base Error</h1><p>There was an error connecting to the database.</p><blockquote>' . $ex->getMessage() . ' [MySQL error code ' . $ex->getCode() . ']</blockquote><p>Please try again in a few moments. This may be a configuration issue.</p></body></html>';
			exit;
		}
	}
	else if($check == 2) {
		echo '<html><head><title>Jara: Base Error</title><style type="text/css">html{background:#eee;font:12px Helvetica,Arial,sans-serif}body{width:760px;margin:0 auto;background:#fff;padding:10px}</style></head><body><h1>Jara: Base Error</h1><p>The template specified (' . Settings::Get('template') .') does not exist.</p><p>This is a configuration issue. Please double-check that the directory exists.</p></body></html>';
		exit;
	}
?>