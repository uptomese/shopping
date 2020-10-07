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
use App\Message;

class AdminUsersController extends Controller
{
    public function index()
    {
        $users = User_nan::collection('users')
            ->select('id','name','email','address','phone','image','admin','sale')
            ->where('id','!=','no')
            ->andWhere('admin','=',0)
            ->orderBy('id','desc')
            ->paginate(10);

        $admins = DB::connection('mongodb')->collection("users")
            ->select('id','name','email','address','phone','image','admin','sale')
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

            $array_user = array(
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
            );
            $save_user = User_nan::database()->collection("users")->insertGetId($array_user, 'id');

            if($save_user){
                if($admin_status != 1 && !$request->input('sale_status')){
   
                    $user_sale = User_nan::database()->collection("users")->select('id','name')->where("sale","=",1)->andwhere("status","=","online")->groupby('id','name')->random(1);

                    if($user_sale==null){
                        $user_sale = User_nan::database()->collection("users")->select('id','name')->where("sale","=",1)->groupby('id','name')->random(1);
                    }

                    $session = Session::database()->collection("sessions")->insertGetId([
                        'id' => Session::database()->collection("sessions")->getModifySequence('sessions_id'),
                        'user_id1' => $save_user[2]*1,
                        'user_id2' => $user_sale[0]['id']*1,
                        'unread' => "1,0",
                        'reading' => 0
                    ]);

                    self::firshMessage($session, $user_sale[0]['id']);

                    if($session) return redirect()->route('getUsers')->withsuccess('Users created successfully');
                }else return redirect()->route('getUsers')->with('fail', 'Session created failed');
            }else return redirect()->route('getUsers')->with('fail', 'Users created failed');
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
                return redirect()->route('getUsers')->withsuccess('Users updated successfully');;
            }else{
                redirect()->route('getUsers')->with('fail', 'Users updated failed');
            }
        }
    }

    public function firshMessage($session, $sale_id)
    {
        $config = DB::connection('mongodb')->collection("config")->where('config','=','first_messages')->first();

        if ($session) {
            $message_insert = Message::collection("messages")->insert(
                [
                    'id' => Message::collection("messages")->getModifySequence('id'),
                    "user_id" => $sale_id * 1,
                    "session" => $session[2] * 1,
                    "message" => $config['value'] ? $config['value'] : \Config::get('adminConfig.first_messages'),
                    "status" => 1,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s")
                ]
            );
        }
    }
}
