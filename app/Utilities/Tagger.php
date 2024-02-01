<?php

namespace App\Utilities;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class Tagger {


    private static function composeTagName($tagName)
    {
        $tagName = trim($tagName);

        $tagName = strtolower($tagName);

        $tagName = preg_replace( '/[^a-zA-Z0-9]/', '-', $tagName );

        $tagName = preg_replace( '/-{2,}/', '-', $tagName );

        $tagName = trim( $tagName, '-' );

        return $tagName;
    }



    public static function tagCharacterMove($gameId, $characterMove, $tags)
    {
        $now = now();
        foreach($tags as $tag){

            $tagName = self::composeTagName($tag);

            $newCharacterMoveTag = Tag::firstOrNew( array('name' => $tagName, 'user_id' => Auth::id(), 'game_id' => $gameId) );

            $newCharacterMoveTag->name = $tagName;

            $newCharacterMoveTag->save();

            $characterMove->tags()->attach(
                $newCharacterMoveTag->id, 
                [
                    'taggable_type' => 'App\Models\CharacterMove',
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        }
    }
    /**
     * Detach tags from Character Combos
     * 
     * @param string $gameId
     * @param object $characterCombo
     * @param array $tags
     */
    public static function untagCharacterMove($gameId, $characterMove, $tags)
    {
        foreach($tags as $tag) {
            // echo $tag;
            $characterMoveTag = Tag::where('name', $tag)
                ->where('user_id', Auth::id())
                ->where('game_id', $gameId)
                ->firstOrFail();
            // var_dump($characterMoveTag);

            $characterMove->tags()->detach($characterMoveTag->id);
        }
    }

    public static function tagCharacterCombo($gameId, $characterCombo, $tags)
    {
        $now = now();
        foreach($tags as $tag){

            $tagName = self::composeTagName($tag);

            $newCharacterComboTag = Tag::firstOrNew( array('name' => $tagName, 'user_id' => Auth::id(), 'game_id' => $gameId) );

            $newCharacterComboTag->name = $tagName;

            $newCharacterComboTag->save();

            $characterCombo->tags()->attach(
                $newCharacterComboTag->id, 
                [
                    'taggable_type' => 'App\Models\CharacterCombo',
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        }
    }
    /**
     * Detach tags from Character Combos
     * 
     * @param string $gameId
     * @param object $characterCombo
     * @param array $tags
     */
    public static function untagCharacterCombo($gameId, $characterCombo, $tags)
    {
        foreach($tags as $tag) {
            // echo $tag;
            $characterComboTag = Tag::where('name', $tag)
                ->where('user_id', Auth::id())
                ->where('game_id', $gameId)
                ->firstOrFail();
            // var_dump($characterMoveTag);

            $characterCombo->tags()->detach($characterComboTag->id);
        }
    }

    public static function tagNote($gameId, $note, $tags)
    {
        $now = now();
        foreach($tags as $tag){

            $tagName = self::composeTagName($tag);

            $newNoteTag = Tag::firstOrNew( array('name' => $tagName, 'user_id' => Auth::id(), 'game_id' => $gameId) );

            $newNoteTag->name = $tagName;

            $newNoteTag->save();

            $note->tags()->attach(
                $newNoteTag->id, 
                [
                    'taggable_type' => 'App\Models\Note',
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        } 
    }

    public static function untagNote($gameId, $note, $tags)
    {
        foreach($tags as $tag) {
            // echo $tag;
            $noteTag = Tag::where('name', $tag)
                ->where('user_id', Auth::id())
                ->where('game_id', $gameId)
                ->firstOrFail();

            $note->tags()->detach($noteTag->id);
        }
    }
}