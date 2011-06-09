<?php

class LawsController extends AppController {
	
	var $helpers = array('Html', 'Form', 'Javascript');
    var $name = 'Laws';
	
	function index($id = null){
		$this->set('title_for_layout', 'View User Law');
		
		$params = array('fields' => array(
									'Law.title',
									'Law.insert_date', 
									'Law.id'));
		// retrieve all laws information
		$this->set('laws', $this->Law->find('list', $params));
	} 
	
	function view($id = null) {
		
	}
	
	function edit($id = null) {
		// if $_POST is empty read the information from DB
		if(empty($this->data)) {
			$this->data = $this->Law->read();
		}
		// if $_POST != empty save the information 
		else {
		 	if($this->Law->save($this->data)) {
                 $this->Session->setFlash('Your law has been updated.');
                 $this->redirect(array('action' => 'index'));
             }
		}
	}
	
	function add() {
		
	}
	
	function delete($id = null) {
		if($id = null) {
			$this->Session->setFlash('Some problem occured on law deleting');
        	$this->redirect(array('action' => 'index'));
		}
		$this->Law->delete($id);
		$this->Session->setFlash('The law with id '.$id.' has been deleted');
        $this->redirect(array('action' => 'index'));
	}
	
	function test() {
		
	}
	
}

?>