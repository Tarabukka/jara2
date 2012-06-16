<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/finish.php - Finish message for installation
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('install_lib.php');
	install_header('Finish', 6);
?>
		<h2>Finish</h2>
		<p>
			So, you're here. You've managed to download the files, upload the files, and you've made the files work.
		</p>
		<p>
			Well done! Thanks for using Jara 2. Click &quot;Go&quot; to go to your newly-installed blog.
		</p>
		<a href="<?php echo $_SESSION['blog-url']; ?>" class="button">Go</a>
<?php
	install_footer();
?>