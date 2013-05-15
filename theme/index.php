<div class="polcode_link_head">
	<h1>Polcode Pretty Links</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_add">Add link</a></p>

	<h2>Htaccess entries </h2>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Pretty link</th>
				<th>Link</th>
				<th>Link</th>
				<th>Link</th>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach ($dblinks  as $link) { ?>
			<tr>
				<td><?php echo $link->id; ?></td>
				<td>/red<?php echo $link->linkfrom; ?></td>
				<td><?php echo $link->link; ?></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_delete&id=<?php echo $link->id; ?>">Delete</a></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_edit&id=<?php echo $link->id; ?>">Edit</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table> 

	
</div>