<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		index.php - Shows most recent blog posts (home page)
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('classes/All.php');
	
	HTML::Header('Home');
	
	try {
		$page = ((isset($_GET['page'])) ? $_GET['page'] : 1);
		
		$total_posts = Post::GetRecentPosts(-1);
		
		if($total_posts == 0) {
			$total_posts = 1;
		}
		
		$total_pages = ceil($total_posts / 10);
		
		if($page > $total_pages) {
			$page = $total_pages;
		}
		if($page < 0) {
			$page = 0;
		}
		
		$offset = ($page - 1) * 10;
		
		Post::DisplaySet(Post::GetRecentPosts($offset));
		
		$vars = array(
			'older_link' => (Settings::Get('blog_url') . '/index.php?page=' . ($page + 1)),
			'newer_link' => (Settings::Get('blog_url') . '/index.php?page=' . ($page - 1))
		);
		$conds = array(
			'older' => ($page != $total_pages),
			'newer' => ($page != 1)
		);
		
		echo HTML::ParseTemplate('pagination', $vars, $conds);
	}
	catch(DBConnectionException $ex) {
		HTML::ErrorPage('Database connection error', 'A database connection error occurred. Please try again in a few moments.');
	}
	catch(DBQueryException $ex) {
		HTML::ErrorPage('Database query error', 'A database query error occurred. Please try again in a few moments.');
	}
	
	HTML::Footer();
?>