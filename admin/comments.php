<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/comments.php - Interface for managing comments
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('admin_include.php');
	$action = Utilities::GetAction();
	
	if(!User::Permission('comments')) {
		HTML::ErrorPage('Incorrect permissions', 'You do not have the permission to view this area.');
	}
	
	if($action == 'view') {
		HTML::Header('Comments');
		
		$page = ((isset($_GET['page'])) ? $_GET['page'] : 1);
		
		$total_comments = Comment::GetAllComments(-1);
		
		if($total_comments == 0) {
			$total_comments = 1;
		}
		
		$total_pages = ceil($total_comments / 50);
		
		if($page > $total_pages) {
			$page = $total_pages;
		}
		if($page < 0) {
			$page = 0;
		}
		
		$offset = ($page - 1) * 50;
		
		$comments = Comment::GetAllComments($offset);
?>
<h2>Comments</h2>
<?php
	switch($_REQUEST['msg']) {
		case 'comment_approved':
?>
<div class="admin-info-msg">
	The comment was approved.
</div>
<?php
			break;
		case 'comment_deleted':
?>
<div class="admin-info-msg">
	The comment was deleted.
</div>
<?php
			break;
		default:
			break;
	}
?>
<ul class="admin-list">
<?php
		foreach($comments as $comment) {
			$post = Post::Get($comment['postid']);
?>
	<li <?php if(!$comment['moderated']) { ?>class="highlight"<?php } ?>>
		<h3><a href="../post.php?id=<?php echo $post['postid']; ?>"><?php echo $post['title']; ?></a></h3>
		<span>
			<?php if(!$comment['moderated']) { ?><a href="comments.php?action=approve&commentid=<?php echo $comment['commentid']; ?>" title="Approve comment"><img src="icons/approve.png" alt="Approve comment" border="0" /></a><?php } ?>
			<a href="javascript:;" onclick="var c = confirm('Are you sure you want to delete this comment?'); if(c) { window.location.href='comments.php?action=delete&commentid=<?php echo $comment['commentid']; ?>&n=<?php echo substr(User::Info('password'), 16, 10); ?>';}" title="Delete comment"><img src="icons/delete.png" alt="Delete comment" border="0" /></a>
		</span>
		<h4><?php echo substr(strip_tags($comment['comment']), 0, 200) . ((strlen($comment['comment']) < 200) ? '' : ' [...]'); ?></h4>
		<p>
			<?php if($comment['userid']) { ?><a href="../user.php?id=<?php echo $comment['userid']; ?>"><?php } echo $comment['author']; if($comment['userid']) { ?></a><?php } ?> - <?php echo $comment['email']; ?> - <?php echo date('jS F Y', $comment['time']); ?>
		</p>
	</li>
<?php
		}
?>
</ul>
<?php
		if(count($comments) == 0) {
?>
<p>
	There are no comments on this blog.
</p>
<?php
		}
		
		$vars = array(
			'older_link' => 'comments.php?page=' . ($page + 1),
			'newer_link' => 'comments.php?page=' . ($page - 1)
		);
		$conds = array(
			'older' => ($page != $total_pages),
			'newer' => ($page != 1)
		);
		
		echo HTML::ParseTemplate('pagination', $vars, $conds);
		
		HTML::Footer();
	}
	else if($action == 'approve') {
		$result = Comment::Approve($_REQUEST['commentid']);
		if(!$result) {
			HTML::ErrorPage('Comment not approved', 'The comment could not be approved.');
		}
		header('Location: comments.php?msg=comment_approved');
		exit;
	}
	else if($action == 'delete') {
		if($_REQUEST['n'] != substr(User::Info('password'), 16, 10)) {
			HTML::ErrorPage('Possible cross-site request forgery', 'A possible cross-site request forgery attempt was blocked.');
		}
		$result = Comment::Delete($_REQUEST['commentid']);
		if(!$result) {
			HTML::ErrorPage('Comment not deleted', 'The comment could not be deleted. Please try again.');
		}
		header('Location: comments.php?msg=comment_deleted');
		exit;
	}
?>