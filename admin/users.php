<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/users.php - Interface for managing users
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('admin_include.php');
	$action = Utilities::GetAction();
	
	if(!User::Permission('users')) {
		HTML::ErrorPage('Incorrect permissions', 'You do not have the permission to view this area.');
	}
	
	if($action == 'view') {
		HTML::Header('Users');
		
		$page = ((isset($_GET['page'])) ? $_GET['page'] : 1);
		
		$total_users = User::GetAllUsers(-1);
		
		if($total_users == 0) {
			$total_users = 1;
		}
		
		$total_pages = ceil($total_users / 50);
		
		if($page > $total_pages) {
			$page = $total_pages;
		}
		if($page < 0) {
			$page = 0;
		}
		
		$offset = ($page - 1) * 50;
		
		$users = User::GetAllUsers($offset, 50);
		
?>
<h2>Users</h2>
<?php
	switch($_REQUEST['msg']) {
		case 'user_saved':
?>
<div class="admin-info-msg">
	The user was saved.
</div>
<?php
			break;
		case 'user_deleted':
?>
<div class="admin-info-msg">
	The user was deleted.
</div>
<?php
			break;
		default:
			break;
	}
?>
<ul class="admin-sections">
	<li>
		<h3><a href="users.php?action=add"><img src="icons/user_add.png" /> Add a User</a></h3>
	</li>
</ul>
<br />
<ul class="admin-list">
<?php
	foreach($users as $user) {
?>
<li>
	<h3><?php echo $user['real_name']; ?></h3>
	<span>
		<?php if($user['userid'] != 1 || User::Info('userid') == 1) { ?>
		<a href="users.php?action=edit&userid=<?php echo $user['userid']; ?>" title="Edit user"><img src="icons/edit.png" alt="Edit user" border="0" /></a>
		<?php if($user['userid'] != User::Info('userid')) { ?>
		<a href="javascript:;" onclick="var c = confirm('Are you sure you want to delete this user?'); if(c) { window.location.href='users.php?action=delete&userid=<?php echo $user['userid']; ?>&n=<?php echo substr(User::Info('password'), 16, 10); ?>';}" title="Delete user"><img src="icons/delete.png" alt="Delete user" border="0" /></a>
		<?php } } ?>
	</span>
	<h4><?php echo $user['bio']; ?></h4>
<?php
	}
		
	$vars = array(
		'older_link' => 'users.php?page=' . ($page + 1),
		'newer_link' => 'users.php?page=' . ($page - 1)
	);
	$conds = array(
		'older' => ($page != $total_pages),
		'newer' => ($page != 1)
	);
	
	echo HTML::ParseTemplate('pagination', $vars, $conds);
		
		HTML::Footer();
	}
	else if($action == 'add') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$real_name = htmlentities($_POST['real_name']);
			$email = $_POST['email'];
			$admin_permission = isset($_POST['admin_permission']);
			$posts_permission = isset($_POST['posts_permission']);
			$comments_permission = isset($_POST['comments_permission']);
			$categories_permission = isset($_POST['categories_permission']);
			$users_permission = isset($_POST['users_permission']);
			$settings_permission = isset($_POST['settings_permission']);
			$edit_not_own_permission = isset($_POST['edit_not_own_permission']);
			
			$admin_permission = ((User::Info('admin_permission')) ? $admin_permission : false);
			$posts_permission = ((User::Info('posts_permission')) ? $posts_permission : false);
			$comments_permission = ((User::Info('comments_permission')) ? $comments_permission : false);
			$categories_permission = ((User::Info('categories_permission')) ? $categories_permission : false);
			$users_permission = ((User::Info('users_permission')) ? $users_permission : false);
			$settings_permission = ((User::Info('settings_permission')) ? $settings_permission : false);
			$edit_not_own_permission = ((User::Info('edit_not_own_permission')) ? $edit_not_own_permission : false);
			
			$permissions = array('admin' => $admin_permission, 'posts' => $posts_permission, 'comments' => $comments_permission, 'categories' => $categories_permission, 'users' => $users_permission, 'settings' => $settings_permission, 'edit_not_own' => $edit_not_own_permission);
			
			$exists = User::GetInfoByUsername($username);
			if(empty($username) || empty($password) || empty($real_name) || empty($email)) {
				HTML::ErrorPage('You need to fill out all the fields', 'You need to fill out all the fields.');
			}
			if($exists) {
				HTML::ErrorPage('Already exists', 'There is already a user with that username.');
			}
			$result = User::Create($username, $password, $email, $real_name, $permissions);
			if(!$result) {
				HTML::ErrorPage('User not added', 'The user could not be added.');
			}
			header('Location: users.php?msg=user_saved');
			exit;
		}
		else {
			HTML::Header('Add User');
?>
<h2>Add User</h2>
<form action="users.php" method="post" class="nice-form">
	<input type="hidden" name="action" value="add" />
	<ul>
		<li>
			<label for="real_name">Real Name / Display Name</label>
			<input type="text" name="real_name" />
		</li>
		<li>
			<label for="username">Username</label>
			<input type="text" name="username" />
		</li>
		<li>
			<label for="password">Password</label>
			<input type="password" name="password" />
		</li>
		<li>
			<label for="email">Email</label>
			<input type="text" name="email" />
		</li>
		<li>
			<label>Permissions</label>
			<?php if(User::Info('admin_permission')) { ?><input type="checkbox" name="admin_permission" /> Administration access<br /><?php } ?>
			<?php if(User::Info('posts_permission')) { ?><input type="checkbox" name="posts_permission" /> Posts<br /><?php } ?>
			<?php if(User::Info('comments_permission')) { ?><input type="checkbox" name="comments_permission" /> Comments<br /><?php } ?>
			<?php if(User::Info('categories_permission')) { ?><input type="checkbox" name="categories_permission" /> Categories<br /><?php } ?>
			<?php if(User::Info('users_permission')) { ?><input type="checkbox" name="users_permission" /> Users<br /><?php } ?>
			<?php if(User::Info('settings_permission')) { ?><input type="checkbox" name="settings_permission" /> Settings<br /><?php } ?>
			<?php if(User::Info('edit_not_own_permission')) { ?><input type="checkbox" name="edit_not_own_permission" /> Edit posts not by them<?php } ?>
		</li>
	</ul>
	<input type="submit" value="Add" />
</form>
<?php
			HTML::Footer();
		}
	}
	else if($action == 'edit') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$userid = $_REQUEST['userid'];
			$user = false;
			try {
				$user = User::GetInfoById($userid);
			}
			catch(UserNotFoundException $ex) {
				HTML::ErrorPage('User does not exist', 'The user you requested does not exist.');
			}
			if($userid == 1 && User::Info('userid') != 1) {
				HTML::ErrorPage('Cannot edit', 'This user cannot be edited.');
			}
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			$real_name = htmlentities($_POST['real_name']);
			$email = $_POST['email'];
			$admin_permission = isset($_POST['admin_permission']);
			$posts_permission = isset($_POST['posts_permission']);
			$comments_permission = isset($_POST['comments_permission']);
			$categories_permission = isset($_POST['categories_permission']);
			$users_permission = isset($_POST['users_permission']);
			$settings_permission = isset($_POST['settings_permission']);
			$edit_not_own_permission = isset($_POST['edit_not_own_permission']);
			
			$admin_permission = ((User::Info('admin_permission')) ? $admin_permission : false);
			$posts_permission = ((User::Info('posts_permission')) ? $posts_permission : false);
			$comments_permission = ((User::Info('comments_permission')) ? $comments_permission : false);
			$categories_permission = ((User::Info('categories_permission')) ? $categories_permission : false);
			$users_permission = ((User::Info('users_permission')) ? $users_permission : false);
			$settings_permission = ((User::Info('settings_permission')) ? $settings_permission : false);
			$edit_not_own_permission = ((User::Info('edit_not_own_permission')) ? $edit_not_own_permission : false);
			
			$permissions = array('admin' => $admin_permission, 'posts' => $posts_permission, 'comments' => $comments_permission, 'categories' => $categories_permission, 'users' => $users_permission, 'settings' => $settings_permission, 'edit_not_own' => $edit_not_own_permission);
			
			
			$success = true;
			
			if(!empty($password)) {
				$result = User::Modify('password', sha1($password), $userid);
				$success = ((!$success) ? false : $result);
			}
			$result = User::Modify('username', $username, $userid);
			$success = ((!$success) ? false : $result);
			$result = User::Modify('real_name', $real_name, $userid);
			$success = ((!$success) ? false : $result);
			$result = User::Modify('email', $email, $userid);
			$success = ((!$success) ? false : $result);
			foreach($permissions as $k => $v) {
				$result = User::Modify($k . '_permission', ($v ? 1 : 0), $userid);
				$success = ((!$success) ? false : $result);
			}
			
			if(!$success) {
				HTML::ErrorPage('User not saved', 'The user could not be saved.');
			}
			header('Location: users.php?msg=user_saved');
			exit;
		}
		else {
			$userid = $_REQUEST['userid'];
			$user = false;
			try {
				$user = User::GetInfoById($userid);
			}
			catch(UserNotFoundException $ex) {
				HTML::ErrorPage('User does not exist', 'The user you requested does not exist.');
			}
			if($userid == 1 && User::Info('userid') != 1) {
				HTML::ErrorPage('Cannot edit', 'This user cannot be edited.');
			}
			HTML::Header('Edit User');
?>
<h2>Edit User</h2>
<form action="users.php" method="post" class="nice-form">
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
	<ul>
		<li>
			<label for="real_name">Real Name / Display Name</label>
			<input type="text" name="real_name" value="<?php echo html_entity_decode($user["real_name"]); ?>" />
		</li>
		<li>
			<label for="username">Username</label>
			<input type="text" name="username" value="<?php echo $user["username"]; ?>" />
		</li>
		<li>
			<label for="password">Password (blank to not change)</label>
			<input type="password" name="password" />
		</li>
		<li>
			<label for="email">Email</label>
			<input type="text" name="email" value="<?php echo $user["email"]; ?>" />
		</li>
		<li>
			<label>Permissions</label>
			<?php if(User::Info('admin_permission')) { ?><input type="checkbox" name="admin_permission" <?php if($user["admin_permission"]) { ?>checked="checked"<?php } ?> /> Administration access<br /><?php } ?>
			<?php if(User::Info('posts_permission')) { ?><input type="checkbox" name="posts_permission" <?php if($user["posts_permission"]) { ?>checked="checked"<?php } ?> /> Posts<br /><?php } ?>
			<?php if(User::Info('comments_permission')) { ?><input type="checkbox" name="comments_permission" <?php if($user["comments_permission"]) { ?>checked="checked"<?php } ?> /> Comments<br /><?php } ?>
			<?php if(User::Info('categories_permission')) { ?><input type="checkbox" name="categories_permission" <?php if($user["categories_permission"]) { ?>checked="checked"<?php } ?> /> Categories<br /><?php } ?>
			<?php if(User::Info('users_permission')) { ?><input type="checkbox" name="users_permission" <?php if($user["users_permission"]) { ?>checked="checked"<?php } ?> /> Users<br /><?php } ?>
			<?php if(User::Info('settings_permission')) { ?><input type="checkbox" name="settings_permission" <?php if($user["settings_permission"]) { ?>checked="checked"<?php } ?> /> Settings<br /><?php } ?>
			<?php if(User::Info('edit_not_own_permission')) { ?><input type="checkbox" name="edit_not_own_permission" <?php if($user["edit_not_own_permission"]) { ?>checked="checked"<?php } ?> /> Edit posts not by them<?php } ?>
		</li>
	</ul>
	<input type="submit" value="Save" />
</form>
<?php
			HTML::Footer();
		}
	}
	else if($action == 'delete') {
		if($_REQUEST['n'] != substr(User::Info('password'), 16, 10)) {
			HTML::ErrorPage('Possible cross-site request forgery', 'A possible cross-site request forgery attempt was blocked.');
		}
		if($_REQUEST['userid'] == 1 || $_REQUEST['userid'] == User::Info('userid')) {
			HTML::ErrorPage('User cannot be deleted', 'You cannot delete this user.');
		}
		$result = User::Delete($_REQUEST['userid']);
		if(!$result) {
			HTML::ErrorPage('User not deleted', 'The user could not be deleted.');
		}
		header('Location: users.php?msg=user_deleted');
		exit;
	}
?>