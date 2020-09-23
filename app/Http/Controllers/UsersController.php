<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Auth;

use App\Address;

class UsersController extends Controller
{
    
    public function getProfile()
    {
        $id = Auth::user()->id;

        $user = DB::connection('mongodb')->collection("users")->where('id','=', $id*1)->first();

        $address = DB::connection('mongodb')->collection("address")->where('user_id',"=",$id*1)->get();

        return view('user.profile',[
            'user' => $user,
            'address' => $address,
            ]);
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

    public function createAddress(Request $request, $id)
    {
        $new_address = array(
            'id' => Address::database()->collection("address")->getModifySequence('address_id'),
            'user_id' => $id*1,
            'title' => $request->input('address_title'),
            'address' => $request->input('address'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $save = Address::database()->collection("address")->insert($new_address);
        return redirect()->route('getProfile');
    }

    public function editAddress(Request $request, $id)
    {
        $address_edit = Address::collection("address")->where('id','=',$id*1)->first();

        $id = Auth::user()->id;

        $user = DB::connection('mongodb')->collection("users")->where('id','=', $id*1)->first();

        $address = DB::connection('mongodb')->collection("address")->where('user_id',"=",$id*1)->get();


        return view('user.editAddress',[
            'user' => $user,
            'address' => $address,
            'address_edit' => $address_edit
            ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $save = DB::connection('mongodb')->collection("address")->where('id',$id*1)->update([
            'title' => $request->input('title'),
            'address' => $request->input('address'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($save){
            return redirect()->route('getProfile');
        }
    }

    public function deleteAddress($id)
    {
        $save = DB::connection('mongodb')->collection("address")->where('id','=',$id*1)->delete();
        if($save){
            return redirect()->route('getProfile');
        }
    }
}
