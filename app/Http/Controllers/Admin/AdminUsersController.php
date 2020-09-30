<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\User_nan;
use App\Session;

class AdminUsersController extends Controller
{
    public function index()
    {
        $users = User_nan::collection('users')
            ->select('id','name','email','address','phone','image','admin')
            ->where('id','!=','no')
            ->andWhere('admin','=',0)
            ->orderBy('id','desc')
            ->paginate(10);

        $admins = DB::connection('mongodb')->collection("users")
            ->select('id','name','email','address','phone','image','admin')
            ->where('id','!=','no')
            ->where('admin','=',1)
            ->orderBy('id','desc')
            ->paginate(10,['*'],'admin');

        return view('admin.displayUsers',[
            'users' => $users,
            'admins' => $admins,
            ]);
    }

    public function createUser()
    {
        return view('admin.createUserForm');
    }

    public function createdUser(Request $request)
    {
        if($request->input('name')=='admin'){

            return redirect()->route('getUsers');
            
        }else{
            $admin_status = $request->input('status')*1;

            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => 'required|unique:users|email|max:255',
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6'
            ]);

            $array_user = array([
                'id' => User_nan::database()->collection("users")->getModifySequence('user_id'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'address' => array('address_a', $request->input('address')),
                'phone' => $request->input('phone'),
                'image' => 'default.jpg',
                'status' => "offline",
                'admin' => $admin_status,
                'sale' => $request->input('sale_status') ? $request->input('sale_status')*1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $save_user = DB::connection('mongodb')->collection("users")->insert($array_user);
            if($save_user){
                if($admin!=1 && !$request->input('sale_status')){
                    $user_id = DB::connection('mongodb')->collection("users")->select('id')->orderBy('id','desc')->first();
                    $session = Session::database()->collection("sessions")->insert([
                        'id' => Session::database()->collection("sessions")->getModifySequence('sessions_id'),
                        'user_id1' => $user_id['id']*1,
                        'user_id2' => 1,
                        'unread' => "0,0",
                        'reading' => 0
                    ]);
                    if($session) return redirect()->route('getUsers');
                }else return redirect()->route('getUsers');
            }
        }
    }

    public function updateUser($id)
    {
        $user = DB::connection('mongodb')->collection("users")->where('id',"=",$id*1)->first();
        return view('admin.createUserForm',['user'=>$user]);    
    }

    public function updatedUser(Request $request, $id)
    {
        if($request->input('name') == 'admin'){
            return redirect()->route('getUsers');
        }else{
            $save = DB::connection('mongodb')->collection("users")
                ->where('id',"=",$id*1)
                ->update([
                    'name' => $request->input('name'),
                    'address' => array('address_a', $request->input('address')),
                    'phone' => $request->input('phone'),
                    'admin' => $request->input('status')*1,
                    'sale' => $request->input('sale_status') ? $request->input('sale_status')*1 : 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
    
            if($save){
                return redirect()->route('getUsers');
            }
        }
    }
}
