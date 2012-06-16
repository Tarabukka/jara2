<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/install_lib.php - Installation utility functions
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	session_start();
	
	require_once('../classes/LocalSettings.php');
	
	if(!defined('jara2_not_installed') && (strpos($_SERVER["REQUEST_URI"], "/install/finish.php") === false)) {
		echo 'Invalid install target';
		exit;
	}
	
	function install_header($title, $on) {
		$items = array('Welcome', 'Prerequisites', 'MySQL information',  'Account information', 'Blog information', 'Install', 'Finish');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Jara 2 Installation - <?php echo $title ?></title>
	<style type="text/css">
		html {
			background: #eee;
			font: 14px "Segoe UI", "Lucida Grande", sans-serif;
		}
		body {
			background: #fff;
			margin: 0 auto;
			padding: 10px;
			width: 760px;
			border: 1px solid #ccc;
		}
		body h1 {
			margin: 0;
			padding: 0;
		}
		#left {
			width: 200px;
			float: left;
			margin-left: 10px;
		}
		#right {
			width: 500px;
			float: left;
			border-left: 1px solid #ccc;
			padding-left: 20px;
			overflow: auto;
		}
		#left ol {
			padding: 0;
			padding-left: 20px;
		}
		#left ol .on {
			font-weight: bold;
		}
		#footer {
			margin-top: 10px;
			border-top: 1px solid #ccc;
			padding-top: 10px;
			text-align: center;
		}
		.button {
			float: right;
			border: 1px solid #ccc;
			padding: 5px 10px;
			background: #eee;
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}
		.button:hover {
			text-decoration: underline;
		}
		input {
			border: 1px solid #ccc;
			padding: 5px 10px;
			background: #fff;
			font: 14px "Segoe UI", "Lucida Grande", sans-serif;
			width: 478px;
		}
		input:focus {
			border: 1px solid #000;
		}
		form label {
			font-weight: bold;
			display: block;
		}
	</style>
</head>
<body>
	<h1><img src="assets/logo.png" alt="Jara 2" /></h1>
	<div id="left">
		<ol>
			<?php
				foreach($items as $num => $item_title) {
			?>
				<li<?php if($num == $on) { ?> class="on"<?php } ?>><?php echo $item_title; ?></li>
			<?php
				}
			?>
		</ol>
	</div>
	<div id="right">
<?php
	}
	function install_footer() {
?>
	</div>
	<br style="clear: both" />
	<div id="footer">
		Copyright &copy; Xanti Software and Services 2009-2010.
	</div>
</body>
</html>
<?php
	}
?>