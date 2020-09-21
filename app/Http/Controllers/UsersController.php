<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Auth;

class UsersController extends Controller
{
    
    public function getProfile()
    {
        $user = DB::connection('mongodb')->collection("users")->where('id','=',Auth::user()->id)->first();

        return view('user.profile',['user'=>$user]);
    }

    public function updateProfile(Request $request)
    {

        $save = DB::connection('mongodb')->collection("users")->where('id',Auth::user()->id*1)->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($save){
            return redirect()->route('getProfile');
        }
    }

    public function updateImageProfile(Request $request)
    {
        if($request->hasFile("image")){

            $user = DB::connection('mongodb')->collection("users")->where('id',"=",Auth::user()->id*1)->first();

            $ext = $request->file('image')->getClientOriginalExtension();
            
            $stringIameReFormat = str_replace(" ", "", $user['name']);
            
            $imageName = $stringIameReFormat.".".$ext;

            $imageEncoded = File::get($request->image);

            Storage::disk('local')->put('public/user_images/'.$imageName, $imageEncoded);

            DB::connection('mongodb')->collection("users")->where('id',Auth::user()->id*1)->update([
                'image' => $imageName,
            ]);

            return redirect()->route('getProfile');

        }else{
            return redirect()->route('getProfile');
        }
       
    }
}
