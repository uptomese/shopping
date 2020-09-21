<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\User_nan;

class AdminUsersController extends Controller
{
    public function index()
    {
        $users = User_nan::collection('users')
            ->select('id','name','email','address','phone','image','admin')
            ->where('id','!=','no')
            ->orderBy('id','desc')
            ->paginate(10);

        return view('admin.displayUsers',['users'=>$users]);
    }

    public function createUser()
    {
        return view('admin.createUserForm');
    }

    public function createdUser(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users|email|max:255',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $array_user = array([
            'id' => User_nan::database()->collection("users")->getModifySequence('user_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'image' => 'default.jpg',
            'admin' => $request->input('status')*1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $save_user = DB::connection('mongodb')->collection("users")->insert($array_user);
        if($save_user){
            return redirect()->route('getUsers');
        }
    }

    public function updateUser($id)
    {
        $user = DB::connection('mongodb')->collection("users")->where('id',"=",$id*1)->first();
        return view('admin.createUserForm',['user'=>$user]);    
    }

    public function updatedUser(Request $request, $id)
    {
        $save = DB::connection('mongodb')->collection("users")
            ->where('id',"=",$id*1)
            ->update([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'admin' => $request->input('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if($save){
            return redirect()->route('getUsers');
        }
    }
}