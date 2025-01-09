<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Task;
use App\User;
use Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['tasks'] = Task::where(function ($query) {
            
            if (Auth::user()->roles->contains('title', 'Admin')) {
               // $query->whereNotNull('id'); 
            } else {
                 
                $query->where('assigned_to', Auth::id());
            }
        })->orderBy('id','DESC')->get();
    
        return view('admin.task.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->data['users'] = User::whereDoesntHave('roles', function ($query) {
        //     $query->where('title', 'Admin');
        // })->get();

        $this->data['users'] = User::where('type','service_agent')->get();
        $this->data['end_users'] = User::where('type','end_user')->get();
        
        return view('admin.task.create',$this->data);
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        //dd($request->all());
        $newTask = new Task();
        $newTask->end_user = $request->end_user;
        $newTask->title = $request->title;
        $newTask->description = $request->description;
        $newTask->assigned_to = $request->assigned_to;
        $newTask->created_by = Auth::Id();
        $newTask->status = $request->status;
        $newTask->due_date = $request->due_date;
        $newTask->save();
        session()->flash('success', 'You have successfully added!');
        return redirect()->route('admin.task.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['task'] = Task::find($id);
        $this->data['users'] = User::whereDoesntHave('roles', function ($query) {
            $query->where('title', 'Admin');
        })->get();
        return view('admin.task.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $newTask = Task::find($id);
        $newTask->title = $request->title;
        $newTask->description = $request->description;
        $newTask->assigned_to = $request->assigned_to;
        //$newTask->created_by = Auth::Id();
        $newTask->status = $request->status;
        $newTask->due_date = $request->due_date;
        $newTask->save();
        session()->flash('success', 'You have successfully update!');
        return redirect()->route('admin.task.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $task = Task::find($id);
       // $task->delete();
        session()->flash('warning', 'You have successfully deleted!');
        return back();
    }
}
