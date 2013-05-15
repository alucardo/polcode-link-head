<div class="polcode_link_head">
	<h1>Polcode Pretty Links Statistic</h1>
	<p><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_reset_all">Reset all statistic</a></p>
	<table>
		<thead>
			<tr>

				<th>Partner links</th>
				<th>Cliks</th>
				<th>Last visit</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php

			 foreach ($links  as $val) { 



			 ?>
			<tr style="text-align: center;">
				
				<?php 
					$l = $this->getLinkInfo($val->link);
				?>
				
				<td><?php echo $l->link; ?></td>
				<td><?php echo $val->visit; ?></td>
				<td><?php echo date("F j, Y, g:i a", $val->last); ?></td>
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_reset&id=<?php echo $val->id; ?>">Reset</a></td>			
				<td><a href="<?php echo get_admin_url(); ?>admin.php?page=polcode_link_head_stat_delete&id=<?php echo $val->id; ?>">Delete</a></td>			
			</tr>
			<?php } ?>
		</tbody>
	</table> 

</div>