<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/index.php - Installation welcome page
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('install_lib.php');
	install_header('Welcome', 0);
?>
		<h2>Welcome</h2>
		<p>
			So, you're here. You've managed to download the files, upload the files, and now you're making the files work. How fun.
		</p>
		<p>
			Are you ready for better, faster and simpler blogging? Click that &quot;next&quot; button and get going!
		</p>
		<a href="prerequisites.php" class="button">Next</a>
<?php
	install_footer();
?>