<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;
use App\User_nan;
use App\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // ,'exists:admin'
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    { 
        $user = User::create([
            'id' => User_nan::database()->collection("users")->getModifySequence('user_id'),
            'admin' => 0,
            'name' => $data['name'],
            'email' => $data['email'],
            'image' => 'default.jpg',
            'status' => "offline",
            'password' => Hash::make($data['password']),
            'address' => array('address_a', $data['address']),
            'phone' => $data['phone'],
        ]);

        $user_id = DB::connection('mongodb')->collection("users")->select('id')->orderBy('id','desc')->first();

        $session = Session::database()->collection("sessions")->insert([
            'id' => Session::database()->collection("sessions")->getModifySequence('sessions_id'),
            'user_id1' => $user_id['id']*1,
            'user_id2' => 1,
            'unread' => "0,0",
            'reading' => 0
        ]);

        return $user;
    }
}
