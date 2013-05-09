<div class="polcode_link_head">
	<h1>Polcode Pretty Links Statistic</h1>
	<table>
		<thead>
			<tr>
				<th>ID stat</th>
				<th>ID link</th>
				<th>Partner links</th>
				<th>Visits</th>
				<th>Last visit</th>
			</tr>
		</thead>
		<tbody>
			<?php

			 foreach ($links  as $val) { 



			 ?>
			<tr style="text-align: center;">
				<td ><?php echo $val->id; ?></td>
				<?php 
					$l = $this->getLinkInfo($val->link);
				?>
				<td><?php echo $l->id; ?></td>
				<td><?php echo $l->link; ?></td>
				<td><?php echo $val->visit; ?></td>
				<td><?php echo date("F j, Y, g:i a", $val->last); ?></td>			
			</tr>
			<?php } ?>
		</tbody>
	</table> 

</div>