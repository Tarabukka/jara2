<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/install.php - Installs Jara
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('install_lib.php');
	foreach($_POST as $k => $v) {
		if(!empty($k)) {
			$_SESSION[$k] = $v;
		}
	}
	install_header('Installing...', 5);
?>
		<h2>Installing...</h2>
		<p>
			<strong>Validating information...</strong>
			<?php
				$keys = array('mysql-hostname', 'mysql-username', 'mysql-password', 'mysql-database-name', 'user-real-name', 'user-username', 'user-password', 'user-email-address', 'blog-title', 'blog-url');
				$alright = true;
				foreach($keys as $v) {
					echo $v;
					if(strlen($_SESSION[$v]) == 0) {
						echo ' is <strong>empty</strong>; ';
						$alright = false;
					}
					else {
						echo ' is OK; ';
					}
				}
				if(!$alright) {
?>
		</p>
		<p>
			One of the required values was empty.
		</p>
<?php
					install_footer();
					exit;
				}
			?>
		</p>
		<p>
			<strong>Retesting prerequisites...</strong>
			<?php
				$file_writable = is_writable('../classes/LocalSettings.php');
				if(!$file_writable) {
					echo 'Settings file not writable; ';
				}
				else {
					echo 'Settings file OK; ';
				}
				$mysqli_available = function_exists('mysqli_connect');
				if(!$mysqli_available) {
					echo 'MySQLi not available; ';
				}
				else {
					echo 'MySQLi available; ';
				}
				if(!$file_writable || !$mysqli_available) {
?>
		</p>
		<p>
			One of the required values was empty.
		</p>
<?php
					install_footer();
					exit;
				}
?>
		</p>
		<p>
			<strong>Testing MySQL...</strong>
			<?php
				$db = new mysqli($_SESSION['mysql-hostname'], $_SESSION['mysql-username'], $_SESSION['mysql-password'], $_SESSION['mysql-database-name']);
				if(mysqli_connect_errno()) {
					echo 'Connection failed: ' . mysqli_connect_error() . ' [MySQL code ' . mysqli_connect_errno() . '];';
?>
		</p>
		<p>
			Could not connect to the database.
		</p>
<?php
					install_footer();
					exit;
				}
				else {
					echo 'Connected;';
				}
			?>
		</p>
		<p>
			<strong>Installing into database...</strong>
			<?php
				echo 'Opening file; ';
				$v = file_get_contents('install_jara2.sql');
				echo 'Replacing constants; ';
				$v = str_replace('<<table_prefix>>', $_SESSION['mysql-table-prefix'], $v);
				$v = str_replace('<<blog_title>>', $_SESSION['blog-title'], $v);
				$v = str_replace('<<blog_url>>', $_SESSION['blog-url'], $v);
				$v = str_replace('<<root_username>>', $_SESSION['user-username'], $v);
				$v = str_replace('<<root_password>>', $_SESSION['user-password'], $v);
				$v = str_replace('<<root_email>>', $_SESSION['user-email-address'], $v);
				$v = str_replace('<<root_real_name>>', $_SESSION['user-real-name'], $v);
				echo 'Running queries; ';
				$db->multi_query($v);
				if($db->errno) {
					echo 'Query failed: ' . $db->error . ' [MySQL code ' . $db->errno . ']; ';
?>
		</p>
		<p>
			Could not install the table structure.
		</p>
<?php
					install_footer();
					exit;
				}
				else {
					echo 'Query success; ';
				}
				echo 'Closing database link; ';
				$db->close();
			?>
		</p>
		<p>
			<strong>Writing settings...</strong>
			<?php
				echo 'Constructing file; ';
				$v = file_get_contents('local_settings_template');
				$v = str_replace('<<table_prefix>>', $_SESSION['mysql-table-prefix'], $v);
				$v = str_replace('<<mysql_hostname>>', $_SESSION['mysql-hostname'], $v);
				$v = str_replace('<<mysql_username>>', $_SESSION['mysql-username'], $v);
				$v = str_replace('<<mysql_password>>', $_SESSION['mysql-password'], $v);
				$v = str_replace('<<mysql_database>>', $_SESSION['mysql-database-name'], $v);
				echo 'Writing file; ';
				$success = file_put_contents('../classes/LocalSettings.php', $v);
				if(!$success) {
					echo 'Writing failed; ';
?>
		</p>
		<p>
			Could not write the configuration file.
		</p>
<?php
					install_footer();
					exit;
				}
				else {
					echo 'Writing succeeded; ';
				}
			?>
		</p>
		<p>
			Jara has been installed!
		</p>
		<a href="finish.php" class="button">Finish</a>
<?php
	install_footer();
?>