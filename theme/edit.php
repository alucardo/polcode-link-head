<div class="polcode_link_head">
	<h1>Edit link</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head">  <<  back to link list </a></p>
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_edit&id=<?php echo $_GET['id'] ?>" method="post">
		

		<p>Link to:</p>
		<input type="text" name="link" value="<?php echo $li; ?>">
		<p>Preety link:</p>
		<input type="text" name="linkfrom" value="<?php echo $lif; ?>">
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
		<p>With iframe:</p>
		<select name="ifr">
				<option value="0">YES</option>
				<option value="1" <?php 
					if($ifr==1){
						echo 'selected';
					}
				?>>NO</option>
		</select>
		<input type="submit" value="save">
	</form>
</div>