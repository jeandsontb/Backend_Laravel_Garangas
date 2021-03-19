<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class UserController extends Controller
{
    public function addUser(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|digits:11|unique:users,cpf',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);

        if(!$validator->fails()) {
            $name = $request->input('name');
            $email = $request->input('email');
            $cpf = $request->input('cpf');
            $password = $request->input('password');

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $newUser = new User();
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->cpf = $cpf;
            $newUser->password = $hash;
            $newUser->admin = 0;
            $newUser->save();

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function editUser($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'same:password_confirm',
            'password_confirm' => 'same:password'
        ]);

        if(!$validator->fails()) {
            $name = $request->input('name');
            $email = $request->input('email');
            $admin = $request->input('admin');
            $password = $request->input('password');

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $setUser = User::find($id);


            if($setUser) {
                $setUser->name = $name;
                $setUser->email = $email;
                $setUser->cpf = $setUser['cpf'];
                $setUser->password = $password !== null ? $hash : $setUser['password'];
                $setUser->admin = $admin;
                $setUser->save();
            } else {
                $array['error'] = 'Post inexistente.';
                return $array;
            }

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }



    public function getUser() {
        $array = ['error' => '', 'data' => ''];

        $getUser = User::orderBy('name', 'ASC')->get();

        $array['data'] = $getUser;

        return $array;
    }


    public function removeUser($id) {
        $array = ['error' => ''];

        $removeUser = User::find($id);
        if($removeUser) {
            User::find($id)->delete();
        } else {
            $array['error'] = 'Post inexistente.';
            return $array;
        }

        return $array;
    }
}
