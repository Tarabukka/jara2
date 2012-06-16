<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/index.php - Administration control panel overview page
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('admin_include.php');
	HTML::Header('Administration');
	
	$posts = 0;
	$new_comments = 0;
	$categories = 0;
	$users = 0;
	$settings = 0;
	
	if(User::Permission('posts')) {
		$posts = Post::GetAllPosts(-1);
	}
	if(User::Permission('comments')) {
		$new_comments = Comment::GetNewComments(-1);
	}
	if(User::Permission('categories')) {
		$categories = Category::GetAllCategories(-1);
	}
	if(User::Permission('users')) {
		$users = User::GetAllUsers(-1);
	}
	if(User::Permission('settings')) {
		$settings = Settings::GetCountFromDatabase();
	}
?>
<h2>Administration - <?php echo Settings::Get('blog_title'); ?></h2>
<br />
<h2>Quick Actions</h2>
<ul class="admin-sections">
	<?php
		if(User::Permission('posts')) {
	?>
	<li>
		<h3><a href="posts.php?action=write"><img src="icons/post_write.png" /> Write a Post</a></h3>
	</li>
	<?php
		}
	?>
</ul>
<br />
<h2>Content</h2>
<ul class="admin-sections">
	<li>
		<h3><a href="profile.php"><img src="icons/edit.png" /> Profile</a></h3>
	</li>
	<?php
		if(User::Permission('posts')) {
	?>
	<li>
		<h3><a href="posts.php"><img src="icons/posts.png" /> Posts</a></h3>
		<h4><a href="posts.php"><?php echo number_format($posts); ?></a></h4>
	</li>
	<?php
		}
		if(User::Permission('comments')) {
	?>
	<li>
		<h3><a href="comments.php"><img src="icons/comments.png" /> Comments</a></h4>
		<h4><a href="comments.php"><?php echo number_format($new_comments); ?> new</a></h4>
	</li>
	<?php
		}
		if(User::Permission('categories')) {
	?>
	<li>
		<h3><a href="categories.php"><img src="icons/categories.png" /> Categories</a></h3>
		<h4><a href="categories.php"><?php echo number_format($categories); ?></a></h4>
	</li>
	<?php
		}
		if(User::Permission('users')) {
	?>
	<li>
		<h3><a href="users.php"><img src="icons/users.png" /> Users</a></h3>
		<h4><a href="users.php"><?php echo number_format($users); ?></a></h4>
	</li>
	<?php
		}
		if(User::Permission('settings')) {
	?>
	<li>
		<h3><a href="settings.php"><img src="icons/settings.png" /> Settings</a></h3>
		<h4><a href="settings.php"><?php echo number_format($settings); ?></a></h4>
	</li>
	<?php
		}
	?>
</ul>
<?php
	HTML::Footer();
?>