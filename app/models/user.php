<?php

class User extends AppModel {
	
	var $name = 'User';
	var $hasMany = 'Category';	// one to many association with categories table
	var $hasMany = 'UserTag';
	
}


?>