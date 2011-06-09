<?php

class LawType extends AppModel {
	
	var $name = 'LawType';
	var $useTable = 'law_types';

	// many to one association with laws table
	var $hasMany = array(
			'Law' => array(
				'className' => 'Law',
				'order' => 'Law.law_id ASC'
			)
		);
	
}

?>