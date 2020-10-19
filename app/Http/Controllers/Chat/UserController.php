<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Message;
use App\Session;
use App\Session_jsg;

class UserController extends Controller
{

    public function __invoke(User $user)
    {
        // $user = User::find(Auth::id());
        // $user->status = 'offline';
        // $user->save();
        // broadcast(new UserOnline($user));
    }

    public static function format($slq,$my_id)
    {   
        $format = array();
        foreach ($slq as $key =>$value) {
            $id = $value['id'];
            $unread = $value['unread'];
            $user_id = $value['0']['id'];
            $name = $value['0']['name'];
            $status = $value['0']['status'];
            $email = $value['0']['email'];
            $image = $value['0']['image'];
            $index_unread = $value['1']['index_unread'];
            
            if($value['1']['message_last'] == 0){
                $message = '';
                array_push($format, array(
                    "session" => $id,
                    "unread" => $unread,
                    "id" => $user_id,
                    "name" => $name,
                    "status" => $status,
                    "email" => $email,
                    "image" => $image,
                    "index_unread" => $index_unread,
                    "unread_message" => $message,
                ));
            }else{ 
                $message = $value['1']['message_last']['message'];
                $status_message = $value['1']['message_last']['status'];
                $status_message == 2 ? $message = 'รูปภาพ' : '';
                $message_last_userid = $value['1']['message_last']['user_id'];
                ($my_id == $message_last_userid) ? $message_last = 'คุณ: ' . $message : $message_last = $message ; 
                array_push($format, array(
                    "session" => $id,
                    "unread" => $unread,
                    "id" => $user_id,
                    "name" => $name,
                    "status" => $status,
                    "email" => $email,
                    "image" => $image,
                    "index_unread" => $index_unread,
                    "unread_message" => $message_last,
                ));
            }
        }
        return $format;
    }

    public static function joinGetUser($slq,$selecte_user)
    {   
        if($selecte_user==1){
            $user = 'user_id2';
            $index_unread = 0;
        }else{
            $user = 'user_id1';
            $index_unread = 1;
        }

        $array_join = array();
        foreach ($slq as $key => $value) {
            $users[] = DB::collection('users')->where('id','=',$value[$user])->first();
            $message_last = DB::collection('messages')
                ->where([
                    ['session','=',$value['id']],
                    ['status','!=',0]
                ])
                ->orderBy('id', 'desc')
                ->first();
            (!$message_last) ? $message_last = 0 : '';
            array_push($array_join,$value);
            array_push($array_join[$key],$users[$key],array('index_unread'=>$index_unread,'message_last'=>$message_last));
        }
        return $array_join;
    }

    public function getFriends(Request $request)
    {
        $id = $request->id;
        $user = User::where('id',$id)->get();
        $status_user_online = User::where('id',$id)->update(['status' => 'online']);
        
        $id1 = DB::collection('sessions')
            ->select()
            ->where('user_id1','=',$id)
            ->get();

        $id2 = DB::collection('sessions')
            ->select()
            ->where('user_id2','=',$id)
            ->get();
        
        $result_id1 = self::joinGetUser($id1,1);
        $result_id2 = self::joinGetUser($id2,2);
       
        $friends = array();
        array_push($friends, $result_id1, $result_id2);
        $result = array();
        $keys = array_keys($friends);
        for ($i = 0; $i < count($friends); $i++) {
            foreach ($friends[$keys[$i]] as $key => $value) {
                array_push($result, $value);
            }
        }

        $result_2 = self::format($result,$id);
        return $result_2;
    }

    public function userOnline(Request $request)
    { 
        ($id = $request->id) ? DB::collection('users')->where('id',$id)->update(['status' => 'online']) : '';
    }

    public function userOffline(Request $request)
    {
        ($id = $request->id) ? DB::collection('users')->where('id',$request->id)->update(['status' => 'offline']) : '';
        $session1 = DB::collection('sessions')->select('id')->where('user_id1', $request->id)->first();
        $session2 = DB::collection('sessions')->select('id')->where('user_id2', $request->id)->first();
        ($session1) 
            ? DB::collection('sessions')->where('id',$session1['id'])->update(['reading' => 0]) 
            : DB::collection('sessions')->where('id',$session2['id'])->update(['reading' => 0]);
    }

    public function newUserOnline(Request $request, $myId, $newId)
    {

        $check = DB::collection('sessions')->where('user_id1','=',$myId*1)->where('user_id2','=',$newId*1)->first();

        if($check!=null){

            $new_user = User::where('id','=',$check['user_id2']*1)->first();

            $new_user['index_unread'] = 0;

            return array('new_user' => $new_user, 'session' => $check);

        }else{

            $check2 = DB::collection('sessions')->where('user_id1','=',$newId*1)->where('user_id2','=',$myId*1)->first();

            if($check2){

                $new_user2 = User::where('id','=',$check2['user_id1']*1)->first();
    
                $new_user2['index_unread'] = 1;
    
                return array('new_user' => $new_user2, 'session' => $check2);

            }else{

                return false;

            }
        }
    }
}
