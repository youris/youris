<?php
echo $this->Form->create();
echo $this->Form->inputs(array(
    'legend' => 'Signup',
    'email',
    'password'
));
echo $this->Form->end('Submit');
?>