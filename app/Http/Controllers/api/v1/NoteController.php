<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    //

    public function gameNoteIndex(Request $request, $gameId)
    {
        // return 'GET Notes';
        //     $tags = Tag::where('game_id', $gameId)
        //     ->where('user_id', Auth::id())
        //     ->get();

        // return $tags;

        // ! May need to use whereHasMorph here to flip between characterId or whatever or "notable" id we establish....
        // https://laravel.com/docs/10.x/eloquent-relationships#querying-morph-to-relationships
        $notes = Note::where('game_id', $gameId)
            ->where('notable_type', 'App\Models\Game')
            ->where('notable_id', $gameId)
            ->where('user_id', Auth::id())
            ->get();

        // $notes = Note::whereHasMorph(
        //     'notable', 
        //     [Game::class],
        //     function (Builder $query) {
        //         $query->where('user_id', Auth::id());
        //     }
            
        //     )
        //     ->get();

        return $notes;
    }

    public function characterNoteIndex(Request $request, $gameId, $characterId)
    {
        // return 'GET Notes';
        //     $tags = Tag::where('game_id', $gameId)
        //     ->where('user_id', Auth::id())
        //     ->get();

        // return $tags;

        // ! May need to use whereHasMorph here to flip between characterId or whatever or "notable" id we establish....
        // https://laravel.com/docs/10.x/eloquent-relationships#querying-morph-to-relationships
        $notes = Note::where('game_id', $gameId)
            ->where('notable_type', 'App\Models\Character')
            ->where('notable_id', $characterId)
            ->where('user_id', Auth::id())
            ->get();

        return $notes;
    }

    public function characterComboNoteIndex(Request $request, $gameId, $characterId, $characterComboId)
    {
        // return 'GET Notes';
        //     $tags = Tag::where('game_id', $gameId)
        //     ->where('user_id', Auth::id())
        //     ->get();

        // return $tags;

        // ! May need to use whereHasMorph here to flip between characterId or whatever or "notable" id we establish....
        // https://laravel.com/docs/10.x/eloquent-relationships#querying-morph-to-relationships
        $notes = Note::where('game_id', $gameId)
            ->where('notable_type', 'App\Models\CharacterCombo')
            ->where('notable_id', $characterComboId)
            ->where('user_id', Auth::id())
            ->get();

        return $notes;
    }


    public function updateGameNote(Request $request, $gameId, $noteId)
    {
        $note = Note::find($noteId);
        $note->title = $request->title;
        $note->body = $request->body;

        $note->save();

        return $note;
    }

    public function updateCharacterNote(Request $request, $gameId, $characterId, $noteId)
    {
        $note = Note::find($noteId);
        $note->title = $request->title;
        $note->body = $request->body;

        $note->save();

        return $note;
    }

    public function updateCharacterComboNote(Request $request, $gameId, $characterId, $characterComboId, $noteId)
    {
        $note = Note::find($noteId);
        $note->title = $request->title;
        $note->body = $request->body;

        $note->save();

        return $note;
    }


    public function deleteGameNote(Request $request, $gameId, $noteId)
    {
        $gameNote = Note::find($noteId);

        $gameNote->delete();

        return $gameNote;
    }

    public function deleteCharacterNote(Request $request, $gameId, $characterId, $noteId)
    {
        $characterNote = Note::find($noteId);

        $characterNote->delete();

        return $characterNote;
    }

    public function deleteCharacterComboNote(Request $request, $gameId, $characterId, $characterComboId, $noteId)
    {
        $characterNote = Note::find($noteId);

        $characterNote->delete();

        return $characterNote;
    }
}
