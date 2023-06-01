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
      //  return 'POST Users endpoint HIT';
      // return $request;
      $user = User::create([
         // 'username' => $request->input('username'),
         'username' => $request->username,
         // 'email' => $request->input('email'),
         'email' => $request->email,
         'password' => Hash::make($request->password),
      ]);
   //   return new TrainerResource($trainer);
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
