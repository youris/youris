<?php
/* 
 * Controller creato per la gestione dei Post
 *
 *  Author: Davide Monfrecola
 *
 */


class PostsController extends AppController {

    var $helpers = array('Html', 'Form');
    var $name = 'Posts';

    /* aggiungo un'azione al controller.
     * Un'azione rappresenta una singola funzione oppure un'interfaccia
     * dell'applicazione.
     * For example, when users request www.example.com/posts/index
     * (which is also the same as www.example.com/posts/)
     * they might expect to see a listing of posts.
     * The code for that action would look something like this:
     */

     function index() {
         $this->pageTitle = 'Posts list';
         /* posts è una variabile della view */
         $this->set('posts', $this->Post->find('all'));
         /*
          * The single instruction in the action uses set() to pass data from the
          * controller to the view (which we'll create next).
          * The line sets the view variable called 'posts' equal to the return
          * value of the find('all') method of the Post model. Our Post model is
          * automatically available at $this->Post because we've followed Cake's naming conventions.
          */
     }

     function view($id = null){
         // setto id del Post tramite il modello
         $this->Post->id = $id;
         $this->set('post', $this->Post->read());
     }

     function add(){
         if(!empty($this->data)){
             // Calling the save() method will check for validation errors and
             // abort the save if any occur
             if($this->Post->save($this->data)) {
                $this->Session->setFlash('Your post has been saved');
                $this->redirect(array('action' => 'index'));
             }
         }
     }

     function delete($id){
         $this->Post->delete($id);
         $this->Session->setFlash('The post with id '.$id.' has been deleted');
         $this->redirect(array('action' => 'index'));
     }

     function edit($id = null){
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
}
?>
