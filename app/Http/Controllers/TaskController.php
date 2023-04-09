<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datetime;

class TaskController extends Controller
{
    public function createTask(Request $req){
        try {
            $board = \App\Models\Board::where('name', $req->board)->get();
            $task = new \App\Models\Task;
            $task->name = $req->name;
            if($req->description){ $task->description = $req->description; }
            $task->deadline = $req->deadline;
            $task->loop_flag = $req->loop_flag;
            $task->complete_flag = 0;
            $task->id_autor = 2; #Auth::user()->id
            $task->save();

            $middle = new \App\Models\Task_in_Board;
            $middle->id_task = $task->id;
            $middle->id_board = $board[0]->id;
            $middle->save();

            $data = [];
            $data[] = $task;
            // $data[] = $task->Board()->name; 

            return response()->json($data, 201);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function getUserBoardTask($board, $id){
        try {
            $tasks =[];
            $cur_board = \App\Models\Board::find($board);
            $task_in_board = \App\Models\Task_in_Board::where('id_board', $cur_board->id)->get();
            $user_tasks = \App\Models\Task::where('id_autor', $id)->get();
            foreach($task_in_board as $tib){
                foreach($user_tasks as $task){
                    if($tib->id_task == $task->id){
                        $tasks[] = $task;
                    }
                }
            }
            return response()->json($tasks, 200);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function getAllTask(){
        try {
            $tasks = \App\Models\Task::where('id_autor', 2)->get(); #Auth::user()->id
            $buf = [];
            foreach($tasks as $task){
                $buf[] = [$task->name => $task->id]; #Сюда надо написать название доски, но для этого починить связи. Пример ---> "Название задачи" => "Название доски"
            }
            $data = [];
            $data[] = $tasks;
            $data[] = $buf;
            return response()->json($data, 200);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function deleteTask($id){
        $task = \App\Models\Task::find($id);
        if ($task) {
            $task->delete();
            return response()->json($task->id, 200);
        } else {
            $data = ['response' => 'Not Found'];
            return response()->json($data, 404);
        }
    }

    public function completeTask($id){
        $task = \App\Models\Task::find($id);
        if ($task) {
            if($task->complete_flag)
                $task->complete_flag = 0;
            else
                $task->complete_flag = 1;
            $task->save();
            return response()->json($task, 200);
        } else {
            $data = ['response' => 'Not Found'];
            return response()->json($data, 404);
        }
    }

    public function getCompleteTask(){
        $tasks = \App\Models\Task::where('complete_flag', true)->where('id_autor', 2)->get(); #Auth::user()->id
        if($tasks->count() > 0){
            $day = new DateTime();
            $counter = 0;
            foreach ($tasks as $task) {
                $stamp = $task->updated_at->getTimestamp();
                if($stamp <= $day->getTimestamp() && $stamp >= $day->modify("-1 days")->getTimestamp())
                    $counter++;
            }
            return response()->json($counter, 200);
        }else{
            $data = ['response' => 'No Entries'];
            return response()->json($data, 404);
        }
    }
}
