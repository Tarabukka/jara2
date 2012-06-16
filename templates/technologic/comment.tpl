<li>
	<a name="comment{comment.commentid}"></a>
	<h4>{if:comment.from_user}<a href="{comment.user_url}">{/if}{comment.author}{if:comment.from_user}</a>{/if}</h3>
	<h5>{comment.date}</h4>
	<p>
		{comment}
	</p>
</li>