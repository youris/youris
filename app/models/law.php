<?php

class Law extends AppModel {
	
	var $name = 'Law';
	var $belongsTo = array('User', 'LawType', 'Standard');		// one to one association with users table
	//var $hasOne = 'LawType';	// one to one association with law_types table
	//var $hasOne = 'Standard';	// one to one association with standards table
	
}

?>