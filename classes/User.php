<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/User.php - User management
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class UserNotFoundException extends Exception { public $userid; }
	
	class User {
		public static function StartSession() {
			if(!isset($_SESSION) && !ini_get('session.auto_start')) {
				session_start();
			}
		}
		
		public static function DisplayInfo($user, $total_posts) {
			echo HTML::ParseTemplate('user', array(
				'user.username' => $user['username'],
				'user.real_name' => $user['real_name'],
				'user.userid' => $user['userid'],
				'user.url' => User::GetURL($user['userid']),
				'user.bio' => $user['bio'],
				'user.location' => $user['location'],
				'user.total_posts' => $total_posts
			));
		}
		
		public static function GetAllUsers($start = 0, $limit = 50) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('users') . '`');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('users') . '` ORDER BY `userid` LIMIT ' . $start . ',' . $limit);
			
			return DB::GetAllRows($result);
		}
		
		public static function Info($field) {
			self::StartSession();
			return $_SESSION['jara_user'][$field];
		}
		
		public static function GetURL($userid, $page = 1) {
			return (Settings::Get('blog_url') . '/user.php?id=' . $userid . '&page=' . $page);
		}
		
		public static function LoggedIn() {
			self::StartSession();
			return isset($_SESSION['jara_user']);
		}
		
		public static function Login($username, $password) {
			$result = DB::Query('SELECT * FROM `' . fn('users') . '` WHERE `username` = \'' . DB::Escape($username) . '\' AND `password` = SHA1(\'' . DB::Escape($password) . '\') LIMIT 1');
			
			if($result->num_rows == 0) {
				return false;
			}
			else {
				self::StartSession();
				$_SESSION['jara_user'] = $result->fetch_assoc();
				return true;
			}
		}
		
		public static function Logout() {
			self::StartSession();
			unset($_SESSION['jara_user']);
		}
		
		public static function GetInfoById($userid) {
			$result = DB::Query('SELECT * FROM `' . fn('users') . '` WHERE `userid` = \'' . DB::Escape($userid) . '\' LIMIT 1');
			
			if($result->num_rows == 0) {
				$ex = new UserNotFoundException();
				$ex->userid = $userid;
				throw $ex;
			}
			else {
				return $result->fetch_assoc();
			}
		}
		
		public static function GetInfoByUsername($username) {
			$result = DB::Query('SELECT * FROM `' . fn('users') . '` WHERE `username` = \'' . DB::Escape($username) . '\' LIMIT 1');
			
			if($result->num_rows == 0) {
				return false;
			}
			else {
				return $result->fetch_assoc();
			}
		}
		
		public static function Permission($permission) {
			self::StartSession();
			return ((self::LoggedIn()) ? ($_SESSION['jara_user'][$permission . '_permission'] == 1) : false);
		}
		
		public static function Create($username, $password, $email, $real_name, $permissions) {
			$permissions_k = array_keys($permissions);
			foreach($permissions_k as $k => $v) {
				$permissions_k[$k] = '`' . $v . '_permission`';
			}
			foreach($permissions as $k => $v) {
				$permissions[$k] = '\'' . ($v ? 1 : 0) . '\'';
			}
			$perms_names = implode(', ', $permissions_k);
			$perms = implode(', ', $permissions);
			$result = DB::Query('INSERT INTO `' . fn('users') . '` (`username`, `password`, `email`, `real_name`, '.$perms_names.') VALUES (\'' . DB::Escape($username) . '\', SHA1(\'' . DB::Escape($password) . '\'), \'' . DB::Escape($email) . '\', \'' . DB::Escape($real_name) . '\', '.$perms.')');
			
			return $result;
		}
		
		public static function UpdateProfile($email, $password, $real_name, $bio, $location) {
			return DB::Query('UPDATE `' . fn('users') . '` SET `email` = \'' . DB::Escape($email) . '\', ' . (!empty($password) ? '`password` = SHA1(\'' . DB::Escape($password) . '\'), ' : '') . ' `real_name` = \'' . DB::Escape($real_name) . '\', `bio` = \'' . DB::Escape($bio) . '\', location = \'' . DB::Escape($location) . '\' WHERE `userid` = \'' . self::Info('userid') . '\' LIMIT 1');
		}
		
		public static function Refresh() {
			if(self::LoggedIn()) {
				$result = DB::Query('SELECT * FROM `' . fn('users') . '` WHERE `userid` = \'' . self::Info('userid') . '\' LIMIT 1');
				
				$_SESSION['jara_user'] = $result->fetch_assoc();
			}
		}
		
		public static function Delete($userid) {
			$result = DB::Query('DELETE FROM `' . fn('users') . '` WHERE `userid` = \'' . DB::Escape($userid) . '\' LIMIT 1');
			
			return $result;
		}
		
		public static function Modify($field, $new_value, $userid = 0) {
			self::StartSession();
			if($userid == 0) {
				$userid = User::Info('userid');
			}
			return DB::Query('UPDATE `' . fn('users') . '` SET `' . $field . '` = \'' . DB::Escape($new_value) . '\' WHERE `userid` = \'' . DB::Escape($userid) . '\' LIMIT 1');
		}
	}
?>