<div>
	<h1>Polcode Pretty Links</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_add">Add link</a></p>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>redirect</th>
				<th>link</th>
				<th>to</th>
				<th>Edit</th>
				<th>Show</th>
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
				<td><a href="">Edit</a></td>
				<td><a href="">Show</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table> 
</div>