<div class="polcode_link_head">
	<h1>Add pretty link</h1>
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_add" method="post">
		<select name="code">
			<option value="301">301</option>
		</select>
		<input type="text" name="link" >
		<input type="text" name="to" >
		<input type="submit" value="save">
	</form>
</div>