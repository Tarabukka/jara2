<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/Utilities.php - Utility functions
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class Utilities {
		const SYSTEM_CHECK_DB_CONNECT_FAILED = 1;
		const SYSTEM_CHECK_SUCCESS = 0;

		public static function RealPrefix() {
			return str_replace('\\', '/', dirname(__FILE__));
		}
		
		public static function SystemCheck() {
			try {
				DB::Get();
			}
			catch(DBConnectionException $ex) {
				return self::SYSTEM_CHECK_DB_CONNECT_FAILED;
			}

			return self::SYSTEM_CHECK_SUCCESS;
		}
		
		public static function DeterminePlural($word, $number) {
			if($number != 1) {
				return self::Plural($word);
			}
			
			return $word;
		}
		
		public static function Plural($word) {
			switch($word) {
				case 'category':
					return 'categories';
				default:
					return ($word . 's');
			}
		}
		
		public static function GetAction($default = 'view') {
			return ((isset($_REQUEST['action'])) ? $_REQUEST['action'] : $default);
		}
	}
?>