<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/Post.php - Post management
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class PostNotFoundException extends Exception { public $postid; }
	
	class Post {
		public static function Display($arg, $single = true) {
			if(is_array($arg)) {
				if($single) {
					echo HTML::ParseTemplate('posts_start');
				}
				
				$arguments = array(
					'post.title' => $arg['title'],
					'post.content' => $arg['content'],
					'post.date' => date('jS F Y', $arg['time']),
					'post.time' => date('g:ia', $arg['time']),
					'post.url' => Post::GetURL($arg['postid'])
				);
				
				$user = false;
				try {
					$user = User::GetInfoById($arg['userid']);
				}
				catch(UserNotFoundException $ex) {
					$user = array(
						'userid' => 0,
						'username' => '',
						'real_name' => 'Deleted author',
						'location' => '',
						'bio' => '',
						'url' => User::GetURL(0)
					);
				}
				$arguments['user.userid'] = $user['userid'];
				$arguments['user.username'] = $user['username'];
				$arguments['user.real_name'] = $user['real_name'];
				$arguments['user.location'] = $user['location'];
				$arguments['user.bio'] = $user['bio'];
				$arguments['user.url'] = User::GetURL($user['userid']);
				
				try {
					$category = Category::Get($arg['categoryid']);
				}
				catch(CategoryNotFoundException $ex) {
					$category = array(
						'categoryid' => 0,
						'title' => 'Unknown category',
						'description' => ''
					);
				}
				
				$arguments['category.categoryid'] = $category['categoryid'];
				$arguments['category.title'] = $category['title'];
				$arguments['category.description'] = $category['description'];
				$arguments['category.url'] = Category::GetURL($category['categoryid']);
				
				$comments_count = Comment::GetCount($arg['postid']);
				$arguments['comments.count'] = $comments_count;
				$arguments['comments.lang'] = $comments_count . ' ' . Utilities::DeterminePlural('comment', $comments_count);
				
				echo HTML::ParseTemplate('post', $arguments);
				
				if($single) {
					echo HTML::ParseTemplate('posts_end');
				}
			}
			else {
				self::Display(Post::Get($arg));
			}
		}
		
		public static function GetURL($postid) {
			return (Settings::Get('blog_url') . '/post.php?id=' . $postid);
		}
		
		public static function DisplaySet($set, $no_posts_message = 'There are no posts to display.') {
			if(count($set) == 0) {
?>
	<p>
		<?php echo $no_posts_message; ?>
	</p>
<?php
				return;
			}
			echo HTML::ParseTemplate('posts_start');
			
			foreach($set as $post) {
				self::Display($post, false);
			}
			
			echo HTML::ParseTemplate('posts_end');
		}
		
		public static function Modify($postid, $title, $content, $categoryid, $comments_enabled) {
			$result = DB::Query('UPDATE `' . fn('posts') . '` SET `title` = \'' . DB::Escape($title) . '\', `content` = \'' . DB::Escape($content) . '\', `categoryid` = \'' . DB::Escape($categoryid) . '\', `comments_enabled` = \'' . ($comments_enabled ? '1' : '0') . '\' WHERE `postid` = \'' . DB::Escape($postid) . '\' LIMIT 1');
			
			return $result;
		}
		
		public static function Write($title, $content, $categoryid, $comments_enabled) {
			$result = DB::Query('INSERT INTO `' . fn('posts') . '` (`userid`, `title`, `content`, `categoryid`, `time`, `comments_enabled`) VALUES(\'' . User::Info('userid') . '\', \'' . DB::Escape($title) . '\', \'' . DB::Escape($content) . '\', \'' . DB::Escape($categoryid) . '\', \'' . time() . '\', \'' . ($comments_enabled ? '1' : '0') . '\')');
			
			return $result;
		}
		
		public static function Delete($postid) {
			$result = DB::Query('DELETE FROM `' . fn('posts') . '` WHERE `postid` = \'' . DB::Escape($postid) . '\' LIMIT 1');
			
			return $result;
		}
		
		public static function GetAllPosts($start = 0, $count = 10) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('posts') . '`');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('posts') . '` ORDER BY `time` DESC LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
		
		public static function Get($postid) {
			$result = DB::Query('SELECT * FROM `' . fn('posts') . '` WHERE `postid` = \'' . DB::Escape($postid) . '\' LIMIT 1');
			
			if($result->num_rows == 0) {
				$ex = new PostNotFoundException();
				$ex->postid = $postid;
				throw $ex;
			}
			
			return $result->fetch_assoc();
		}
		
		public static function GetPostsByUser($userid, $start = 0, $count = 10) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('posts') . '` WHERE `userid` = \'' . DB::Escape($userid) . '\'');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('posts') . '` WHERE `userid` = \'' . $userid . '\' LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
		
		public static function GetPostsByCategory($categoryid, $start = 0, $count = 10) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('posts') . '` WHERE `categoryid` = \'' . DB::Escape($categoryid) . '\'');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('posts') . '` WHERE `categoryid` = \'' .  DB::Escape($categoryid) . '\' LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
		
		public static function GetRecentPosts($start = 0, $count = 10) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('posts') . '`');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('posts') . '` ORDER BY `time` DESC LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
		
		public static function SearchPosts($term, $start = 0, $count = 10) {
			$term = DB::Escape($term);
			$term = DB::EscapeLike($term);
			
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) `' . fn('posts') . '` WHERE `title` LIKE \'%' . $term . '%\' OR `content` LIKE \'%' . $term . '%\'');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('posts') . '` WHERE `title` LIKE \'%' . $term . '%\' OR `content` LIKE \'%' . $term . '%\' ORDER BY `time` DESC LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
	}
?>