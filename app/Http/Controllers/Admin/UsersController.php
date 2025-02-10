<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use App\Notes;
use App\Contacts;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $type = $_REQUEST['type'] ?? ''; // Validate or sanitize this input before using
        $this->data['users'] = User::where('type', 'LIKE', $type)->get();
 
        return view('admin.users.index',$this->data);
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->data['roles'] = Role::all()->pluck('title', 'id');
        $this->data['bms'] = Contacts::where('type', 'bm')->get();
        $this->data['tts'] = Contacts::where('type', 'tt')->get();
        $this->data['stores'] = Contacts::where('type', 'store')->get();

        return view('admin.users.create', $this->data);
    }

    public function store(Request $request)
    {   

       // dd($request->all());  
        //try {
            # validate the incoming request data
            // $request->validate([
            //     'email' => 'required',
            // ]);

            
            $roleId = Role::where('title', $request->input('type'))->first();
            # create a new user with the request data
            $user = User::create($request->all());
            
            # sync the user's roles, if any
            if ($request->filled('type')) {
                $user->roles()->sync($roleId->id);
            }

           // dd($user);

            //dd($request->all());
            # set a success message in the session
            session()->flash('success', 'User has been successfully added!');
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     # handle validation errors and flash them to the session
        //     session()->flash('error', implode(' ', $e->validator->errors()->all()));
        //     return redirect()->back()->withInput(); # redirect back with old input
        // } catch (\Exception $e) {
        //     # log the exception and flash a generic error message
        //     //dd($e->getMessage());
        //     \Log::error('Error adding user: ' . $e->getMessage());
        //     session()->flash('error', 'Something went wrong. Please try again.');
        //     return redirect()->back()->withInput(); # redirect back with old input
        // }

        # redirect to the users index page
       // dd(1);
        return redirect()->route('admin.users.index');
    }


    public function edit(User $user)
    {
        //abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');
        $this->data['bms'] = Contacts::where('type', 'bm')->get();
        $this->data['tts'] = Contacts::where('type', 'tt')->get();
        $this->data['stores'] = Contacts::where('type', 'store')->get();
        $this->data['user'] = $user;
        $this->data['roles'] = $roles;

        return view('admin.users.edit',$this->data);
    }

    public function update($id, Request $request)
    {
        try {
            # Validate the incoming request
            $request->validate([
                //'name' => 'required|string|max:255',
                //'email' => 'required|email|unique:users,email,' . $id,
                 
            ]);

            # Find the user by ID
            $user = User::findOrFail($id);

            # Update the user's information
            $user->update($request->except('type'));

            # Sync the user's roles
            //$user->roles()->sync($request->input('roles', []));

            # Set a success message in the session
            session()->flash('success', 'You have successfully updated the user!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            # Flash validation errors to the session
            session()->flash('error', implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            # Handle any other exceptions
            session()->flash('error', 'Something went wrong. Please try again.');
        }

        # Redirect to the users index page
        return redirect()->route('admin.users.index');
    }


    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();
        session()->flash('warning', 'You have successfully deleted!');
        return back();

    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function admin(Request $request)
    {
        $this->data['users'] = User::whereHas('roles', function ($query) {
            $query->where('title', 'Admin');
        })->orderBy('id','DESC')->get();
        return view('admin.admin.index',$this->data);
    }

    public function admin_create(Request $request)
    {
        return view('admin.admin.create');
    }

    public function admin_store(Request $request)
    {
        
            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);

            $roleId = Role::where('title', 'Admin')->first();
            # create a new user with the request data
            $user = User::create($request->all());
            
            $user->roles()->sync($roleId->id);
            
            session()->flash('success', 'Admin has been successfully added!');

            # redirect to the users index page
            return redirect()->route('admin.admin');
    }

    public function admin_edit($id){

        $this->data['user'] = User::find($id);
        return view('admin.admin.edit', $this->data);

    }

    // admin_update
    public function admin_update($id, Request $request)
{
    try {
        # Validate the incoming request
        $request->validate([
            //'name' => 'required|string|max:255', // Uncomment if needed
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        # Find the user by ID
        $user = User::findOrFail($id);

        # Update the user's information with validated data
        $user->update([
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'password' => \Hash::make($request->input('password')),
            'full_name' => $request->input('full_name'), // Uncomment if name is included in the request
        ]);

        # Sync the user's roles if needed
        // $user->roles()->sync($request->input('roles', []));

        # Set a success message in the session
        session()->flash('success', 'You have successfully updated the admin!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        # Flash validation errors to the session
        session()->flash('error', implode(' ', $e->validator->errors()->all()));
    } catch (\Exception $e) {
        # Handle any other exceptions
        session()->flash('error', 'Something went wrong. Please try again.');
    }

    # Redirect to the admin index page
    return redirect()->route('admin.admin');
}


    public function view_data($id){
        $users = User::findOrFail($id);
        $bm_notes = Notes::where('distributer_id',$id)->where('type','bm_notes')
        // ->where('notes_type','distributor')
        ->get();
        $tt_notes = Notes::where('distributer_id',$id)->where('type','tt_notes')
        // ->where('notes_type','distributor')
        ->get();
        $selfNotes = Notes::where('distributer_id',$id)->where('type','self_notes')->where('notes_type','distributor')->orderBy('id','DESC')->get();
        return view('admin.users.view_data', compact('users','bm_notes','tt_notes','selfNotes'));
    }
    
    
    public function notesStore($id, Request $request){

        $store  = new Notes();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $destinationPath = public_path('uploads');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Generate unique filename
            $file->move($destinationPath, $fileName);
            
            # store only file name in database
            $store->file = $fileName;
        }


        
        $store->user_id = \Auth::Id();
        $store->distributer_id = $id;
        $store->type = $request->type;
        $store->notes = $request->notes;
        if($request->contact_id){
            $store->contact_id = $request->contact_id;
        }
         
        if($request->notes_type){
            $store->notes_type = $request->notes_type;
        }
        
        $store->save();
        session()->flash('success', 'Notes successfully added!');
        return back();
    }


    public function contacts(){
        error_reporting(0);
        $contacts = Contacts::orderBy('id','DESC')->get();
        return view('admin.users.contacts', compact('contacts'));
    }

    public function createContact(){
         
        return view('admin.users.createContact');
    }

    public function contactStore(Request $request){

            $data = $request->all();
            $data['phone'] = $request->phone_number;

            unset($data['phone_number']);
            Contacts::create($data);
            session()->flash('success', 'You have successfully added!');
            return redirect()->route('admin.contacts');
    }

    // Contact Views
    public function contact_view($id){
        error_reporting(0);
        $user = Contacts::find($id);
        return view('admin.users.contact_data', compact('user'));
    }

    
    public function contactDelete($id){

        $user = Contacts::find($id);
        $user->delete();
        session()->flash('warning', 'You have successfully deleted!');
        return back();
    }

    public function contactEdit($id){
        
        $user = Contacts::find($id);
        return view('admin.users.contactEdit', compact('user'));
         
    }


    public function contactUpdate($id, Request $request)
    {
        $data = $request->all();
        $data['phone'] = $request->phone;
        $data['store_location'] = $request->name;
        unset($data['phone_number']);
        unset($data['name']);
        //dd($data);
        $contact = Contacts::findOrFail($id); # Find the contact by ID
        $contact->update($data); # Update the contact

        session()->flash('success', 'You have successfully updated the contact!');
        return redirect()->route('admin.contacts');
    }

    // Send email
    public function sendEmail(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $this->data['data'] = [
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        \Mail::send('email.contact', $this->data, function ($mail) use ($request) {
            $mail->to($request->recipient_email)
                ->subject($request->subject);

            # Attach file if provided
            if ($request->hasFile('attachment')) {
                $mail->attach($request->file('attachment')->getRealPath(), [
                    'as' => $request->file('attachment')->getClientOriginalName(),
                    'mime' => $request->file('attachment')->getMimeType(),
                ]);
            }
        });

        return back()->with('success', 'Email sent successfully!');
    }




}
