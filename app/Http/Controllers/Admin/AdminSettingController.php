<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends Controller
{
    
    public function settingConfig()
    {
        $config = DB::connection('mongodb')->collection("config")->get();
        return view('admin.adminSettingConfig', ['config' => $config]);
    }

    public function updateConfig(Request $request)
    {

        $save_web_payment = DB::connection('mongodb')->collection("config")->where('config',"=",'payment_payment_url')->update(['value' => $request->input('payment_payment_url')]);
        $save_mywab = DB::connection('mongodb')->collection("config")->where('config',"=",'payment_url_myweb')->update(['value' => $request->input('payment_url_myweb')]);
        $save_currency = DB::connection('mongodb')->collection("config")->where('config',"=",'payment_currencycode')->update(['value' => $request->input('payment_currencycode')]);
        $save_custip = DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custip')->update(['value' => $request->input('payment_custip')]);
        $save_custname = DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custname')->update(['value' => $request->input('payment_custname')]);
        $save_custemail = DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custemail')->update(['value' => $request->input('payment_custemail')]);
        $save_custphone = DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custphone')->update(['value' => $request->input('payment_custphone')]);
        $save_pagetimeout = DB::connection('mongodb')->collection("config")->where('config',"=",'pagetimeout')->update(['value' => $request->input('pagetimeout')]);
        $save_first_messages = DB::connection('mongodb')->collection("config")->where('config',"=",'first_messages')->update(['value' => $request->input('first_messages')]);

        $save_limit_messages = DB::connection('mongodb')->collection("config")->where('config',"=",'limit_messages')->update(['value' => $request->input('limit_messages')]);
        $save_delete_old_messages = DB::connection('mongodb')->collection("config")->where('config',"=",'delete_old_messages')->update(['value' => $request->input('delete_old_messages')]);

        $save_conpany_about = DB::connection('mongodb')->collection("config")->where('config',"=",'conpany_about')->update(['value' => $request->input('conpany_about')]);
        $save_conpany_address = DB::connection('mongodb')->collection("config")->where('config',"=",'conpany_address')->update(['value' => $request->input('conpany_address')]);
        $save_conpany_phone = DB::connection('mongodb')->collection("config")->where('config',"=",'conpany_phone')->update(['value' => $request->input('conpany_phone')]);
        $save_conpany_email = DB::connection('mongodb')->collection("config")->where('config',"=",'conpany_email')->update(['value' => $request->input('conpany_email')]);

        $save_web_payment_r = ($save_web_payment==1) ? "Payment Url" : '';
        $save_mywab_r = ($save_mywab==1) ? "Url Web" : '';
        $save_currency_r = ($save_currency==1) ? "Currency" : '';
        $save_custip_r = ($save_custip==1) ? "Cust IP" : '';
        $save_custname_r = ($save_custname==1) ? "Cust Name" : '';
        $save_custemail_r = ($save_custemail==1) ? "Cust Email" : '';
        $save_custphone_r = ($save_custphone==1) ? "Cust Phone" : '';
        $save_pagetimeout_r = ($save_pagetimeout==1) ? "Pagetimeout" : '';
        $save_first_messages_r = ($save_first_messages==1) ? "First Messages" : '';
        $save_limit_messages_r = ($save_limit_messages==1) ? "Limit Messages" : '';
        $save_delete_old_messages_r = ($save_delete_old_messages==1) ? "Delete old messages" : '';
        $save_conpany_about_r = ($save_conpany_about==1) ? "Conpany About" : '';
        $save_conpany_address_r = ($save_conpany_address==1) ? "Conpany Address" : '';
        $save_conpany_phone_r = ($save_conpany_phone==1) ? "Conpany Phone" : '';
        $save_conpany_email_r = ($save_conpany_email==1) ? "Conpany Email" : '';

        $text_update = $save_web_payment_r.$save_mywab_r.$save_currency_r.$save_custip_r.$save_custname_r.$save_custemail_r.$save_custphone_r.$save_pagetimeout_r.$save_first_messages_r.$save_limit_messages_r.$save_delete_old_messages_r.$save_conpany_about_r.$save_conpany_address_r.$save_conpany_phone_r.$save_conpany_email_r;

        if($save_web_payment||$save_mywab||$save_currency||$save_custip||$save_custname||$save_custemail||$save_custphone||$save_pagetimeout||$save_first_messages||$save_limit_messages_r||$save_delete_old_messages_r||$save_conpany_about_r||$save_conpany_address_r||$save_conpany_phone_r||$save_conpany_email_r){
            return back()->withsuccess($text_update.' update successfully');
        }else{
            return back()->with('fail', 'No update');
        }

    }
}
