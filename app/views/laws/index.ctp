<h1>Laws List</h1>

<table>
	<tr>
		<td>Title</td>
		<td>Insert date</td>
	</tr>
	<?php 
		//print_r($laws);
		foreach($laws as $id => $info) {?>
	<tr>
		<?php foreach($info as $title => $insert_date) {?>
		
		<td><?php echo $title // title ?></td>
		<td><?php echo $insert_date; // insert date?></td>
		
		<?php }?>
		<td>
			<?php 
				echo $html->link('View', array('action' => 'view', $id)).'  ';
				echo $html->link('Edit', array('action' => 'edit', $id)).'  '; 
				echo $html->link('Delete', array('action' => 'delete', $id)); 
			?>
		</td>
	</tr>
	
	<?php }?>
</table>
<?php echo $html->link('Add Law',array('controller' => 'laws', 'action' => 'add'))?>
