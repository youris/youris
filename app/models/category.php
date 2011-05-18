<?php

class Category extends AppModel {
	
	var $name = 'Category';
	var $useTable = 'categories'; 	// database table name
	var $belongsTo = 'User';
	
	/* virtual field complete_name -> concatenation of user first name 
	 * and user last name
	 */
	var $virtualFields = array(    
		'complete_name' => "CONCAT(User.name, ' ', User.surname)"
	);
	
}

?>