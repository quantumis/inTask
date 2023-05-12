<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUser($id){
        $user = \App\Models\User::find($id);
        if($user)
            return response()->json($user, 200);
        else{
            $data = ['response' => 'Not Found'];
            return response()->json($data, 404);
        }
    }

    public function getAllUsers(){
        $users = \App\Models\User::all();
        if($users->count() > 0)
            return response()->json($users, 200);
        else{
            $data = ['response' => 'No Entries'];
            return response()->json($data, 404);
        }
    }

    public function createUser(Request $req){
        try {
            $user = new \App\Models\User;
            if($req->tg_name){ $user->tg_name = $req->tg_name; }
            $user->firstname = $req->firstname;
            $user->lastname = $req->lastname;
            $user->phone = $req->phone;
            $user->region = $req->region;
            $user->birthdate = $req->birthdate;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->save();
            return response()->json($user, 201);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function editMail(Request $req){
        try {
            $user = \App\Models\User::find($req->user_id);
            $user->email = $req->email;
            $user->save();
            return response()->json($user, 200);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function editPass(Request $req){
        try {
            $user = \App\Models\User::find($req->user_id);
            $user->password = Hash::make($req->password);
            $user->save();
            return response()->json($user, 200);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function editFirstLastName(Request $req){
        try {
            $user = \App\Models\User::find($req->user_id);
            if($req->lastname){
                $user->lastname = $req->lastname;
                $user->save();
            }
            if($req->firstname){
                $user->firstname = $req->firstname;
                $user->save();
            }
            return response()->json($user, 200);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }
}
