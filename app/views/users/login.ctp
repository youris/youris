<?php
echo $this->Session->flash('auth'); 
echo $this->Form->create('User', array('action'=>'login'));
echo $this->Form->inputs(array(
   'legend' => 'Login',
   'email',
   'password'
));
echo $this->Form->end('Login');
?>