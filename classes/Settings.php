<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/Settings.php - Settings management
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class Settings {
		private static $data = array();
		
		public static function LoadFromFile($filename) {
			require_once($filename);
		
			$const = get_defined_constants();
			
			foreach($const as $k => $v) {
				if(substr(strtolower($k), 0, 6) == 'jara2_') {
					self::$data[substr(strtolower($k), 6)] = $v;
				}
			}
		}
		
		public static function GetAll() {
			return self::$data;
		}
		
		public static function LoadFromDatabase() {
			$result = DB::Query('SELECT * FROM `' . fn('settings') . '`');
			
			for($i = 0; $i < $result->num_rows; $i++) {
				$row = $result->fetch_assoc();
				self::$data[$row['key']] = $row['value'];
			}
		}
		
		public static function GetFromDatabase() {
			$result = DB::Query('SELECT * FROM `' . fn('settings') . '`');
			
			return DB::GetAllRows($result);
		}
		
		public static function GetCountFromDatabase() {
			$result = DB::Query('SELECT COUNT(*) FROM `' . fn('settings') . '`');
			$row = $result->fetch_assoc();
			return $row['COUNT(*)'];
		}
		
		public static function Set($key) {
			return isset(self::$data[$key]);
		}
		
		public static function Get($key) {
			return self::$data[$key];
		}
		
		public static function GetKeyById($settingid) {
			$result = DB::Query('SELECT `key` FROM `' . fn('settings') . '` WHERE `settingid` = \'' . DB::Escape($settingid) . '\'');
			$row = $result->fetch_assoc();
			return $row['key'];
		}
		
		public static function Create($key, $value, $friendly_name, $is_bool) {
			return DB::Query('INSERT INTO `' . fn('settings') .'` (`key`, `value`, `friendly_name`, `is_bool`) VALUES(\'' . DB::Escape($key) . '\', \'' . DB::Escape($value) . '\', \'' . DB::Escape($friendly_name) . '\', \'' . DB::Escape($is_bool) . '\')');
		}
		
		public static function Modify($key, $value) {
			$result = DB::Query('UPDATE `' . fn('settings') . '` SET `value` = \'' . DB::Escape($value) . '\' WHERE `key` = \'' . DB::Escape($key) . '\' LIMIT 1');
		}
		
		public static function Delete($settingid) {
			$result = DB::Query('DELETE FROM `' . fn('settings') . '` WHERE `settingid` = \'' . DB::Escape($settingid) . '\' LIMIT 1');
			return $result;
		}
	}
?>