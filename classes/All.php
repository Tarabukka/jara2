<?php
	/*
	 * Jara v2.1, the lightweight PHP/MySQL blogging platform.
	 * 
	 * classes/All.php
	 * Loads all classes.
	 *
	 */

	/*
	 * Copyright 2012 Tarabukka.

	 * Licensed under the Apache License, Version 2.0 (the "License");
	 * you may not use this file except in compliance with the License.
	 * You may obtain a copy of the License at
	 *
	 * http://www.apache.org/licenses/LICENSE-2.0

	 * Unless required by applicable law or agreed to in writing, software
	 * distributed under the License is distributed on an "AS IS" BASIS,
	 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	 * See the License for the specific language governing permissions and
	 * limitations under the License.
	 *
	*/

	// Define where we should load all our classes from for easy use below.
	// This value is the absolute path of the current directory.
	define('CLASSES_PATH', str_replace('\\', '/', dirname(__FILE__)));
	
	require_once(CLASSES_PATH . '/Settings.php');
	Settings::LoadFromFile('LocalSettings.php');

	// If we aren't installed yet, we don't want to try and do anything below.
	if(!Settings::Installed()) {
		header('Location: install');
		exit;
	}

	require_once(CLASSES_PATH . '/DB.php');
	Settings::LoadFromDatabase();

	require_once(CLASSES_PATH . '/User.php');
	User::Refresh();

	// Load other modules - the order of these doesn't matter.
	require_once(CLASSES_PATH . '/HTML.php');
	require_once(CLASSES_PATH . '/Post.php');
	require_once(CLASSES_PATH . '/Comment.php');
	require_once(CLASSES_PATH . '/Category.php');
	require_once(CLASSES_PATH . '/Utilities.php');

	// TODO: Replace system check with "lazy" error handling.
	$check = Utilities::SystemCheck();

	if($check == Utilities::SYSTEM_CHECK_DB_CONNECT_FAILED) {
		echo '<html><head><title>Jara: Base Error</title><style type="text/css">html{background:#eee;font:12px Helvetica,Arial,sans-serif}body{width:760px;margin:0 auto;background:#fff;padding:10px}</style></head><body><h1>Jara: Base Error</h1><p>There was an error connecting to the database.</p><blockquote>' . $ex->getMessage() . ' [MySQL error code ' . $ex->getCode() . ']</blockquote><p>Please try again in a few moments. This may be a configuration issue.</p></body></html>';
		exit;
	}
?>