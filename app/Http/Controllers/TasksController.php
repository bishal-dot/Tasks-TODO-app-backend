<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function show (Request $request){
        $user = $request->user();

        $userTasks = Tasks::where('user_id',$user->id)->get();

        return response()->json([
            'success' => true,
            'reason'  => null,
            'response' => $userTasks
        ]);
    }

     public function add (Request $request){
        $UserID = $request->user()->id;
        $NewTask = Tasks::create([
            'title'=> $request->title,
            'user_id'=> $UserID,
        ]);

        // create activity
        // Activity::create([
        //     'user_id' => $UserID,
        //     'icon'=> 'user',
        //     'detail' => 'You have created new task ' . $request->title,
        // ]);

        return response()->json([
            'success'  =>  true,
            'reason' => 'New task Added',
            'response' => $NewTask
        ]);
    }

    // edit tasks
    public function edit (Request $request, $id){
        $UserID = $request->user()->id;
        $Task = Tasks::where('user_id', $UserID)->where('id', $id)->first();

        $Task->update($request->all());

         // create activity
        //  Activity::create([
        //     'user_id'   => $UserID,
        //     'icon'      => 'update',
        //     'detail'    => 'You have updated a task ' . $request->title,
        // ]);

        return response()->json([
            'error'     => false,
            'reason'    => 'updated',
            'response'  => $Task
        ]);
    }

     public function remove (Request $request,$id){
        $UserID = $request->user()->id;
        // get the task anc collect detail info for activity
        $Task = Tasks::where('user_id', $UserID)->where('id', $id)->first();
        $Detail = 'You have deleted a task ' . $Task->title;

        Tasks::where('user_id', $UserID)->where('id', $id)->delete();

        // create activity
        // Activity::create([
        //     'user_id'   => $UserID,
        //     'icon'      => 'delete',
        //     'detail'    => $Detail,
        // ]);

        return response()->json([
            'error'     => false,
            'reason'    => 'deleted',
            'response'  => null
        ]);
    }

    public function complete (Request $request, $id){
        $UserID = $request->user()->id;
        $Task = Tasks::where('user_id', $UserID)->where('id', $id)->first();

        // get detail message
        $Detail = "";
        if ($Task->completed) $Detail = "Task " . $Task->title . " successfully incomplete.";
        else $Detail = "Task " . $Task->title . " successfully completed";

        // update complete or incomplete
        $Task->update([
            'completed'     => !$Task->completed
        ]);

        // create activity
        // Activity::create([
        //     'user_id'   => $UserID,
        //     'icon'      => 'complete',
        //     'detail'    => $Detail,
        // ]);

        return response()->json([
            'error'     => false,
            'reason'    => 'updated',
            'response'  => $Task
        ]);
    }

    public function favorite (Request $request, $id){
        $UserID = $request->user()->id;
        $Task = Tasks::where('user_id', $UserID)->where('id', $id)->first();

        // get detail message
        $Detail = "";
        if (!$Task->favorite) $Detail = "Task " . $Task->title . " favorite in your list.";
        else $Detail = "Task " . $Task->title . " unfavorite from your list.";

        // update complete or incomplete
        $Task->update([
            'favorite'     => !$Task->favorite
        ]);

        // create activity
        // Activity::create([
        //     'user_id'   => $UserID,
        //     'icon'      => 'favorite',
        //     'detail'    => $Detail,
        // ]);

        return response()->json([
            'error'     => false,
            'reason'    => 'updated',
            'response'  => $Task
        ]);
    }

    public function search (Request $request, $keyword){
        $SearchTask = Tasks::where('title','like','%'.$keyword.'%')->get();
    return response()->json([
        'success'  => true,
        'reason'   => "Searched User",
        'response' => $SearchTask
        ]);
    }
}
