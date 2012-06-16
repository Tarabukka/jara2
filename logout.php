<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		logout.php - Simple script to log out user
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('classes/All.php');
	
	User::Logout();
	
	header('Location: login.php?message=You%20have%20been%20logged%20out.');
?>