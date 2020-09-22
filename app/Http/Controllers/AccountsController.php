<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\URL;

class AccountsController extends Controller
{
    public function validatePasswordRequest(Request $request)
    {
        //You can add validation login here
        $user = DB::table('users')->where('email', '=', $request->email)->first();

        //Check if the user exists
        if ($user == null) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        //Create Password Reset Token
        $test = DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(60),
                'created_at' => date('Y-m-d H:i:s')
            ]);

        //Get the token just created above
        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

        if (self::sendResetEmail($request->email, $tokenData['token'])) {
            return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }
    }

    private function sendResetEmail($email, $token)
    {
        //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();

        //Generate, the password reset link. The token generated is embedded in the link
        $link = URL::to('') . '/password/reset/' . $token . '?email=' . $user['email'];

        $to_name = $user['name'];
        $to_email = $email;
        $data = array('name' => $to_name, 'body' => "Reset Password Link = ".$link);

        Mail::send('email.mail', $data, function($message) use ($to_name, $to_email) {

                $message->to($to_email, $to_name)->subject('Reset Password at web screwshop.');

            });

        try {
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function resetPassword(Request $request)
    {
        //Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed',
            'token' => 'required' ]);

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['email' => 'Please complete the form']);
        }

        $password = $request->password;
        // Validate the token

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        // Redirect the user back to the password reset request form if the token is invalid
        if ($tokenData == null) return view('auth.passwords.email')->withErrors(['email' => 'Token fail']);
    

        $user = User::where('email', $tokenData['email'])->first();
        // Redirect the user back if the email is invalid
        if (!$user) return redirect()->back()->withErrors(['email' => 'Email not found']);
        //Hash and update the new password
        $user->password = \Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)->delete();

        return redirect()->route('allProducts');

        //Send Email Reset Success Email
        // if ($this->sendSuccessEmail($tokenData['email'])) {
        //     return view('/');
        // } else {
        //     return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
        // }

    }

   
}
