<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/settings.php - Interface for managing remote (in database) settings
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('admin_include.php');
	$action = Utilities::GetAction();
	
	if(!User::Permission('settings')) {
		HTML::ErrorPage('Incorrect permissions', 'You do not have the permission to view this area.');
	}
	
	$crucial_settings = array('blog_url', 'blog_title', 'template');
	
	if($action == 'view') {
		HTML::Header('Settings');
		
		$settings = Settings::GetFromDatabase();
?>
<h2>Settings</h2>
<?php
	switch($_REQUEST['msg']) {
		case 'settings_saved':
?>
<div class="admin-info-msg">
	The settings have been saved.
</div>
<?php
			break;
		case 'setting_deleted':
?>
<div class="admin-info-msg">
	The setting was deleted.
</div>
<?php
			break;
		default:
			break;
	}
?>
<ul class="admin-sections">
	<li>
		<h3><a href="settings.php?action=add"><img src="icons/setting_add.png" /> Add a Setting</a></h3>
	</li>
</ul>
<br />
<form action="settings.php" method="post">
	<input type="hidden" name="action" value="edit" />
	<ul class="admin-list">
<?php
		foreach($settings as $setting) {
?>
	<li>
		<h3><?php echo $setting['friendly_name']; ?> &mdash; <?php echo $setting['key']; ?></h3>
		<span>
			<?php if(!in_array($setting['key'], $crucial_settings)) { ?>
			<a href="javascript:;" onclick="var c = confirm('Are you sure you want to delete this setting?'); if(c) { window.location.href='settings.php?action=delete&settingid=<?php echo $setting['settingid']; ?>&n=<?php echo substr(User::Info('password'), 16, 10); ?>';}" title="Delete setting"><img src="icons/delete.png" alt="Delete setting" border="0" /></a>
			<?php } ?>
		</span>
		<h4><?php if($setting['is_bool']) { ?><input type="checkbox" name="<?php echo $setting['key']; ?>"<?php if($setting['value']) { ?> checked="checked"<?php } ?> /><?php } else { ?><input type="text" name="<?php echo $setting['key']; ?>" value="<?php echo $setting['value']; ?>" /><?php } ?></h4>

	</li>
<?php
		}
?>
	</ul>
	<br />
	<input type="submit" value="Save All" />
</form>
<?php
		HTML::Footer();
	}
	else if($action == 'add') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$key = $_POST['key'];
			$value = $_POST['value'];
			$friendly_name = htmlentities($_POST['friendly_name']);
			$is_bool = isset($_POST['is_bool']);
			$exists = Settings::Set($key);
			if($exists) {
				HTML::ErrorPage('Already exists', 'There is already a setting with that key.');
			}
			$result = Settings::Create($key, $value, $friendly_name, $is_bool);
			if(!$result) {
				HTML::ErrorPage('Setting not added', 'The setting could not be added.');
			}
			header('Location: settings.php?msg=settings_saved');
			exit;
		}
		else {
			HTML::Header('Add Setting');
?>
<h2>Add Setting</h2>
<form action="settings.php" method="post" class="nice-form">
	<input type="hidden" name="action" value="add" />
	<ul>
		<li>
			<label for="friendly_name">Name</label>
			<input type="text" name="friendly_name" />
		</li>
		<li>
			<label for="key">Key</label>
			<input type="text" name="key" />
		</li>
		<li>
			<label for="value">Value (0/1 for true/false)</label>
			<input type="text" name="value" />
		</li>
		<li>
			<label for="is_bool">Is Boolean</label>
			<input type="checkbox" name="is_bool" />
		</li>
	</ul>
	<input type="submit" value="Add" />
</form>
<?php
			HTML::Footer();
		}
	}
	else if($action == 'delete') {
		if($_REQUEST['n'] != substr(User::Info('password'), 16, 10)) {
			HTML::ErrorPage('Possible cross-site request forgery', 'A possible cross-site request forgery attempt was blocked.');
		}
		$key = Settings::GetKeyById($_REQUEST['settingid']);
		if(in_array($key, $crucial_settings)) {
			HTML::ErrorPage('Cannot delete setting', 'The setting cannot be deleted because it is required for Jara to run.');
		}
		$result = Settings::Delete($_REQUEST['settingid']);
		if(!$result) {
			HTML::ErrorPage('Setting not deleted', 'The setting could not be deleted.');
		}
		header('Location: settings.php?msg=setting_deleted');
		exit;
	}
	else if($action == 'edit') {
		$settings = Settings::GetFromDatabase();
		foreach($settings as $row) {
			if(isset($_POST[$row['key']])) {
				if(!$row['is_bool']) {
					Settings::Modify($row['key'], $_POST[$row['key']]);
				}
				else {
					Settings::Modify($row['key'], 1);
				}
			}
			else {
				Settings::Modify($row['key'], 0);
			}
		}
		header('Location: settings.php?msg=settings_saved');
		exit;
	}
?>