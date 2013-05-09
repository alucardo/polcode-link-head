<div class="polcode_link_head">
	<h1>Polcode Pretty Links</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_add">Add link</a></p>

	<h2>Htaccess entries </h2>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Redirect</th>
				<th>Link</th>
				<th>To</th>
				<th>Theme</th>
				<th>Robot text</th>
				<th>Delete</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->entries  as $key => $entrie) { 

				$tab = explode(' ', $entrie);
				$ir = $this -> getRedById($entrie);
				
				$ri = $this->getRed( (int)$ir );   

			?>
			<tr>
				<td><?php echo $key; ?></td>
				<td><?php echo $tab[1]; ?></td>
				<td><?php echo $tab[2]; ?></td>
				<td><?php echo $ri->link; ?></td>
				<td><?php echo $this->getThemeById($ri->theme)->name;  ?></td>
				<td><?php echo $ri->aft; ?></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_delete&id=<?php echo $key ?>">Delete</a></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_edit&id=<?php echo $key ?>&db=<?php echo $ir; ?>">Edit</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table> 

	
</div>