<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		post.php - Shows a post
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('classes/All.php');
	
	try {
		$post = Post::Get($_GET['id']);
	}
	catch(PostNotFoundException $ex) {
		HTML::ErrorPage('Post not found', 'The requested post was not found in the database.');
	}
	catch(DBConnectionException $ex) {
		HTML::ErrorPage('Database connection error', 'A database connection error occurred. Please try again in a few moments.');
	}
	catch(DBQueryException $ex) {
		HTML::ErrorPage('Database query error', 'A database query error occurred. Please try again in a few moments.');
	}
	
	HTML::Header($post['title']);
	
	Post::Display($post);
	
	Comment::Display($_GET['id']);
	
	if($post['comments_enabled']) {
		Comment::DisplayForm($_GET['id']);
	}
	else {
		echo '<p>Comments are not enabled for this post.</p>';
	}
	
	HTML::Footer();
?>