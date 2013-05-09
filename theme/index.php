<div class="polcode_link_head">
	<h1>Polcode Pretty Links</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_add">Add link</a></p>

	<h2>Htaccess entries </h2>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>redirect</th>
				<th>link</th>
				<th>to</th>
				<th>Delete</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->entries  as $key => $entrie) { 

				$tab = explode(' ', $entrie);

			?>
			<tr>
				<td><?php echo $key; ?></td>
				<td><?php echo $tab[1]; ?></td>
				<td><?php echo $tab[2]; ?></td>
				<td><?php echo $tab[3]; ?></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_delete&id=<?php echo $key ?>">Delete</a></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_edit&id=<?php echo $key ?>">Edit</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table> 

	<h2>Databes entries </h2>


</div>