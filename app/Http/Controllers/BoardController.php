<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function createBoard(Request $req){
        try {
            $board = new \App\Models\Board;
            $board->id_creator = 2; #Auth::user()->id
            $board->name = $req->name;
            $board->publish_flag = $req->flag;
            $board->save();
            return response()->json($board, 201);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function getUserBoards($id){
        if(\App\Models\User::find($id)){
            $boards = \App\Models\Board::where('id_creator', $id)->get();
            return response()->json($boards, 200);
        }
        else{
            $data = ['response' => 'Not Found'];
            return response()->json($data, 404);
        }
    }

    public function getCountTaskInBoards($id){
        if(\App\Models\Board::find($id)){
            $count = \App\Models\Task_in_Board::where('id_board',$id)->count();
            $data = [\App\Models\Board::find($id)->name => $count];
        }
        return response()->json($data, 200);
    }
}
