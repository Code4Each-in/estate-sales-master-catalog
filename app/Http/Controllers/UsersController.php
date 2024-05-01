<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUser;
use App\Http\Requests\UpdateUser;
use App\Models\Role;
use App\Models\User;
use App\Notifications\CommonEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $usersFilter = request()->all() ;
        // $allUsersFilter = $usersFilter['all_users'] ?? '';
        // $rolesFilter =  $rolesFilter['role_filter'] ?? '';
     
  //Get Roles For User Without Super Admin
        $roles = Role::whereNot('name','SUPER_ADMIN')->get();
        // $loginUser = auth()->user();
      
        // //Get Users Without Super Admin
        // if($loginUser->role->name == 'SUPER_ADMIN'){
        //     $users = User::with('role')->whereHas('role', function($q) {
        //         $q->where('name', '!=', 'SUPER_ADMIN');
        //     });
        //     if(!$allUsersFilter  == 'on'){
        //         $users = $users->where('status','active');
        //     }
           
        //     if (request()->has('role_filter') && request()->input('role_filter')!= '') {
        //         $users = $users->whereHas('role', function($query) { 
        //             $query->where('id', request()->input('role_filter')); 
        //         });
        //     }

        //    $users = $users->get();
            
        // }else{
        //     $users = [];
        // }

        if (request()->ajax()) {
            $query = User::with('role')->whereHas('role', function($q) {
                $q->where('name', '!=', 'SUPER_ADMIN');
            });

            // if (request()->has('status_filter') && request()->input('status_filter') == 'all') {
            //     // If 'status_filter' is 'all', select all records
            //     $query->select('*');
            // } else
            if (request()->has('role_filter') && request()->input('role_filter')!= '') {
                // If 'status_filter' is not empty, filter by status
                $query->whereHas('role', function($q) { 
                    $q->where('id', request()->input('role_filter')); 
                });
                // $query->where('status', request()->input('status_filter'));
            } elseif (request()->has('search') && request()->input('search.value') !== null) {
                // If search value is provided, perform search across multiple columns
                $searchText = request()->input('search.value');
                $columns = ['first_name', 'last_name','email', 'phone', 'status'];
                $query->where(function($query) use ($columns, $searchText) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%'.$searchText.'%');
                    }
                })->orWhereHas('role', function($q) use ($searchText) {
                    $q->where('name', 'like', '%'.$searchText.'%')->whereNot('name','SUPER_ADMIN');
                });
            } 
            // else {
            //     // Default behavior, filter by status 'publish'
            //     $query->whereNot('status', 'publish');
            // }

              // Implement server-side pagination
              $start = request()->input('start', 0);
              $length = request()->input('length', 10);
      
              $totalRecords = $query->count();
      
              $data = $query
                  ->skip($start)
                  ->take($length)
                  ->get();
      
              return response()->json([
                  'data' => $data,
                  'draw' => request()->input('draw', 1),
                  'recordsTotal' => $totalRecords,
                  'recordsFiltered' => $totalRecords,
              ]);
        }
        
        return view('users.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddUser $request)
    {
         //Request Data validation
         $validatedData = $request->validated();

         //Create User
         $user = User::create([
             'first_name' => $validatedData['first_name'],
             'last_name' => $validatedData['last_name'],
             'email' => $validatedData['email'],
             'phone' => $validatedData['phone'],
             'role_id' => $validatedData['role'],
             'password' =>  Hash::make($validatedData['password']),
             'status' => 'active',
             'created_at' => now(),
             'updated_at' => now(),
         ]);
         if($user){
            $messages = [
                'subject' => 'Welcome to '. config('app.name'). '! Your Account Details Inside',
                'greeting-text' => 'Dear ' .ucfirst($user->first_name). ',',
                'url-title' => 'Click Here To Login',
                'url' => '/login',
                'lines_array' => [
                    'body-text' => 'Your Account Is Created On '.config('app.name'). '. Below are your login credentials:',
                    'special_Email' => $user->email,
                    'special_Password' => $validatedData['password'],
                ],
                'thanks-message' => 'Once again, welcome aboard, and thank you for choosing MasterCatalog!',
            ];
                   // Send Credentails To User 
                   $user->notify(new CommonEmailNotification($messages));
                  
                   $request->session()->flash('message','User created successfully.');

                   return Response()->json(['status'=>200, 'users'=>$user]);
            
            // return response()->json(['success' => true, 'message' => 'User created successfully']);
         }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       //Get Roles For User Without Super Admin
       $roles = Role::whereNot('name','SUPER_ADMIN')->get();

        $users = User::whereHas('role', function($q) {
            $q->where('name', '!=', 'SUPER_ADMIN');
        })->find($id);

        return Response()->json(['users' =>$users,'roles' =>$roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUser $request,  $id)
    {   

         //Request Data validation
         $validatedData = $request->validated();

         $user = User::where('id', $id)->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'role_id' => $validatedData['role'],
            'status' => $validatedData['status'],
            'updated_at' => now(),
        ]);
        
        // if (isset($validatedData['password']) && $validatedData['password'] != null) {
        //     User::where('id', $id)->update(['password' => Hash::make($validatedData['password'])]);
        // }
        
        $request->session()->flash('message','User updated successfully.');
		return Response()->json(['status'=>200]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        session()->flash('message','User Deleted successfully.');
        return response()->json(['success' => true]);
    }

   
}
