<div class="polcode_link_head">
	<h1>Edit link</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head">  <<  back to link list </a></p>
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_edit&id=<?php echo $_GET['id'] ?>" method="post">
		<textarea name="editlink" class="short"><?php echo $tresc; ?></textarea><br>
		<input type="submit" value="save">
	</form>
</div>