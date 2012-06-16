<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/DB.php - Database management
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class DBConnectionException extends Exception { }
	class DBQueryException extends Exception { public $query; }
	
	class DB {
		private static $conn;
		
		public static function Get() {
			if(self::$conn == null) {
				self::$conn = new mysqli(Settings::Get('mysql_hostname'), Settings::Get('mysql_username'), Settings::Get('mysql_password'), Settings::Get('mysql_database'));
				if(mysqli_connect_errno()) {
					throw new DBConnectionException(mysqli_connect_error(), mysqli_connect_errno());
				}
				return self::$conn;
			}
			else {
				return self::$conn;
			}
		}
		
		public static function Query($query) {
			$db = self::Get();
			
			$start = microtime(true);
			$result = $db->query($query);
			$taken = (microtime(true) - $start);
			
			if($db->errno) {
				$v = new DBQueryException(self::Get()->error, self::Get()->errno);
				$v->query = $query;
				throw $v;
			}
			
			return $result;
		}
		
		public static function GetColumn($query, $column = 'COUNT(*)') {
			$result = self::Query($query);
			$row = $result->fetch_assoc();
			return $row[$column];
		}
		
		public static function Escape($value) {
			return ((get_magic_quotes_gpc()) ? $value : ((self::$conn) ? self::$conn->real_escape_string($value) : addslashes($value)));
		}
		
		public static function EscapeLike($value) {
			$value = str_replace('%', '\%', $value);
			$value = str_replace('_', '\_', $value);
			return $value;
		}
		
		public static function Unescape($value) {
			return ((get_magic_quotes_gpc()) ? stripslashes($value) : $value);
		}
		
		public static function UnescapeLike($value) {
			$value = str_replace('\%', '%', $value);
			$value = str_replace('\_', '_', $value);
			return $value;
		}
		
		public static function GetAllRows($result) {
			$rows = array();
			
			for($i = 0; $i < $result->num_rows; $i++) {
				$rows[]= $result->fetch_assoc();
			}
			
			return $rows;
		}
	}
	
	function fn($table_name) {
		return Settings::Get("table_prefix") . $table_name;
	}
?>