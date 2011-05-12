<?php

class LawsController extends AppController {
	
	var $helpers = array('Html', 'Form');
    var $name = 'Laws';
	
	public function index($id = null){
		$this->pageTitle = 'Laws list';
		
		// retrieve all laws information
		$this->set('laws', $this->Law->find('all'));
	} 
	
}

?>