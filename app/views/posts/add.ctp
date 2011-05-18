<!-- File: /app/views/posts/add.ctp -->

<h1>Add Post</h1>
<?php
/*
 * If create() is called with no parameters supplied, it assumes you are building a form
 * that submits to the current controller's add() action (or edit() action when id is
 * included in the form data), via POST.
 * The $form->input() method is used to create form elements of the same name.
 * The first parameter tells CakePHP which field they correspond to, and the second
 * parameter allows you to specify a wide array of options - in this case, the number
 * of rows for the textarea. There's a bit of introspection and automagic here: input()
 * will output different form elements based on the model field specified.
 */
echo $form->create('Post');
echo $form->input('title');
echo $form->input('body', array('rows' => '3'));
echo $form->end('Save Post');
?>