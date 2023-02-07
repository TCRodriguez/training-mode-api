<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return 'GET Users endpoint HIT';
    }

    public function store(Request $request)
    {
       return 'POST Users endpoint HIT';
    }

    public function show($id)
    {
       return 'GET User endpoint HIT';
    }

    public function update(Request $request, $id)
    {
       return 'PUT Users endpoint HIT'; 
    }

    public function delete($id)
    {
       return 'DELETE User endpoint HIT'; 
    }
}