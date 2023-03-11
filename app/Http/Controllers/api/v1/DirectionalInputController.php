<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\DirectionalInput;
use Illuminate\Http\Request;

class DirectionalInputController extends Controller
{
    public function index()
    {
        $directionalInputs = DirectionalInput::with('icons')
            ->with('notations')
            ->get();

        return $directionalInputs;
    }
}
