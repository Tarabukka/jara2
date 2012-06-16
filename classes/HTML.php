<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/HTML.php - Templating and output control
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class HTML {
		private static $headerShown = false;
		private static $footerShown = false;
		
		public static function IsHeaderShown() {
			return self::$headerShown;
		}
		
		public static function IsFooterShown() {
			return self::$footerShown;
		}
		
		public static function ParseTemplate($template_name, $vars = array(), $conds = array()) {
			$template = file_get_contents(Utilities::RealPrefix() . '/../templates/' . Settings::Get('template') . '/'.$template_name.'.tpl');

			$vars['blog_url'] = Settings::Get('blog_url');
			$vars['blog_title'] = Settings::Get('blog_title');
			$vars['template'] = Settings::Get('template');
			$vars['menu'] = self::Menu();
			$vars['categories'] = self::Categories();
			$vars['categories.with_count'] = self::Categories(true);
			
			$conds['logged_in'] = User::LoggedIn();
			
			if(User::LoggedIn()) {
				$vars['current_user.userid'] = User::Info('userid');
				$vars['current_user.username'] = User::Info('username');
				$vars['current_user.real_name'] = User::Info('real_name');
				$vars['current_user.location'] = User::Info('location');
				$vars['current_user.bio'] = User::Info('bio');
			}
			
			$template = preg_replace('/\/\*.+?\*\//is', '', $template);
			
			foreach($vars as $name => $value) {
				$template = str_replace('{' . $name. '}', $value, $template);
			}
			
			$settings = Settings::GetAll();
			
			foreach($settings as $name => $value) {
				$template = str_replace('{settings.' . $name .'}', $value, $template);
			}
			
			foreach($conds as $name => $value) {
				if(!$value) {
					$template = preg_replace('/{if\:' . preg_quote($name, '/') . '}.+?{\/if}/is', '', $template);
				}
				else {
					$template = preg_replace('/{if\:' . preg_quote($name, '/') . '}(.+?){\/if}/is', '$1', $template);
				}
			}
			
			foreach($conds as $name => $value) {
				if($value) {
					$template = preg_replace('/{ifnot\:' . preg_quote($name, '/') . '}.+?{\/ifnot}/is', '', $template);
				}
				else {
					$template = preg_replace('/{ifnot\:' . preg_quote($name, '/') . '}(.+?){\/ifnot}/is', '$1', $template);
				}
			}
			
			foreach($settings as $name => $value) {
				if($value === 0 || $value === '0') {
					$template = preg_replace('/{if\:settings\.' . preg_quote($name, '/') . '}.+?{\/if}/is', '', $template);
				}
				else if($value === 1 || $value === '1') {
					$template = preg_replace('/{if\:settings\.' . preg_quote($name, '/') . '}(.+?){\/if}/is', '$1', $template);
				}
			}
			
			foreach($settings as $name => $value) {
				if($value === 1 || $value === '1') {
					$template = preg_replace('/{ifnot\:settings\.' . preg_quote($name, '/') . '}.+?{\/ifnot}/is', '', $template);
				}
				else if($value === 0 || $value === '0') {
					$template = preg_replace('/{ifnot\:settings\.' . preg_quote($name, '/') . '}(.+?){\/ifnot}/is', '$1', $template);
				}
			}
			
			return trim($template);
		}
		
		public static function Menu() {
			$return = '<li><a href="' . Settings::Get('blog_url') . '">Home</a></li><li><a href="' . Settings::Get('blog_url') . '/search.php">Search</a></li>';
			if(!User::LoggedIn()) {
				$return .= '<li><a href="' . Settings::Get('blog_url') . '/login.php">Login</a></li>';
			}
			else {
				if(User::Permission('admin')) {
					$return .= '<li><a href="' . Settings::Get('blog_url') . '/admin/index.php">Admin</a></li>';
				}
				$return .= '<li><a href="' . Settings::Get('blog_url') . '/logout.php">Logout</a></li>';
			}
			return $return;
		}
		
		public static function Categories($count = false) {
			$return = '';
			$categories = Category::GetAllCategories();
			foreach($categories as $category) {
				$return .= '<li><a href="' . Category::GetURL($category['categoryid']) . '">' . $category['title'] . '</a>';
				if($count) {
					$num_posts = Post::GetPostsByCategory($category['categoryid'], -1);
					$return .= ' (' . $num_posts . ')';
				}
				$return .= '</li>';
			}
			return $return;
		}
		
		public static function Header($page_title, $extra_header = '', $extra_body = '') {
			self::$headerShown = true;
			echo self::ParseTemplate('header', array('page_title' => $page_title, 'extra_header' => $extra_header, 'extra_body' => $extra_body));
		}
		
		public static function Footer() {
			self::$footerShown = true;
			echo self::ParseTemplate('footer');
		}
		
		public static function Login($messages) {
			$vars = array(
				'login.url' => (Settings::Get('blog_url') . '/login.php')
			);
			
			$vars['login.messages'] = '';
			
			foreach($messages as $message) {
				$vars['login.messages'] .= $message . '<br />';
			}
			
			echo self::ParseTemplate('login', $vars);
		}
		
		public static function Search() {
			$vars = array(
				'search.url' => (Settings::Get('blog_url') . '/search.php')
			);
			
			echo self::ParseTemplate('search', $vars);
		}
		
		public static function ErrorPage($title, $message) {
			if(!self::IsHeaderShown()) {
				self::Header('Error: ' . $title);
			}
			
			echo '<h2>Error: ' . $title . '</h2>';
			
			echo '<p>' . $message . '</p>';
			
			if(!self::IsFooterShown()) {
				self::Footer();
			}
			
			exit;
		}
	}
?>