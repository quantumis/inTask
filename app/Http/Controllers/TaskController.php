<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datetime;

class TaskController extends Controller
{
    public function createTask(Request $req){
        try {
            $task = new \App\Models\Task;
            $task->name = $req->name;
            if($req->description){ $task->description = $req->description; }
            if($req->deadline){ $task->deadline = $req->deadline; }
            if($req->loop_flag){ $task->loop_flag = $req->loop_flag; }
            if($req->loop_iteration){ $task->loop_iteration = $req->loop_iteration; }
            $task->id_autor = $req->id_autor; #Auth::user()->id
            $task->save();

            $middle = new \App\Models\Task_in_Board;
            $middle->id_task = $task->id;
            $middle->id_board = $req->id_board;
            $middle->save();

            $middle = new \App\Models\Assigned;
            $middle->id_task = $task->id;
            $middle->id_user = $req->id_autor; #Auth::user()->id
            $middle->save();

            $data = [];
            $data['task'] = \App\Models\Task::find($task->id);
            $data['task']['id_autor'] = $task->User()->get()[0]->tg_name;
            $data['board'] = ["board" => $task->Board()->get()[0]->name]; 

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
            $tasks = [];
            $accesses = \App\Models\Access::where('id_board', $board)->where('id_user', $id)->get();
            if($accesses->count() > 0){
                $tasks_in = \App\Models\Task_in_Board::where('id_board', $board)->get();
                $assign = [];
                foreach($tasks_in as $t){
                    $assign[] = \App\Models\Assigned::where('id_user', $id)->where('id_task', $t->id_task)->get();
                }
                $subtasks = [];
                $i = 0;
                foreach($assign as $assig){
                    foreach($assig as $a){
                        $item = \App\Models\Task::find($a->id_task);
                        $sub = \App\Models\Sub_in_Task::where('id_task', $a->id_task)->get();
                        if($sub->count() > 0){
                            $j = 0;
                            foreach ($sub as $s){
                                $subtasks[$j] = $s->SubTask()->get();
                                $j++;
                            }
                        }
                        $tasks[$i] = $item;
                        $tasks[$i]["id_autor"] = $item->User()->get()[0]->tg_name;
                        $i++;
                    }
                }
                $data = [];
                $data['tasks'] = $tasks;
                $data['subtasks'] = $subtasks;
                return response()->json($data, 200);
            }else{
                $data = ['response' => 'null'];
                return response()->json($data, 200);
            }
            
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }

    public function getAllTask($id){
        try {
            $assigned = \App\Models\Assigned::where('id_user', $id)->get(); #Auth::user()->id
            $tasks = [];
            $subtasks = [];
            $i = 0;
            foreach($assigned as $as){
                $item = \App\Models\Task::find($as->id_task); #Potential need add where('complete_flag', 0)
                $sub = \App\Models\Sub_in_Task::where('id_task', $as->id_task)->get();
                if($sub->count() > 0){
                    $j = 0;
                    foreach ($sub as $s){
                        $subtasks[$j] = $s->SubTask()->get();
                        $j++;
                    }
                }
                $tasks[$i] = $item;
                $tasks[$i]["id_autor"] = $item->User()->get()[0]->tg_name;
                $i++;
            }
            $buf = [];
            foreach($tasks as $task){
                $buf[$task->Board()->get()[0]->name][] = $task->name;
                
            }
            $data = [];
            $data['tasks'] = $tasks;
            $data['boards'] = $buf;
            $data['subtasks'] = $subtasks;
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

    public function getTodayTask($id){
        try {
            $assigned = \App\Models\Assigned::where('id_user', $id)->get(); #Auth::user()->id
            $tasks = [];
            $i = 0;
            $today = date("Y-m-d");
            foreach($assigned as $as){
                $item = \App\Models\Task::where('deadline', $today)->where('id', $as->id_task)->get();
                if($item->count() == 0){

                }else{
                    foreach($item as $it){
                        $tasks[$i] = $it;
                        $tasks[$i]["id_autor"] = $it->User()->get()[0]->tg_name;
                        $i++;
                    }
                }
            }
            $buf = [];
            foreach($tasks as $task){
                $buf[$task->Board()->get()[0]->name][] = $task->name;
            }
            $data = [];
            $data['tasks'] = $tasks;
            $data['boards'] = $buf;
            return response()->json($data, 200);
        } catch (Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }
}
