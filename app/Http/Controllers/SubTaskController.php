<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    public function createSubTask(Request $req){
        try {
            $subtask = new \App\Models\SubTask;
            $subtask->name = $req->name;
            $subtask->save();

            $middle = new \App\Models\Sub_in_Task;
            $middle->id_subtask = $subtask->id;
            $middle->id_task = $req->id_task;
            $middle->save();

            $data = [];
            $data['subtask'] = $subtask;
            $task = \App\Models\Task::find($req->id_task);
            $data['subtask']['task'] = $task->name;
            $data['subtask']['autor'] = $task->User()->get()[0]->tg_name;
            return response()->json($data, 201);
        } catch (\Throwable $th) {
            $data = [];
            $data[] = ['response' => 'Something went wrong'];
            $data[] = ['error' => $th];
            return response()->json($data, 400);
        }
    }
}
