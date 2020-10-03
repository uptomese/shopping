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
        if($request->input('address')==null){
            return redirect()->route('getProfile')->with('fail', 'Press the button to create an address.');
        }

        $type_address = $request->input('type_address');

        if($type_address=='address_a'){
            $address = array('address_a', $request->input('address'));
        }elseif ($type_address!='address_a'){
            $address_b = $request->input('type_address');
            $address_b = substr($address_b, 10);
            $address = array('address_b', $request->input('address'), $address_b);
        }

        $save = DB::connection('mongodb')->collection("users")->where('id',Auth::user()->id*1)->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $address,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($save){
            return redirect()->route('getProfile')->withsuccess('Profile updated successfully');
        }else{
            return redirect()->route('getProfile')->with('fail', 'Profile updated failed');
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

            return redirect()->route('getProfile')->withsuccess('Image updated successfully');
        }else{
            return redirect()->route('getProfile')->with('fail', 'Image updated failed');
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
        if($save){
            return redirect()->route('getProfile')->withsuccess('Address created successfully');
        }else{
            return redirect()->route('getProfile')->with('fail', 'Address created failed');
        }
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
            return redirect()->route('getProfile')->withsuccess('Address update successfully');
        }else{
            return redirect()->route('getProfile')->with('fail', 'Address update failed');
        }
    }

    public function deleteAddress($id)
    {
        $save = DB::connection('mongodb')->collection("address")->where('id','=',$id*1)->delete();
        if($save){
            return redirect()->route('getProfile')->withsuccess('Address delete successfully');
        }else{
            return redirect()->route('getProfile')->with('fail', 'Address delete failed');
        }
    }
}
