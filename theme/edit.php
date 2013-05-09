<div class="polcode_link_head">
	<h1>Edit link</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head">  <<  back to link list </a></p>
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_edit&id=<?php echo $_GET['id'] ?>&db=<?php echo $_GET['db'] ?>" method="post">
		<p>Htaccess entrie: </p>
		<textarea name="editlink" class="short"><?php echo $tresc; ?></textarea><br>

		<p>Link to:</p>
		<input type="text" name="link" value="<?php echo $li; ?>">
		<p>Theme:</p>
		<select name="them">
			<?php foreach ($themes as $theme) { ?>
			<option value="<?php echo $theme->id; ?>" <?php 

			if($theme->id == $th) {
				echo 'selected';
			}

			?> ><?php echo $theme->name; ?></option>
				
			<?php } ?>
		</select>
		<p>Robots text:</p>
		<input type="text" name="aft" value="<?php echo $af; ?>">
		<input type="submit" value="save">
	</form>
</div>