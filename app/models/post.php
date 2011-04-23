<?php
/* 
 *  Modello che viene utilizzato per interagire con il DB per tutto ciò che
 *  riguarda i post.
 *
 *  Author: Davide Monfrecola
 *
 */


class Post extends AppModel {

    var $name = 'Post';

    // Validation rules (form)
    var $validate = array(
        'title' => array(
                'rule' => 'notEmpty'
        ),
        'body' => array(
                'rule' => 'notEmpty'
        )
    );
}

?>
