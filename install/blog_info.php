<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		install/blog_info.php - Installation frontend for blog information
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('install_lib.php');
	install_header('Blog information', 4);
	foreach($_POST as $k => $v) {
		if(!empty($k)) {
			$_SESSION[$k] = $v;
		}
	}
?>
		<h2>Blog information</h2>
		<p>
			This information describes your blog.
		</p>
		<form action="install.php" method="post" id="install-form">
			<p>
				<label for="blog-title">Blog Title</label>
				<input type="text" name="blog-title" />
			</p>
			<p>
				<label for="blog-url">Blog URL (just to check &mdash; no trailing slash)</label>
				<input type="text" name="blog-url" value="http://<?php echo $_SERVER["HTTP_HOST"] . str_replace("/install/blog_info.php", "", $_SERVER["REQUEST_URI"]); ?>" />
			</p>
			<p>
				Press the Install button to install Jara 2.
			</p>
			<a href="javascript:;" onclick="document.getElementById('install-form').submit();" class="button">Install</a>
		</form>
<?php
	install_footer();
?>