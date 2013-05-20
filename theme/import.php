<div class="polcode_link_head">
	<h1>Import links</h1>
	<a href="#" id="selall">Select All</a> |
	<a href="#" id="deall">Deselect All</a> |
	<a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_import_all">Import All</a> 
	<form action="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_import_single" method="post">
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>

			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) { ?>
			<tr>				
				<td><?php echo $row->id; ?></td>
				<td><?php echo $row->name; ?></td>
				<td><input type="checkbox" name="item-<?php echo $row->id; ?>" value="<?php echo $row->id; ?>"></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="submit" value="Import" />
	</form> 
	
</div>