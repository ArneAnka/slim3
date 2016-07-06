<?php

namespace App\Controllers;

use App\Models\Note;
use Slim\Views\Twig as View;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class NoteController extends Controller
{
	/**
	* Render all notes that we have in the DB
	*
	* @param null
	*
	* @return note_id, note_text, user_id, created_at, updated_at
	*/
    public function index($request, $response)
    {
    	// fetch all notes from model note, pass to view

        $notes = Note::where('user_id',$_SESSION['user_id'])->orderBy('note_id','desc')->get();

        return $this->view->render($response, 'notes/index.twig', ['notes' => $notes]);
    }

    /**
	* Show the note to be edited
	*
	* @param note_id
	*
	* @return mixed
	*/
    public function getEditNote($request, $response, $args)
    {
        $current_user_id = $_SESSION['user_id'];
        $user_id_of_note = Note::where('note_id', $args['note_id'])->first()->user_id;

        if($current_user_id == $user_id_of_note){
            return $this->view->render($response, 'notes/edit.twig', ['note' => Note::find($args['note_id'])]);
        }else{
            // If another user tries to view a note that he/she doesn't own, or that note doesn't exist.
            $this->flash->addMessage('error', 'No such note');
            
            return $response->withRedirect($this->router->pathFor('notes'));
        }
    }

    /**
	* Update a existing note one note to be edited
	*
	* @param note_id
	*
	* @return bool
	*/
    public function postEditNote($request, $response, $args)
    {
        // Check to se that the note does belong to the correct user
    	if($_SESSION['user_id'] == Note::where('note_id', $args['note_id'])->first()->user_id)
        {

            $note = Note::where('user_id', '=', $_SESSION['user_id'])->where('note_id', '=', $args['note_id'])->first();
            $note->note_text = $request->getParam('note_text');
            $note->save();

            $this->flash->addMessage('success', 'Note was successfully updated!');

            return $response->withRedirect($this->router->pathFor('notes'));
    	}else{ 
            /** Add a flas message **/
            $this->flash->addMessage('error', 'That did not go as planned.');

            /** Redirect back */
            return $response->withRedirect($this->router->pathFor('notes'));
    }

    }

    /**
	* Delete one note
	*
	* @param note_id
	*
	* @return bool
	*/
    public function deleteNote($request, $response, $args)
    {
        // Check to se that the note does belong to the correct user
        if($_SESSION['user_id'] == Note::where('note_id', $args['note_id'])->first()->user_id)
        {
            $note = Note::where('user_id', '=', $_SESSION['user_id'])->where('note_id', '=', $args['note_id'])->first();
            $note->delete();

            $this->flash->addMessage('info', 'Note was deleted');

            return $response->withRedirect($this->router->pathFor('notes'));
        }

        $this->flash->addMessage('error', 'No such note');

        return $response->withRedirect($this->router->pathFor('notes'));
    }

    /**
    * Add a new note
    *
    * @param
    *
    * @return bool
    */
    public function newNote($request, $response){

        /**
        * Check if the fields are valied
        */
        $validation = $this->validator->validate($request, [
            'note_text' => v::notEmpty()
                    ]);

        /**
        * If the fields fail, then redirect back to notes
        */
        if ($validation->failed()) 
        {
            $this->flash->addMessage('warning', 'Please insert some text.');

            return $response->withRedirect($this->router->pathFor('notes'));
        }
        
        // Insert new note into DB
        $note = new Note;

        $note->note_text = $request->getParam('note_text');

        $note->user_id = $_SESSION['user_id'];

        $note->save();

        $this->flash->addMessage('success', 'Your note has been saved!');
        unset($_SESSION['old']);

        return $response->withRedirect($this->router->pathFor('notes'));

    }


}
