<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    //

    public function index(Request $request, $gameId)
    {
        return 'GET Notes';
    }

    // public function store(Request $request, $gameId)
    // {
    //     // return "store notes";
    //     $note = new Note(['body' => 'Lorem ipsum text here.']);

    //     // $
    // }
    public function update(Request $request, $gameId, $characterId, $noteId)
    {
        // return 'EDIT NOTE';
        // return $request;
        $note = Note::find($noteId);

        // $characterCombo->directionalInputs()->detach();
        // $characterCombo->notations()->detach();
        // $characterCombo->attackButtons()->detach();
        // $characterCombo->touch();
        $note->title = $request->title;
        $note->body = $request->body;

        $note->save();

        return $note;
    }

    public function delete(Request $request, $gameId, $characterId, $noteId)
    {
        // return 'delete note hit';
        $characterNote = Note::find($noteId);

        $characterNote->delete();

        return $characterNote;
    }
}
