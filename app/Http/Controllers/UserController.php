<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // name
    public function name(){
        echo 123;
    }

    // young
    public function young(){
        echo 23;
    }

    // model test
    public function store(Request $request){

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        $user->save();
    }
}
