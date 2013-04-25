<div class="polcode_link_head">
	<h1>Add pretty link</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head">  <<  back to link list </a></p>
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_add" method="post">
		<p>Redirect code:</p>
		<select name="code">
			<option value="301">301</option>
			<option value="307">307</option>
		</select><br>
		<p>Pretty Link:</p>
		<textarea name="link" class="short"></textarea><br>
		<p>Link to:</p>
		<textarea name="to"  class="short"></textarea><br>
		<input type="submit" value="save">
	</form>
</div>