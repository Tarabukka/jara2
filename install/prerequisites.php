<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/prerequisites.php - Checks that Jara can be installed
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('install_lib.php');
	install_header('Prerequisites', 1);
?>
		<h2>Prerequisites</h2>
		<h3>Settings file writable</h3>
		<?php
			$file_writable = is_writable('../classes/LocalSettings.php');
			if($file_writable) {
		?>
		<p>
			Well done! PHP can write to the settings file. <img src="assets/tick.png" />
		</p>
		<?php
			}
			else {
		?>
		<p>
			PHP cannot write to the settings file. Please check that the permissions on &quot;classes/LocalSettings.php&quot; are 0777 or all-writable. <img src="assets/cross.png" />
		</p>
		<?php
			}
		?>
		<h3>MySQLi available</h3>
		<?php
			$mysqli_available = function_exists('mysqli_connect');
			if($mysqli_available) {
		?>
		<p>
			PHP can call the MySQLi library. <img src="assets/tick.png" />
		</p>
		<?php
			}
			else {
		?>
		<p>
			PHP can't call the MySQLi library. You will need to install this or ask your host to. It is vital to the operation of Jara. <img src="assets/cross.png" />
		</p>
		<?php
			}
		?>
		<?php
			if($mysqli_available && $file_writable) {
		?>
		<p>
			All of these are alright! Click the Next button to continue and set up MySQL.
		</p>
		<a href="mysql.php" class="button">Next</a>
		<?php
			}
			else {
		?>
		<p>
			You need to fix these issues before continuing.
		</p>
		<?php
			}
		?>
<?php
	install_footer();
?>