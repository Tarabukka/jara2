<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/mysql.php - MySQL information frontend for installation
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('install_lib.php');
	install_header('MySQL information', 2);
?>
		<h2>MySQL information</h2>
		<p>
			Please fill out this information so Jara can connect to the database. If you are using a web host, you should be able to set up a database or find the details for one in your control panel.
		</p>
		<p>
			Please note that the MySQL user requires the CREATE TABLE, SELECT, INSERT, UPDATE and DELETE permissions.
		</p>
		<form action="user.php" method="post" id="install-form">
			<p>
				<label for="mysql-hostname">MySQL Hostname</label>
				<input type="text" name="mysql-hostname" value="localhost" />
			</p>
			<p>
				<label for="mysql-username">MySQL Username</label>
				<input type="text" name="mysql-username" />
			</p>
			<p>
				<label for="mysql-password">MySQL Password</label>
				<input type="password" name="mysql-password" />
			</p>
			<p>
				<label for="mysql-database">MySQL Database Name</label>
				<input type="text" name="mysql-database-name" />
			</p>
			<p>
				<label for="mysql-table-prefix">Table Prefix</label>
				<input type="text" name="mysql-table-prefix" value="jara2_" />
			</p>
			<a href="javascript:;" onclick="document.getElementById('install-form').submit();" class="button">Next</a>
		</form>
<?php
	install_footer();
?>