<h3>Leave a Comment</h3>
<form action="{comment_action}" method="post" class="nice-form">
	<input type="hidden" name="postid" value="{postid}" />
	<p>
		{if:logged_in}Logged in as <strong>{current_user.real_name}</strong>.{/if} All fields are required. Comments will be manually moderated before being publicly shown.
	</p>
	<ul>
	{ifnot:logged_in}
		<li>
			<label for="author">Name</label>
			<input type="text" name="author" />
		</li>
		<li>
			<label for="email">Email</label>
			<input type="text" name="email" />
		</li>
	{/ifnot}
		<li>
			<label for="comment">Comment</label>
			<textarea name="comment"></textarea>
		</li>
	</ul>
	<input type="submit" value="Post" />
</form>