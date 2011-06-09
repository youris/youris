<?php

class Error extends AppModel {
	
	var $name = 'Error';
	
	var $belongsTo = 'User';	// one to many association with users table
	
}

?>