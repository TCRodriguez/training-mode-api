<?php

namespace App\Utilities;

use App\Models\Tag;

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

            $newCharacterMoveTag = Tag::firstOrNew( array('name' => $tagName, 'user_id' => 1, 'game_id' => $gameId) );

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
                ->where('user_id', 1)
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

            $newCharacterComboTag = Tag::firstOrNew( array('name' => $tagName, 'user_id' => 1, 'game_id' => $gameId) );

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
                ->where('user_id', 1)
                ->firstOrFail();
            // var_dump($characterMoveTag);

            $characterCombo->tags()->detach($characterComboTag->id);
        }
    }
}