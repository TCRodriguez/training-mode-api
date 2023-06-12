<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return 'GET Users endpoint HIT';
    }

    public function store(Request $request)
    {
      $user = User::create([
         'username' => $request->username,
         'email' => $request->email,
         'password' => Hash::make($request->password),
      ]);
      return $user;
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
