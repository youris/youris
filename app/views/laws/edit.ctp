<?php  
if(isset($javascript)) { 
	// adding TinyMCE
    echo $javascript->link('tiny_mce/tiny_mce.js');  
}
?> 
<script type="text/javascript"> 
    tinyMCE.init({ 
        theme : "simple", 
        mode : "textareas", 
        convert_urls : false 
    }); 
</script> 
<h1>Law editing</h1>

<?php
	echo $form->create('Law', array('action' => 'edit'));
	echo $form->input('law_number');
	echo $form->input('title');
	echo $form->input('note', array('rows' => '3'));
	echo $form->input('id', array('type'=>'hidden'));
	echo $form->end('Save Law');
?>
