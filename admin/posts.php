<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/posts.php - Interface for managing posts
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('admin_include.php');
	$action = Utilities::GetAction();
	
	if(!User::Permission('posts')) {
		HTML::ErrorPage('Incorrect permissions', 'You do not have the permission to view this area.');
	}
	
	if($action == 'view') {
		HTML::Header('Posts');
		
		$page = ((isset($_GET['page'])) ? $_GET['page'] : 1);
		
		$total_posts = Post::GetRecentPosts(-1);
		
		if($total_posts == 0) {
			$total_posts = 1;
		}
		
		$total_pages = ceil($total_posts / 20);
		
		if($page > $total_pages) {
			$page = $total_pages;
		}
		if($page < 0) {
			$page = 0;
		}
		
		$offset = ($page - 1) * 20;
		
		$posts = Post::GetRecentPosts($offset, 20);
?>
<h2>Posts</h2>
<?php
	switch($_REQUEST['msg']) {
		case 'post_saved':
?>
<div class="admin-info-msg">
	The post was saved.
</div>
<?php
			break;
		case 'post_deleted':
?>
<div class="admin-info-msg">
	The post was deleted.
</div>
<?php
			break;
		default:
			break;
	}
?>
<ul class="admin-sections">
	<li>
		<h3><a href="posts.php?action=write"><img src="icons/post_write.png" /> Write a Post</a></h3>
	</li>
</ul>
<br />
<ul class="admin-list">
<?php
		foreach($posts as $post) {
			$author = false;
			try {
				$author = User::GetInfoById($post['userid']);
			}
			catch(UserNotFoundException $ex) {
				$author = array(
					'userid' => 0,
					'real_name' => 'Deleted author'
				);
			}
?>
	<li>
		<h3><?php echo $post['title']; ?></h3>
		<span>
			<?php
				if(User::Permission('edit_not_own') || (!User::Permission('edit_not_own') && $post['userid'] == User::Info('userid'))) {
			?>
			<a href="../post.php?id=<?php echo $post['postid']; ?>" title="View post"><img src="icons/view.png" alt="View post" border="0" /></a>
			<a href="posts.php?action=edit&postid=<?php echo $post['postid']; ?>" title="Edit post"><img src="icons/edit.png" alt="Edit post" border="0" /></a>
			<a href="javascript:;" onclick="var c = confirm('Are you sure you want to delete this post?'); if(c) { window.location.href='posts.php?action=delete&postid=<?php echo $post['postid']; ?>&n=<?php echo substr(User::Info('password'), 16, 10); ?>';}" title="Delete post"><img src="icons/delete.png" alt="Delete post" border="0" /></a>
			<?php
				}
				else {
			?>
			<a href="../post.php?id=<?php echo $post['postid']; ?>" title="View post"><img src="icons/view.png" alt="View post" /></a>
			<?php
				}
			?>
		</span>
		<h4><?php echo substr(strip_tags($post['content']), 0, 200) . ((strlen($post['content']) < 200) ? '' : ' [...]'); ?></h4>
		<p>
			<?php echo $author['real_name']; ?> - <?php echo date('jS F Y', $post['time']); ?>
		</p>
	</li>
<?php
		}
?>
</ul>
<br />
<?php
		if(count($posts) == 0) {
?>
<p>
	There are no posts on this blog. Why not <a href="posts.php?action=write">write one</a>?
</p>
<?php
		}
		$vars = array(
			'older_link' => 'posts.php?page=' . ($page + 1),
			'newer_link' => 'posts.php?page=' . ($page - 1)
		);
		$conds = array(
			'older' => ($page != $total_pages),
			'newer' => ($page != 1)
		);
		
		echo HTML::ParseTemplate('pagination', $vars, $conds);
		HTML::Footer();
	}
	else if($action == 'delete') {
		if($_REQUEST['n'] != substr(User::Info('password'), 16, 10)) {
			HTML::ErrorPage('Possible cross-site request forgery', 'A possible cross-site request forgery attempt was blocked.');
		}
		$post = false;
		try {
			$post = Post::Get($_REQUEST['postid']);
		}
		catch(PostNotFoundException $ex) {
			HTML::ErrorPage('Post does not exist', 'The post you attempted to delete does not exist.');
		}
		if(!User::Permission('edit_not_own') && $post['userid'] != User::Info('userid')) {
			HTML::ErrorPage('Insufficient permissions', 'You are not allowed to delete this post.');
		}
		$result = Post::Delete($_REQUEST['postid']);
		if(!$result) {
			HTML::ErrorPage('Post not deleted', 'Your post could not be deleted. Please try again.');
		}
		header('Location: posts.php?msg=post_deleted');
		exit;
	}
	else if($action == 'edit') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$postid = $_POST['postid'];
			$post = false;
			try {
				$post = Post::Get($postid);
			}
			catch(PostNotFoundException $ex) {
				HTML::ErrorPage('Post does not exist', 'The post you requested does not exist.');
			}
			if(!User::Permission('edit_not_own') && $post['userid'] != User::Info('userid')) {
				HTML::ErrorPage('Insufficient permissions', 'You are not allowed to edit this post.');
			}
			$title = $_POST['title'];
			$content = $_POST['post_content'];
			$categoryid = $_POST['categoryid'];
			$allow_comments = (isset($_POST['allow_comments']) ? true : false);
			if(empty($postid) || empty($title) || empty($content) || empty($categoryid)) {
				HTML::ErrorPage('You need to fill out all the fields', 'You need to fill out all the fields.');
			}
			$result = Post::Modify($postid, $title, $content, $categoryid, $allow_comments);
			if(!$result) {
				HTML::ErrorPage('Post not saved', 'Your post could not be saved. Please try again.');
			}
			header('Location: posts.php?msg=post_saved');
			exit;
		}
		else {
			$post = false;
			try {
				$post = Post::Get($_REQUEST['postid']);
			}
			catch(PostNotFoundException $ex) {
				HTML::ErrorPage('Post does not exist', 'The post you requested does not exist.');
			}
			if(!User::Permission('edit_not_own') && $post['userid'] != User::Info('userid')) {
				HTML::ErrorPage('Insufficient permissions', 'You are not allowed to edit this post.');
			}
			HTML::Header('Edit Post', YUI_EDITOR_FILES, YUI_EDITOR_EXTRA_BODY);
			$categories = Category::GetAllCategories();
?>
<h2>Edit Post</h2>
<form action="posts.php" method="post" class="nice-form" id="editor-form">
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="postid" value="<?php echo $post['postid']; ?>" />
	<ul>
		<li>
			<label for="title">Title</label>
			<input type="text" name="title" value="<?php echo $post['title']; ?>" />
		</li>
		<li>
			<label for="post_content">Post Content</label>
			<textarea name="post_content" id="post_content"><?php echo $post['content']; ?></textarea>
			<?php
				echo yui_editor_script('post_content');
			?>
		</li>
		<li>
			<label for="categoryid">Category</label>
			<select name="categoryid" class="j">
				<?php
					foreach($categories as $category) {
				?>
				<option value="<?php echo $category['categoryid']; ?>"<?php if($post['categoryid'] == $category['categoryid']) { ?> selected="selected"<?php } ?>><?php echo $category['title']; ?></option>
				<?php
					}
				?>
			</select>
		</li>
		<li>
			<label for="allow_comments">Allow Comments</label>
			<input type="checkbox" name="allow_comments" class="checkbox"<?php if($post['comments_enabled']) {?> checked="checked"<?php } ?> />
		</li>
	</ul>
	<input type="submit" value="Save" />
</form>
<?php
			HTML::Footer();
		}
	}
	else if($action == 'write') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$title = htmlentities($_POST['title']);
			$content = $_POST['post_content'];
			$categoryid = $_POST['categoryid'];
			$allow_comments = (isset($_POST['allow_comments']));
			if(empty($title) || empty($content) || empty($categoryid)) {
				HTML::ErrorPage('You need to fill out all the fields', 'You need to fill out all the fields.');
			}
			$result = Post::Write($title, $content, $categoryid, $allow_comments);
			if(!$result) {
				HTML::ErrorPage('Post not saved', 'Your post was not saved. Please try again.');
			}
			header('Location: posts.php?msg=post_saved');
			exit;
		}
		else {
			HTML::Header('Write Post', YUI_EDITOR_FILES, YUI_EDITOR_EXTRA_BODY);
			$categories = Category::GetAllCategories();
?>
<h2>Write Post</h2>
<form action="posts.php" method="post" class="nice-form" id="editor-form">
	<input type="hidden" name="action" value="write" />
	<ul>
		<li>
			<label for="title">Title</label>
			<input type="text" name="title" />
		</li>
		<li>
			<label for="post_content">Post Content</label>
			<textarea name="post_content" id="post_content"></textarea>
			<?php
				echo yui_editor_script('post_content');
			?>
		</li>
		<li>
			<label for="categoryid">Category</label>
			<select name="categoryid" class="j">
				<?php
					foreach($categories as $category) {
				?>
				<option value="<?php echo $category['categoryid']; ?>"><?php echo $category['title']; ?></option>
				<?php
					}
				?>
			</select>
		</li>
		<li>
			<label for="allow_comments">Allow Comments</label>
			<input type="checkbox" name="allow_comments" class="checkbox" checked="checked" />
		</li>
	</ul>
	<input type="submit" value="Post" />
</form>
<?php
			HTML::Footer();
		}
	}
	
	
?>