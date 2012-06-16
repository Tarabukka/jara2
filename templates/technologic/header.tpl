<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{page_title} / {blog_title}</title>
	<link href="{blog_url}/templates/{template}/styles/technologic.css" type="text/css" rel="stylesheet" media="screen" />
	{extra_header}
</head>
<body{extra_body}>
	<h1>{blog_title}</h1>
	
	<div id="content">
	
		<div id="sidebar">
			
			<h3>Menu</h3>
			
			{if:logged_in}
			<p>
				Logged in as <strong>{current_user.real_name}</strong>.
			</p>
			{/if}
			
			<ul id="menu">
				{menu}
			</ul>
			
			<h3>Categories</h3>
			
			<ul class="list">
				{categories.with_count}
			</ul>
			
		</div>
		
		<div id="main">