<?php

class Law extends AppModel {
	
	var $name = 'Law';
	var $belongsTo = 'LawType';	// one to one association with law_types table
	var $belongsTo = 'Standard';	// one to one association with standards table
	var $belongsTo = 'User';		// one to one association with users table
	
}

?>