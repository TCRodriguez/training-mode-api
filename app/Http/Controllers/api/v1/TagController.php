<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $tags = Tag::where('game_id', $gameId)
            ->where('user_id', Auth::id())
            ->get();

        return $tags;
    }
}
