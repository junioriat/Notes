<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class mainControler extends Controller
{
    public function index()
    {
        // load user's note
        $id = session('user.id');
        $notes = User::find($id)
                        ->notes()
                        ->whereNull('deleted_at')
                        ->get()
                        ->toArray();

        // show home view
        return view('home', ['notes' => $notes]);
    }
    public function newNote()
    {
        // show new note view
        return view('new_note');
    }

    public function newNoteSubmit(Request $request){
        // validate request
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            //error messages
            [
                'text_title.required' => 'O Título da Nota é obrigatório',
                'text_title.min' => 'O Título da Nota deve ter no mínimo :min caracteres',
                'text_title.max' => 'O Título da Nota deve ter no máximo :max caracteres',
                'text_note.required' => 'O Conteúdo da Nota é obrigatório',
                'text_note.min' => 'O Conteúdo da Nota deve ter no mínimo :min caracteres',
                'text_note.max' => 'O Conteúdo da Nota deve ter no máximo :max caracteres'
            ]
        );
        // get user id
        $id = session('user.id');

        // create new note
        $note = new Note();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // redirect to home
        return redirect()->route('home');
    }

    public function editNote($id)
    {
        $id = Operations::decryptId($id);

        if($id === null){
            return redirect()->route('home');
        }
    
        // load note
        $note = Note::find($id);

        // show edit note view
        return view('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(Request $request)
    {
        // validate request
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            //error messages
            [
                'text_title.required' => 'O Título da Nota é obrigatório',
                'text_title.min' => 'O Título da Nota deve ter no mínimo :min caracteres',
                'text_title.max' => 'O Título da Nota deve ter no máximo :max caracteres',
                'text_note.required' => 'O Conteúdo da Nota é obrigatório',
                'text_note.min' => 'O Conteúdo da Nota deve ter no mínimo :min caracteres',
                'text_note.max' => 'O Conteúdo da Nota deve ter no máximo :max caracteres'
            ]
        );

        // check if note_id exists
        if($request->note_id == null){
            return redirect()->route('home');
        }

        // decrypt note_id
        $id = Operations::DecryptId($request->note_id);

        if($id === null){
            return redirect()->route('home');
        }

        // load note
        $note = Note::find($id);

        // update note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // redirect to home
        return redirect()->route('home');
    }

    public function deleteNote($id)
    {
        $id = Operations::decryptId($id);

        if($id === null){
            return redirect()->route('home');
        }

        // load note
        $note = Note::find($id);

        //show delete note confirm
        return view('delete_note', ['note' => $note]);
    }

    public function deleteNoteConfirm($id){

        // check if $id is encrypted
        $id = Operations::decryptId($id);

        if($id === null){
            return redirect()->route('home');
        }

        // load note
        $note = Note::find($id);

        // // hard delete
        // $note->delete();

        // soft delete
        // $note->deleted_at = date('Y:m:d H:i:s');
        // $note->save();

        // soft delete in model
        $note->delete();

        // hard delete in model
        // $note->forceDelete();

        // redirect to home
        return redirect()->route('home');

    }
    

}
