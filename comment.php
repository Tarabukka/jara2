<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		comment.php - Verifies and posts a comment
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('classes/All.php');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$postid = $_POST['postid'];
		$author = ((User::LoggedIn()) ? User::Info('real_name') : $_POST['author']);
		$email = ((User::LoggedIn()) ? User::Info('email') : $_POST['email']);
		$comment = $_POST['comment'];
		
		if(empty($author) || empty($email) || empty($comment)) {
			HTML::ErrorPage('Some fields not filled out', 'You need to fill out all the fields.');
		}
		
		if(empty($postid)) { // Even though empty() checks for 0 too, that's fine, because no post should have ID 0.
			HTML::ErrorPage('No post ID supplied', 'No post ID was supplied.');
		}
		
		$post = Post::Get($postid);
		
		if(!$post['comments_enabled']) {
			HTML::ErrorPage('Comments not enabled', 'Comments are not enabled for that post.');
		}
		
		try {
			Comment::Write($postid, $author, $email, $comment);
		}
		catch(DBConnectionException $ex) {
			HTML::ErrorPage('Database connection error', 'A database connection error occurred. Please try again in a few moments.');
		}
		catch(DBQueryException $ex) {
			HTML::ErrorPage('Database query error', 'A database query error occurred. Please try again in a few moments.');
		}
		
		
		
		HTML::Header('Comment posted');
?>
<h2>Comment posted</h2>
<p>
	Thank you for your comment! Your comment will be manually moderated before being publicly visible.
</p>
<p>
	<a href="<?php echo Post::GetURL($postid); ?>">Back to <strong><?php echo $post['title']; ?></strong></a>
</p>
<?php
		HTML::Footer();
		exit;
	}
	else {
		HTML::ErrorPage('Bad request', 'The wrong request method method was used to access this page. It should be POST.');
	}
?>