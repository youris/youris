<!-- File: /app/views/posts/index.ctp -->
<h1>Blog posts</h1>
<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
                <th>Actions</th>
		<th>Created</th>
	</tr>

	<!-- Here is where we loop through our $posts array, printing out post info -->
        <!-- la variabile posts l'ho settata nel controller Post -->
	<?php foreach ($posts as $post): ?>
	<tr>
		<td><?php echo $post['Post']['id']; ?></td>
		<td>
                <?php
                // link al controller posts, con azione view e id = all'id del post
                echo $html->link($post['Post']['title'],
array('controller' => 'posts', 'action' => 'view', $post['Post']['id'])); ?>
		</td>
                <td>
                    <!-- uses the HtmlHelper to prompt the user with a JavaScript
                         confirmation dialog before they attempt to delete a post. -->
                    <?php echo $html->link('Edit', array('action' => 'edit', $post['Post']['id']))?>
                    <?php echo $html->link('Delete', array('action' => 'delete', $post['Post']['id']), null, 'Are you sure?' )?>

                </td>
		<td><?php echo $post['Post']['created']; ?></td>
	</tr>
	<?php endforeach; ?>

</table>
<?php echo $html->link('Add Post',array('controller' => 'posts', 'action' => 'add'))?>