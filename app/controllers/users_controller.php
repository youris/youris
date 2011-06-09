<?php
class UsersController extends AppController {
public function login() {
	$this->Auth->fields = array(
		'username' => 'email',
		'password' => 'password'
	);	
	
   if (
       !empty($this->data) &&
       !empty($this->Auth->data['User']['email']) &&
       !empty($this->Auth->data['User']['password'])
   ) {
	  $email = $this->User->find('first', array(
	          'conditions' => array(
	               'User.email' => $this->Auth->data['User']['email'],
	               'User.password' => $this->Auth->data['User']['password']
	         ),'recursive' => -1
	      ));
	   //var_dump($email);
	   if (!empty($email) && $this->Auth->login($email)) {
	        if ($this->Auth->autoRedirect) {
	           $this->redirect($this->Auth->redirect());
	        }
	   } else {
	        $this->Session->setFlash($this->Auth->loginError, $this->Auth->flashElement,
	        	array(), 'auth');
	        	echo ($this->Auth->login($email));
	      }
	   }
	}
	
	
   public function logout() {
     $this->redirect($this->Auth->logout());
   }

	public function dashboard() {
	}
	
	public function beforeFilter() {
	       parent::beforeFilter();
	       $this->Auth->allow('add');
	}
	
	public function add() {
	  if (!empty($this->data)) {
	      $this->User->create();
	      if ($this->User->save($this->data)) {
	         $this->Session->setFlash('User created!');
	         $this->redirect(array('action'=>'login'));
	       } else {
	          $this->Session->setFlash('Please correct the errors');
	        }
	    }
	}
	

}
?>