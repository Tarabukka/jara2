<?php
	/*
		Jara, the open source, lightweight PHP and MySQL blogging platform, v2.0-RELEASE
		admin/categories.php - Interface for managing categories
		
		Copyright (C) Xanti Software and Services 2009-2010.
		See _readme.txt for licensing information.
	*/
	
	require_once('admin_include.php');
	$action = Utilities::GetAction();
	
	if(!User::Permission('categories')) {
		HTML::ErrorPage('Incorrect permissions', 'You do not have the permission to view this area.');
	}
	
	if($action == 'view') {
		HTML::Header('Categories');
		
		$page = ((isset($_GET['page'])) ? $_GET['page'] : 1);
		
		$total_categories = Category::GetAllCategories(-1);
		
		if($total_categories == 0) {
			$total_categories = 1;
		}
		
		$total_pages = ceil($total_categories / 50);
		
		if($page > $total_pages) {
			$page = $total_pages;
		}
		if($page < 0) {
			$page = 0;
		}
		
		$offset = ($page - 1) * 50;
		
		$categories = Category::GetAllCategories($offset, 50);
?>
<h2>Categories</h2>
<?php
	switch($_REQUEST['msg']) {
		case 'category_saved':
?>
<div class="admin-info-msg">
	The category was saved.
</div>
<?php
			break;
		case 'category_deleted':
?>
<div class="admin-info-msg">
	The category was deleted.
</div>
<?php
			break;
		default:
			break;
	}
?>
<ul class="admin-sections">
	<li>
		<h3><a href="categories.php?action=add"><img src="icons/category_add.png" /> Add a Category</a></h3>
	</li>
</ul>
<br />
<ul class="admin-list">
<?php
		foreach($categories as $category) {
?>
	<li>
		<h3><?php echo $category['title']; ?></h3>
		<span>
			<a href="../category.php?id=<?php echo $category['categoryid']; ?>" title="View category"><img src="icons/view.png" alt="View category" border="0" /></a>
			<?php if($category['title'] != 'Uncategorized') { ?>
			<a href="categories.php?action=edit&categoryid=<?php echo $category['categoryid']; ?>" title="Edit category"><img src="icons/edit.png" alt="Edit category" border="0" /></a>
			<a href="javascript:;" onclick="var c = confirm('Are you sure you want to delete this category?'); if(c) { window.location.href='categories.php?action=delete&categoryid=<?php echo $category['categoryid']; ?>&n=<?php echo substr(User::Info('password'), 16, 10); ?>';}" title="Delete category"><img src="icons/delete.png" alt="Delete category" border="0" /></a><?php } ?>
		</span>
		<h4><?php echo $category['description']; ?></h4>
	</li>
<?php
		}
?>
</ul>
<br />
<?php
		if(count($categories) == 0) {
?>
<p>
	There are no categories on this blog. Why not <a href="categories.php?action=add">add one</a>?
</p>
<?php
		}
		$vars = array(
			'older_link' => 'categories.php?page=' . ($page + 1),
			'newer_link' => 'categories.php?page=' . ($page - 1)
		);
		$conds = array(
			'older' => ($page != $total_pages),
			'newer' => ($page != 1)
		);
		
		echo HTML::ParseTemplate('pagination', $vars, $conds);
		HTML::Footer();
	}
	else if($action == 'add') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$title = htmlentities($_POST['title']);
			$description = htmlentities($_POST['description']);
			if(empty($title) || empty($description)) {
				HTML::ErrorPage('You need to fill out all the fields', 'You need to fill out all the fields.');
			}
			if($title == 'Uncategorized') {
				HTML::ErrorPage('Invalid title', 'You cannot make a category named Uncategorized.');
			}
			$result = Category::Create($title, $description);
			if(!$result) {
				HTML::ErrorPage('Category not created', 'The category could not be created.');
			}
			header('Location: categories.php?msg=category_saved');
			exit;
		}
		else {
			HTML::Header('Add Category');
?>
<h2>Add Category</h2>
<form action="categories.php" method="post" class="nice-form">
	<input type="hidden" name="action" value="add" />
	<ul>
		<li>
			<label for="title">Title</label>
			<input type="text" name="title" />
		</li>
		<li>
			<label for="description">Description</label>
			<textarea name="description"></textarea>
		</li>
	</ul>
	<input type="submit" value="Add" />
</form>
<?php
			HTML::Footer();
		}
	}
	else if($action == 'edit') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$categoryid = $_POST['categoryid'];
			$category = false;
			try {
				$category = Category::Get($categoryid);
			}
			catch(CategoryNotFoundException $ex) {
				HTML::ErrorPage('Category does not exist', 'The category you requested does not exist.');
			}
			$title = htmlentities($_POST['title']);
			$description = htmlentities($_POST['description']);
			$result = Category::Modify($_POST['categoryid'], $title, $description);
			if(!$result) {
				HTML::ErrorPage('Category not saved', 'The category could not be saved.');
			}
			header('Location: categories.php?msg=category_saved');
			exit;
		}
		else {
			$categoryid = $_REQUEST['categoryid'];
			$category = false;
			try {
				$category = Category::Get($categoryid);
			}
			catch(CategoryNotFoundException $ex) {
				HTML::ErrorPage('Category does not exist', 'The category you requested does not exist.');
			}
			HTML::Header('Edit Category');
?>
<h2>Edit Category</h2>
<form action="categories.php" method="post" class="nice-form">
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="categoryid" value="<?php echo $categoryid; ?>" />
	<ul>
		<li>
			<label for="title">Title</label>
			<input type="text" name="title" value="<?php echo $category['title']; ?>" />
		</li>
		<li>
			<label for="description">Description</label>
			<textarea name="description"><?php echo $category['description']; ?></textarea>
		</li>
	</ul>
	<input type="submit" value="Save" />
</form>
<?php
			HTML::Footer();
		}
	}
	else if($action == 'delete') {
		if($_REQUEST['n'] != substr(User::Info('password'), 16, 10)) {
			HTML::ErrorPage('Possible cross-site request forgery', 'A possible cross-site request forgery attempt was blocked.');
		}
		$result = Category::Delete($_REQUEST['categoryid']);
		if(!$result) {
			HTML::ErrorPage('Category not deleted', 'The category could not be deleted.');
		}
		header('Location: categories.php?msg=category_deleted');
		exit;
	}
?>