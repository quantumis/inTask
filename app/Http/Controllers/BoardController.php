<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function createBoard(Request $req){
        try {
            $board = new \App\Models\Board;
            $board->id_creator = $req->id_user; #Auth::user()->id
            $board->name = $req->name;
            if($req->flag){$board->publish_flag = $req->flag;}
            $board->save();

            $board = \App\Models\Board::find($board->id);
            $board->id_creator = $board->User()->get()[0]->tg_name;

            $middle = new \App\Models\Access;
            $middle->id_user = $req->id_user; #Auth::user()->id
            $middle->id_board = $board->id;
            $middle->save();

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
            $accesses = \App\Models\Access::where('id_user', $id)->get();
            $boards = [];
            $i = 0;
            foreach($accesses as $ac){
                $item = \App\Models\Board::find($ac->id_board);
                $boards[$i] = $item;
                $boards[$i]['id_creator'] = $item->User()->get()[0]->tg_name;
                $i++;
            }
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
