<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		search.php - Search frontend
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('classes/All.php');
	
	HTML::Header('Search');
	
?>
<h2>Search</h2>
<?php
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			Post::DisplaySet(Post::SearchPosts($_POST['search_term']), 'There were no results for your search.');
		}
		catch(DBConnectionException $ex) {
			HTML::ErrorPage('Database connection error', 'A database connection error occurred. Please try again in a few moments.');
		}
		catch(DBQueryException $ex) {
			HTML::ErrorPage('Database query error', 'A database query error occurred. Please try again in a few moments.');
		}
	}
	else {
		HTML::Search();
	}
	
	HTML::Footer();
?>