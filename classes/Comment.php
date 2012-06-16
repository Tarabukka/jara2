<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		classes/Comment.php - Comment management
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	class Comment {
		public static function GetAllComments($start = 0, $count = 50) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('comments') . '`');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('comments') . '` ORDER BY `time` DESC LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
		
		public static function GetNewComments($start = 0, $count = 50) {
			if($start == -1) {
				$result = DB::Query('SELECT COUNT(*) FROM `' . fn('comments') . '` where `moderated` = \'0\'');
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			}
			
			$result = DB::Query('SELECT * FROM `' . fn('comments') . '` where `moderated` = \'0\' ORDER BY `time` DESC LIMIT ' . $start . ',' . $count);
			
			return DB::GetAllRows($result);
		}
		
		public static function DisplayForm($postid) {
			echo HTML::ParseTemplate('comment_form', array('comment_action' => (Settings::Get('blog_url') . '/comment.php'), 'postid' => $postid));
		}
		
		public static function Display($postid) {
			echo HTML::ParseTemplate('comments_start');
			
			$comments = self::Get($postid);
			foreach($comments as $comment) {
				$vars = array(
					'comment' => $comment['comment'],
					'comment.commentid' => $comment['commentid'],
					'comment.author' => $comment['author'],
					'comment.date' => date('jS F Y', $comment['time']),
					'comment.userid' => $comment['userid'],
					'comment.user_url' => (($comment['userid'] != 0) ? User::GetURL($comment['userid']) : '')
				);
				$conds = array(
					'comment.from_user' => $comment['userid'] != 0
				);
				echo HTML::ParseTemplate('comment', $vars, $conds);
			}
			
			echo HTML::ParseTemplate('comments_end');
		}
		
		public static function Delete($commentid) {
			$result = DB::Query('DELETE FROM `' . fn('comments') . '` WHERE `commentid` = \'' . DB::Escape($commentid) . '\' LIMIT 1');
			
			return $result;
		}
		
		public static function Approve($commentid) {
			$result = DB::Query('UPDATE `' . fn('comments') . '` SET `moderated` = \'1\' WHERE `commentid` = \'' . DB::Escape($commentid) . '\' LIMIT 1');
			
			return $result;
		}
		
		public static function Get($postid) {
			$result = DB::Query('SELECT * FROM `' . fn('comments') . '` WHERE `postid` = \'' . DB::Escape($postid) . '\' AND `moderated` = \'1\' ORDER BY `time`');
			
			return DB::GetAllRows($result);
		}
		
		public static function GetCount($postid) {
			$result = DB::Query('SELECT COUNT(*) FROM `' . fn('comments') . '` WHERE `postid` = \'' . DB::Escape($postid) . '\' AND `moderated` = \'1\' ORDER BY `time`');
			$row = $result->fetch_assoc();
			return $row['COUNT(*)'];
		}
		
		public static function Write($postid, $author, $email, $comment) {
			$result = DB::Query('INSERT INTO `' . fn('comments') . '` (`postid`, `author`, `ip`, `email`, `comment`, `userid`, `moderated`, `time`) VALUES(\'' . DB::Escape($postid) . '\', \'' . DB::Escape(htmlentities($author)) . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\', \'' . DB::Escape(htmlentities($email)) . '\', \'' . DB::Escape(htmlentities($comment)) . '\', \'' . (User::LoggedIn() ? User::Info('userid') : 0) . '\', \'0\', \'' . time() . '\')');
			
			return $result;
		}
	}
?>