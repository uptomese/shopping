<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

use App\Session;
use App\User;
use App\Message;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $auto =  Message::collection('sessions')->getModifySequence('id') ;     
        // dd("test: ",$message ,"AUTO",$auto);
        return view('home');
    }

    public static function format($slq)
    {   
        $format = array();
        foreach ($slq as $key =>$value) {
            $id = $value['id'];
            $message = $value['message'];
            $status = $value['status'];
            $created_at = $value['created_at'];
            $user_id = $value['0']['id'];
            $name = $value['0']['name'];
            
            array_push($format, array(
                "message_id" => $id,
                "message" => $message,
                "status" => $status,
                "id" => $user_id,
                "name" => $name,
                "created_at" => $created_at,
            ));
        }
        return $format;
    }

    private function deleteOldMessages($session)
    {
        $config = DB::connection('mongodb')->collection("config")->get();

        $date = $config[0]['value'] ? $config[0]['value']*1 : \Config::get('adminConfig.delete_old_messages');

        $last_month = date('Y-m-d h:i:s', time() - (86400 * $date));

        $delete_old_messages = DB::collection('messages')
            ->where([
                ['session','=',$session],
                ['status', '!=', 2],
                ['created_at', '<=', $last_month]
            ])
            ->delete();
            
        $delete_old_messages_image = DB::collection('messages')
            ->where([
                ['session','=',$session],
                ['status', '=', 2],
                ['created_at', '<=', $last_month]
            ])
            ->get();

        foreach($delete_old_messages_image as $item){

            $exists = Storage::disk('local')->exists('public/message_images/'.$item['message']);

            if($exists){

                $del_image = Storage::disk('local')->delete('public/message_images/'.$item['message']);
                
                DB::collection('messages')->where('id','=',$item['id']*1)->delete();

            }else{
                
                DB::collection('messages')->where('id','=',$item['id']*1)->delete();

            }

        }

    }

    public function fetchMessages(Request $request)
    {
        $config = DB::connection('mongodb')->collection("config")->get();

        $limit = $config[1]['value'] ? $config[1]['value']*1 : \Config::get('adminConfig.limit_messages');

        $session = $request->session;

        self::deleteOldMessages($session);

        $load = $request->load;
        (!$load) ? $load=1 : $request->load;
        $row = DB::collection('messages')->where([
            ['session','=',$session],
            ['status', '!=', 0],
            ['status', '!=', 3]
        ])->count();
        
        $skip = ($row * 1) - ($limit * $load);
        if($limit >= $row) $limit = $row;
        if($load >= 2){
            if($skip <= 0){
                if($skip > (0 - $limit)){
                    $x = $limit + $skip;
                    return self::fetchMessagesMore($session,0,$x);
                }else return 0;
            }else return self::fetchMessagesMore($session,$skip,$limit);  
        }else return self::fetchMessagesMore($session,$skip,$limit);
    }

    public static function fetchMessagesMore($session,$skip,$limit)
    {
        $result_session = DB::collection('messages')
                ->where([
                    ['session', '=', $session],
                    ['status', '!=', 0],
                    ['status', '!=', 3]
                ])
                ->skip($skip)
                ->take($limit) 
                ->get();

            $array_message = array();
            $users = array();

            foreach ($result_session as $key => $value) {
                $users = DB::collection('users')->where('id','=',$value['user_id']*1.0)->first();
                array_push($array_message, $value);
                array_push($array_message[$key], $users);
            }
    
            $session_event = DB::collection('sessions')->where('id','=',$session)->first();
            $result_message = self::format($array_message);
    
            $user = Auth::user();
            $socket = array();
            array_push($socket,$result_message,$session_event,$user);
            return $socket;    
    }

    public static function messageReading($session,$status,$unread)
    {
        $str1 = explode(",", $unread)[0];
        $str2 = explode(",", $unread)[1];
        $id = Auth::user()->id;
        $id1 = DB::collection('sessions')->select('user_id1')->where('id','=',$session)->where('user_id1', $id)->first();
        if (!$id1) {
            $id2 = DB::collection('sessions')->select('user_id2')->where('id','=',$session)->where('user_id2', $id)->first();
            if($id2){
                $status == 1 ? $result2 = $str1 : $result2 = $str1 + 1;
                $unread_now2 = $result2 . ',' . $str2;
                $update_unread2 = DB::collection('sessions')->where('id','=',$session)->update(['unread' => $unread_now2]);
                return $result2;
            }
        }else{
            $status == 1 ? $result1 = $str2 : $result1 = $str2 + 1;
            $unread_now1 = $str1 . ',' . $result1;
            $update_unread1 = DB::collection('sessions')->where('id','=',$session)->update(['unread' => $unread_now1]);
            return $result1;
        }
    }

    public function sendMessage(Request $request)
    {
        $messages = $request->data['message']['message'];
        $session = $request->data['message']['session'];
        $id = $request->data['message']['id'];

        $message_insert = Message::collection("messages")->insert(
            [
                'id'=> Message::collection("messages")->getModifySequence('id') ,
                "user_id" => $id,
                "session" => $session,
                "message" => $messages,
                "status" => 1,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);

        $user = Auth::user();
        $message = $request->data['message'];

        $reading = DB::collection('sessions')->select('reading')->where('id','=',$session)->first();
        $get_reading = $reading['reading'];
        $str = DB::collection('sessions')->select('unread')->where('id','=',$session)->first();
        $get_str = $str['unread'];
        $count_unread = self::messageReading($session,$get_reading,$get_str);

        $last_id = DB::collection('messages')->where('session','=',$session)->orderBy('id', 'desc')->first();

        $socket = array();
        array_push($socket,$user,$message,$session,$count_unread,$last_id['id']);

        return $socket;

    }

    public function updateMessage(Request $request)
    {
        $id = $request->data['id'];
        $message = $request->data['message'];
        $date = date("Y-m-d H:i:s");
        $message_update = DB::collection('messages')->where('id',$id)->update(['message' => $message, 'updated_at' => $date]);
        // return ['status' => 'Message Update!'];
        return $message_update;
    }

    public function destroyMessage(Request $request)
    {
        $id = $request->id;
        $message_delete = DB::collection('messages')->where('id',$id*1.0)->update(['status' => 0]);
        // return ['status' => 'Message Delete!'];
    }

    public function reCount(Request $request)
    {
        $session = $request->session;
        $id = Auth::user()->id;
        
        $str = DB::collection('sessions')->select('unread')->where('id','=',$session)->first();
        $get_str = $str['unread'];
        $str1 = explode(",", $get_str)[0];
        $str2 = explode(",", $get_str)[1];

        $id1 = DB::collection('sessions')->select('user_id1')->where('id','=',$session)->where('user_id1', $id)->first();
        if(!$id1){
            $id2 = DB::collection('sessions')->select('user_id2')->where('id','=',$session)->where('user_id2', $id)->first();
            if($id2){
                $re_unread2 = $str1 . ",0";
                $update_re_unread2 = DB::collection('sessions')->where('id','=',$session)->update(['unread' => $re_unread2]);
            }
        }else{
            if ($id == $id1['user_id1']) {
                $re_unread1 = "0," . $str2;
                $update_re_unread1 = DB::collection('sessions')->where('id','=',$session)->update(['unread' => $re_unread1]);
            }
        }
    }

    public function reading(Request $request)
    {
        $session_reading = $request->data['session_reading'];
        $diff = array();
        array_push($diff, $session_reading);

        $id_1 = $request->data['session']['user_id1'];
        $id_2 = $request->data['session']['user_id2'];

        $session_id1_1 = Db::collection('sessions')->select('id')->where('user_id1',$id_1)->get();
        $session_id1_2 = Db::collection('sessions')->select('id')->where('user_id2',$id_1)->get();
        $session_id2_1 = Db::collection('sessions')->select('id')->where('user_id1',$id_2)->get();
        $session_id2_2 = Db::collection('sessions')->select('id')->where('user_id2',$id_2)->get();

        $session = array();
        array_push($session, $session_id1_1, $session_id1_2, $session_id2_1, $session_id2_2);

        $result = array();
        $keys = array_keys($session);
        for ($i = 0; $i < count($session); $i++) {
            foreach ($session[$keys[$i]] as $key => $value) {
                array_push($result, $value['id']);
            }
        }
        $unique = array_unique($result);
        $diff_session_reading = array_diff($unique, $diff);

        $session_reading = DB::collection('sessions')->where('id','=',$session_reading)->update(['reading' => 1]);

        foreach ($diff_session_reading as $key => $value) {
            $session_re_reading = DB::collection('sessions')->where('id','=',$value)->update(['reading' => 0]);
        }
        // return $session_reading;
    }

    public function reReading(Request $request)
    {
        $id = $request->data['user_id'];
        $session_reading = $request->data['session'];
        $diff = array();
        array_push($diff, $session_reading);

        $id1 = DB::collection('sessions')->where('user_id1','=',$id)->get();
        $id2 = DB::collection('sessions')->where('user_id2','=',$id)->get();

        $session = array();
        array_push($session, $id1, $id2);
        $result = array();
        $keys = array_keys($session);

        for ($i = 0; $i < count($session); $i++) {
            foreach ($session[$keys[$i]] as $key => $value) {
                array_push($result, $value['id']);
            }
        }

        $diff_session_reading = array_diff($result, $diff);

        foreach ($diff_session_reading as $key => $value) {
            DB::collection('sessions')->where('id','=',$value)->update(['reading' => 0]);
        }
    }

    public function uploadFile(Request $request)
    {
        if($request->get('image')){
          $session = $request->get('session');
          $image = $request->get('image');
          $name = $session.'_'.time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];

          $resize = \Image::make($image)->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
          })->encode('jpg');
          $hash = md5($resize->__toString());

          Storage::put('public/message_images/'.$name, $resize->__toString());


        //   \Image::make($request->get('image'))->save(public_path('images/message_images/').$name);

          $id = Auth::user()->id;
          
          $message_insert = Message::collection("messages")->insert(
            [
                'id'=> Message::collection("messages")->getModifySequence('id') ,
                "user_id" => $id,
                "session" => $session,
                "message" => $name,
                "status" => 2,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
        }

        $user = Auth::user();
        $message = $name;

        $reading = DB::collection('sessions')->select('reading')->where('id','=',$session)->first();
        $get_reading = $reading['reading'];
        $str = DB::collection('sessions')->select('unread')->where('id','=',$session)->first();
        $get_str = $str['unread'];
        $count_unread = self::messageReading($session,$get_reading,$get_str);

        $last_id = DB::collection('messages')->where('session','=',$session)->orderBy('id', 'desc')->first();

        $socket = array();
        array_push($socket,$user,$message,$session,$count_unread,$last_id['id']);
        return $socket;
     }

     public function videoTime(Request $request)
     {
        $time_insert = Message::collection("messages")->insert(
            [
                'id'=> Message::collection("messages")->getModifySequence('id') ,
                "user_id" => $request->data['user_id']*1,
                "session" => $request->data['session']*1,
                "message" => $request->data['time'],
                "status" => 3,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
     }

     public function videoTimeEnd(Request $request)
     {
        $session = DB::collection('sessions')
            ->where('id', '=', $request->data['session']*1)
            ->first();
        $user = array();
        array_push($user, $session['user_id1'], $session['user_id2']);
        self::deleteElement($request->data['user_id'] ,$user);
        if(count($user) > 1){
            $user_from_email = User::select()->where('email', '=', $request->data['user_id'])->first();
            $time_start = DB::collection('messages')->where('session','=',$request->data['session']*1)->where('status','=',3)->orderBy('id', 'desc')->first();
            if($time_start){
                $time_end = $request->data['time']*1.0;
                $time = ($time_end - ($time_start['message']*1));
                $time_format = self::time_elapsed_A($time/1000);
                $date = date("Y-m-d H:i:s");
                $time_start_update = DB::collection('messages')->where('id',$time_start['id'])->update([
                    'message' => $time_format,
                    'status' => 4,
                    'updated_at' => $date
                    ]);
                $socket = array();
                $message = array($time_format,'time');
                array_push($socket,$user_from_email,$message,$request->data['session']);
                return $socket;
            }
        }else{
            $user_from_id = User::select()->where('id', '=', $user[0])->first();
            $time_start = DB::collection('messages')->where('session','=',$request->data['session']*1)->where('status','=',3)->orderBy('id', 'desc')->first();
            if($time_start){
                $time_end = $request->data['time']*1.0;
                $time = ($time_end - ($time_start['message']*1));
                $time_format = self::time_elapsed_A($time/1000);
                $date = date("Y-m-d H:i:s");
                $time_start_update = DB::collection('messages')->where('id',$time_start['id'])->update([
                    'message' => $time_format,
                    'status' => 4,
                    'updated_at' => $date
                    ]);
                $socket = array();
                $message = array($time_format,'time');
                array_push($socket,$user_from_id,$message,$request->data['session']);
                return $socket;
            }
        }
     }
    
     public function time_elapsed_A($secs)
     {
        $bit = array(
            'y' => $secs / 31556926 % 12,
            'w' => $secs / 604800 % 52,
            'd' => $secs / 86400 % 7,
            'h' => $secs / 3600 % 24,
            'm' => $secs / 60 % 60,
            's' => $secs % 60
            );
           
        foreach($bit as $k => $v)
            if($v > 0)$ret[] = $v . $k;
           
        return join(' ', $ret);
    }

    public function deleteElement($element, &$array)
    {
        $index = array_search($element, $array);
        if($index !== false){
            unset($array[$index]);
        }
    }
}
