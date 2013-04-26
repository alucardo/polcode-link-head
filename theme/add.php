<div class="polcode_link_head">
	<h1>Add pretty link</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head">  <<  back to link list </a></p>
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_add" method="post">
		<p>Redirect code:</p>
		<select name="code" id="headtyp">
			<option value="301">301</option>
			<option value="307">307</option>
			<option value="1">Header</option>
		</select><br>
		<p>Pretty Link:</p>
		<textarea name="link" class="short"></textarea><br>
		<div class="red">
			<p>Link to:</p>
			<textarea name="to"  class="short"></textarea><br>
		</div>
		<div class="he">
			<p>Theme:</p>
			<select name="the">
				<?php foreach ($themes as $theme) { ?>
				<option value="<?php echo $theme->id; ?>" ><?php echo $theme->name; ?></option>
					
				<?php } ?>
			</select>
			<p>Text for roobots without iframes:</p>
			<input type="text" name="rob">
		</div>
		<input type="submit" value="save">
	</form>
</div>