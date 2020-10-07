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

        DB::connection('mongodb')->collection("config")->where('config',"=",'payment_payment_url')->update(['value' => $request->input('payment_payment_url')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'payment_url_myweb')->update(['value' => $request->input('payment_url_myweb')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'payment_currencycode')->update(['value' => $request->input('payment_currencycode')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custip')->update(['value' => $request->input('payment_custip')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custname')->update(['value' => $request->input('payment_custname')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custemail')->update(['value' => $request->input('payment_custemail')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'payment_custphone')->update(['value' => $request->input('payment_custphone')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'pagetimeout')->update(['value' => $request->input('pagetimeout')]);
        DB::connection('mongodb')->collection("config")->where('config',"=",'first_messages')->update(['value' => $request->input('first_messages')]);

        return redirect()->route('settingConfig')->withsuccess('Config update successfully');
    }
}
