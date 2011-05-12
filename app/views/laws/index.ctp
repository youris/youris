<h1>Laws List</h1>

<table>
	<tr>
		<td>Title</td>
		<td>Insert date</td>
	</tr>
	<?php foreach($laws as $law) {?>
	<tr>
		<td><?php echo $law['Law']['title']?></td>
		<td><?php echo $law['Law']['insert_date']?></td>
	</tr>
	
	<?php }?>
</table>
