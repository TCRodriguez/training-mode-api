<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\DirectionalInputIcon;
use Illuminate\Http\Request;

class DirectionalInputIconController extends Controller
{
    public function index()
    {
        $icons = DirectionalInputIcon::all();

        return $icons;
    }
}
