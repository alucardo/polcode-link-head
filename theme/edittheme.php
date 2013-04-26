<div class="polcode_link_head">
	<h1>Edit theme</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_theme"> << back to theme list </a></p>
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_theme_edit&id=<?php echo $theme[0]->id; ?>" method="post">
		<p>Theme name:</p>
		<input type="text" name="nametheme" value="<?php echo $theme[0]->name; ?>"><br>
		<p>Theme content:</p>
		<textarea name="des"><?php echo $theme[0]->des; ?></textarea><br>
		<input type="submit" value="save">
	</form>
</div>