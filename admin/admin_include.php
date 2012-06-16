<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/admin_include.php - Administrative library functions and constants
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('../classes/All.php');
	if(!User::LoggedIn()) {
		HTML::ErrorPage('Not logged in', 'You must be logged in to access the administration area.');
	}
	if(!User::Permission('admin')) {
		HTML::ErrorPage('Not an administrator', 'You must be an administrator to access the administration area.');
	}
	define('YUI_EDITOR_FILES', '<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/assets/skins/sam/skin.css" /><script src="http://yui.yahooapis.com/2.8.1/build/yahoo-dom-event/yahoo-dom-event.js"></script><script src="http://yui.yahooapis.com/2.8.1/build/element/element-min.js"></script><script src="http://yui.yahooapis.com/2.8.1/build/container/container_core-min.js"></script><script src="http://yui.yahooapis.com/2.8.1/build/editor/simpleeditor-min.js"></script>');
	
	define('YUI_EDITOR_EXTRA_BODY', ' class="yui-skin-sam"');
	
	function yui_editor_script($box) {
		return '<script type="text/javascript">window.'.$box.'_editor=new YAHOO.widget.SimpleEditor("'.$box.'",{height:"400px",width:"600px",dompath:false,insert:true});'.$box.'_editor.render();document.getElementById("editor-form").onsubmit=function(){window.'.$box.'_editor.saveHTML();}</script>';
	}
?>