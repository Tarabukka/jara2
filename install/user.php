<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/user.php - User information frontend for installation 
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('install_lib.php');
	install_header('Account information', 3);
	foreach($_POST as $k => $v) {
		if(!empty($k)) {
			$_SESSION[$k] = $v;
		}
	}
?>
		<h2>Account information</h2>
		<p>
			Please fill out this information to create the account you use to log in to your blog. Don't forget this! You can set up more accounts later once you've installed and logged in.
		</p>
		<form action="blog_info.php" method="post" id="install-form">
			<p>
				<label for="user-real-name">Display Name / Real Name</label>
				<input type="text" name="user-real-name" />
			</p>
			<p>
				<label for="user-username">Username</label>
				<input type="text" name="user-username" />
			</p>
			<p>
				<label for="user-password">Password</label>
				<input type="password" name="user-password" />
			</p>
			<p>
				<label for="user-email-address">Email Address</label>
				<input type="text" name="user-email-address" />
			</p>
			<a href="javascript:;" onclick="document.getElementById('install-form').submit();" class="button">Next</a>
		</form>
<?php
	install_footer();
?>