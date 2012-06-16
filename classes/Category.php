<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/Category.php - Category management
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class CategoryNotFoundException extends Exception { public $categoryid; }
	
	class Category {
		public static function Get($categoryid) {
			$result = DB::Query('SELECT * FROM `' . fn('categories') . '` WHERE `categoryid` = \'' . DB::Escape($categoryid) . '\' LIMIT 1');
			
			if($result->num_rows == 0) {
				$ex = new CategoryNotFoundException();
				$ex->categoryid = $categoryid;
				throw $ex;
			}
			
			return $result->fetch_assoc();
		}
		
		public static function DisplayInfo($category, $total_posts) {
			echo HTML::ParseTemplate('category', array(
				'category.title' => $category['title'],
				'category.description' => $category['description'],
				'category.categoryid' => $category['categoryid'],
				'category.url' => Category::GetURL($category['categoryid']),
				'category.total_posts' => $total_posts
			));
		}
		
		public static function GetURL($categoryid, $page = 1) {
			return (Settings::Get('blog_url') . '/category.php?id=' . $categoryid . '&page=' . $page);
		}
		
		public static function Create($title, $description) {
			$result = DB::Query('INSERT INTO `' . fn('categories') . '` (`title`, `description`) VALUES(\'' . DB::Escape($title) . '\', \'' . DB::Escape($description) . '\')');
			
			return $result;
		}
		
		public static function Modify($categoryid, $title, $description) {
			$result = DB::Query('UPDATE `' . fn('categories') . '` SET `title` = \'' . DB::Escape($title) . '\', `description` = \'' . DB::Escape($description) . '\' WHERE `categoryid` = \'' . DB::Escape($categoryid) . '\' LIMIT 1');
			
			return $result;
		}
		
		public static function Delete($categoryid) {
			$result = DB::Query('DELETE FROM `' . fn('categories') . '` WHERE `categoryid` = \'' . DB::Escape($categoryid) . '\' LIMIT 1');
			
			return $result;
		}
		
		public static function GetAllCategories($start = 0, $count = 50) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('categories') . '`');
				$row = $result->fetch_assoc();
				return $row["COUNT(*)"];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('categories') . '` LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
	}
?>