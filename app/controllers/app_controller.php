<?php
class AppController extends Controller {
	
	
   	
   public $components = array(
	   'Auth' => array(
	      'authorize' => 'controller',
	      'loginRedirect' => array(
	          'admin' => false,
	          'controller' => 'users',
	          'action' => 'dashboard'
	      ),
	     'loginError' => 'Invalid account specified',
	     'authError' => 'You don\'t have the right permission'
	   ),
	'Session'
   );
   
	public function beforeFilter() {
	  if ($this->Auth->getModel()->hasField('active'))
	      {$this->Auth->userScope = array('active' => 1);
	      }
	}
   
   public function isAuthorized() {
      return true;
   }
}
?>