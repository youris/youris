<?php

class LawsController extends AppController {
	
	var $helpers = array('Html', 'Form');
    var $name = 'Laws';
	
	public function index($id = null){
		$this->set('title_for_layout', 'View User Law');
		
		$params = array('fields' => array(
									'Law.title',
									'Law.insert_date'));
		// retrieve all laws information
		$this->set('laws', $this->Law->find('all', $params));
	} 
	
}

?>