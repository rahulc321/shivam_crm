<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\Training;
use App\Role;
use App\VideoView;
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
         
        if (Auth::user()->type == 'service_agent') {
            $userId = Auth::Id();

            $totalVideos = \DB::table('training')->where('role',Auth::user()->type)->count();

            $unviewedVideos = \DB::table('training')->where('role',Auth::user()->type)
            ->leftJoin('video_views', function ($join) use ($userId) {
            $join->on('training.id', '=', 'video_views.video_id')
            ->where('video_views.user_id', '=', $userId);
            })
            ->whereNull('video_views.id')
            ->count();

            $hasViewedAll = ($unviewedVideos === 0);

        }
         
        $this->data['task_access'] = $hasViewedAll;
        
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
        $newTask->due_date = $request->due_date.' '.$request->time;
        $newTask->save();


        //////////////////////////////////////////////////////////////////////////////////
        // Send email to Agent
        $this->data['end_user'] = User::where('id',$request->end_user)->first();
        $agent = User::where('id',$request->assigned_to)->first();
        $this->data['newTask'] = $newTask;

        $email = $agent->email;

       // return view('email.agent',$this->data);die;
        // dd($data);
        \Mail::send("email.agent", $this->data, function (
            $message
        ) use ($email) {
            $message
                ->to($email)
                ->from("info@gmail.com")
                ->subject("Congratulation Task Assigned");
        });

        //////////////////////////////////////////////////////////////////////////////
        $this->data['end_user'] = User::where('id',$request->assigned_to)->first();
        $end_user = User::where('id',$request->end_user)->first();
        $this->data['newTask'] = $newTask;

        $email = $end_user->email;

        //return view('email.user',$this->data);die;
        // dd($data);
        \Mail::send("email.user", $this->data, function (
            $message
        ) use ($email) {
            $message
                ->to($email)
                ->from("info@gmail.com")
                ->subject("Congratulation Task Created");
        });

        /////////////////////////////////////////////////////////////////////////////

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
         $task->delete();
        session()->flash('warning', 'You have successfully deleted!');
        return back();
    }
    
    public function task_detail($id)
    {   
        error_reporting(0);
        $this->data['task'] = Task::find($id);
        return view('admin.task.task_detail',$this->data); 
    }

    public function changeStatus($id,Request $request)
    {   
        $task = Task::find($id);
        $task->status = $request->status;
        $task->save();
        session()->flash('success', 'You have successfully update task status!');
        return back();
    }

    // Training module
    public function training(){
 
            // Get the current user's ID
        $currentUserId = Auth::id();

        // Fetch trainings with the video view count for the current user

        if (Auth::user()->roles->contains('title', 'Admin')){
            $this->data['trainings'] = Training::with('viewedByUser')->get();
        }else{
            $this->data['trainings'] = Training::with('viewedByUser')->where('role',Auth::user()->type)->get();
 
        }
        
        return view('admin.training.index',$this->data); 
    }

    public function trainingCreate(){
        error_reporting(0);
        $this->data['roles'] = Role::all();
        return view('admin.training.create',$this->data); 
    }
    
    public function trainingStore(Request $request)
    {
        
       

        // Handle video file upload
        $videoPath = null;
        if ($request->hasFile('file')) {
            $video = $request->file('file');

            // Check the file size (optional step for additional security)
            

            // Create a unique file name and specify the destination path
            $videoName = time() . '_' . $video->getClientOriginalName();
            $destinationPath = public_path('videos'); // Direct path to public/videos folder

            // Move the uploaded file to the public/videos directory
            $video->move($destinationPath, $videoName);

            // Store the relative path of the video
            $videoPath = 'videos/' . $videoName;
        }

         
        // Create a new training record and save it to the database
        $training = new Training();
        $training->title = $request->title;
        $training->video_url = $videoPath; // Save the public path of the video
        $training->role = $request->role;
        $training->status = $request->status;
        $training->video_time = $request->video_time;
        $training->save();

        // Set success message and redirect back
        session()->flash('success', 'You have successfully added the training!');
        return redirect()->route('admin.training');
    }

    public function markVideoWatched(Request $request)
    {

       
        // Check if the user has already watched the video
        $viewRecord = VideoView::firstOrCreate(
            [
                'video_id' => $request['video_id'],
                'user_id' => $request['user_id'],
            ],
            [
                'view_count' => 1,
            ]
        );

        // if (!$viewRecord->wasRecentlyCreated) {
        //     $viewRecord->increment('view_count');
        // }

        return response()->json(['success' => true, 'message' => 'Video marked as watched successfully!']);
    }

    // Delete trainings
    public function trainingDelete($id,Request $request)
    {   
        $trainingDelete = Training::find($id);
        
        //$trainingDelete->save();
        session()->flash('warning', 'You have successfully Deleted!');
        return back();
    }

    

}
