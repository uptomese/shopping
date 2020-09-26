<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SessionResource;
use App\Events\SessionEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Session;

class SessionController extends Controller
{
    public function create(Request $request)
    {
        $id = Auth::user()->id;
        $session = Session::select()->where('user_id1', $id)->where('user_id2', $request->friend_id)->first();
        if (!$session) {
            $session2 = Session::select()->where('user_id1', $request->friend_id)->where('user_id2', $id)->first();
            if (!$session2) {
                $session3 = Session::create(['user_id1' => $id, 'user_id2' => $request->friend_id]);
                broadcast(new SessionEvent($session3, $id));
                return $session3;
            }
            broadcast(new SessionEvent($session2, $id));
            return $session2;
        }
        broadcast(new SessionEvent($session, $id));
        return $session;
    }
}
