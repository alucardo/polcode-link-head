<div class="polcode_link_head">
	<h1>Themes</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_theme_add">Add new header theme</a></p>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Delete</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows  as $key => $entrie) { ?>
			<tr>
				<td><?php echo $entrie->id; ?></td>
				<td><?php echo $entrie->name; ?></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_theme_delete&id=<?php echo $entrie->id; ?>">Delete</a></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_theme_edit&id=<?php echo $entrie->id; ?>">Edit</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table> 
</div>