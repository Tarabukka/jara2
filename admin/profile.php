<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/profile.php - Edit the current administrator's account information
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('admin_include.php');
	HTML::Header('Profile');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$email = $_POST['email'];
		$password = $_POST['password'];
		$real_name = htmlentities($_POST['real_name']);
		$bio = htmlentities($_POST['bio']);
		$location = htmlentities($_POST['location']);
		
		$result = User::UpdateProfile($email, $password, $real_name, $bio, $location);
		
		if(!$result) {
			HTML::ErrorPage('Profile not updated', 'Your profile could not be updated.');
		}
		
?>
<h2>Profile Updated</h2>
<p>
	Your profile has been updated.
</p>
<?php
	}
	else {
?>
<h2>Edit Profile</h2>
<form action="profile.php" method="post" class="nice-form">
	<ul>
		<li>
			<label for="real_name">Real Name / Display Name</label>
			<input type="text" name="real_name" value="<?php echo html_entity_decode(User::Info('real_name')); ?>" />
		</li>
		<li>
			<label for="password">Password (blank to not change)</label>
			<input type="password" name="password" />
		</li>
		<li>
			<label for="email">Email</label>
			<input type="text" name="email" value="<?php echo User::Info('email'); ?>" />
		</li>
		<li>
			<label for="bio">Bio</label>
			<textarea name="bio"><?php echo html_entity_decode(User::Info('bio')); ?></textarea>
		</li>
		<li>
			<label for="location">Location</label>
			<input type="text" name="location" value="<?php echo html_entity_decode(User::Info('location')); ?>" />
		</li>
	</ul>
	<input type="submit" value="Save" />
</form>
<?php
	}
	
	HTML::Footer();
?>