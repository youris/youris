<?php

class Standard extends AppModel {
	
	var $name = 'Standard';
	var $hasMany = 'Law'; 	// one to many association with laws table
	
}

?>