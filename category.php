<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		category.php - Shows a category with its posts
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('classes/All.php');
	
	$vars = false;
	$conds = false;
	
	try {
		$category = Category::Get($_GET['id']);
		
		$page = ((isset($_GET['page'])) ? $_GET['page'] : 1);
		
		$total_posts = Post::GetPostsByCategory($_GET['id'], -1);
		
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
		
		$posts = Post::GetPostsByCategory($_GET['id'], $offset);
		
		
		$vars = array(
			'older_link' => Category::GetURL($_GET['id'], $page + 1),
			'newer_link' => Category::GetURL($_GET['id'], $page - 1)
		);
		$conds = array(
			'older' => ($page != $total_pages),
			'newer' => ($page != 1)
		);
	}
	catch(CategoryNotFoundException $ex) {
		HTML::ErrorPage('Category not found', 'The requested category was not found in the database.');
	}
	catch(DBConnectionException $ex) {
		HTML::ErrorPage('Database connection error', 'A database connection error occurred. Please try again in a few moments.');
	}
	catch(DBQueryException $ex) {
		HTML::ErrorPage('Database query error', 'A database query error occurred. Please try again in a few moments.');
	}
	
	HTML::Header($category['title']);
	
	Category::DisplayInfo($category, $total_posts);
	
	Post::DisplaySet($posts, 'There are no posts in this category.');
	
	echo HTML::ParseTemplate('pagination', $vars, $conds);
	
	HTML::Footer();
?>