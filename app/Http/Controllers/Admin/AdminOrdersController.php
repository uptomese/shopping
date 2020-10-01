<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Order;
use App\OrderItem;

class AdminOrdersController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year');
        $year ? $year = $year : $year = '2020';

        $month = $request->input('month');
        $month ? $month = $month : $month = date("m");

        $month2 = $month < 9 ? "0".($month+1) : $month+1 ;


        $result_array_order_in_user = [];
        $result_array_order_in = DB::connection('mongodb')->collection("orders")
            ->select('*')
            ->orderBy('date','desc')
            ->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])
            ->where('user_id','!=','no')
            ->paginate(10,['*'],'pageMonth_'.$month);

        foreach($result_array_order_in as $item){
            $user = DB::connection('mongodb')->collection("users")->select('id','name','email','image')->where('id','=',$item['user_id']*1)->first();
            array_push($result_array_order_in_user, array('user' => $user));
        }


        

        $array_year = [];
        $get_year = DB::connection('mongodb')->collection("orders")
            ->select('id','date')
            ->where('user_id','!=','no')
            ->groupBy('date')
            ->orderby('date','desc')
            ->get();

        foreach($get_year as $key => $item){
            array_push($array_year, date('y', strtotime($get_year[$key]['date'])));
        }
        $all_year = array_count_values($array_year);




        $count_list_order = [];
        $count2 = 0;
        for ($i=1; $i <= 13; $i++) { 
            ($i < 10) ? $i = "0".$i : $i ;
            $j = ($i <= 10) ? "0".($i - 1) : $i - 1 ;

            // $count_list_order[$count2] = $year.'-'.$j.'/ '. $year.'-'.$i ;
            $count_list_order[$count2] = DB::connection('mongodb')->collection("orders")
                ->whereBetween('date', [$year.'-'.$j, $year.'-'.$i])
                ->count();

            $count2++;
        }




        $orders_users_out = DB::connection('mongodb')->collection("orders")
            ->orderby('id','desc')
            ->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])
            ->where('user_id','=','no')
            ->paginate(10,['*'],'pageMonth__out_'.$month);

    
        // $orders_users_in = Order::collection('orders')
        //     ->select('orders.id as id','orders.date as date','orders.status as status','orders.price as price','users.name as name','orders.address as address','users.image as image','users.phone as phone','orders.quantity as quantity','orders.status_payment as status_payment')
        //     ->leftjoin('users','orders.user_id','users.id')
        //     ->groupby('$selected')
        //     ->orderby('orders.id','desc')
        //     ->where('orders.user_id','!=','no')
        //     ->paginate(10);

        // $orders_users_out = DB::connection('mongodb')->collection("orders")
        //     ->orderby('id','desc')
        //     ->where('user_id','=','no')
        //     ->paginate(10,['*'],'orders_users_out');


        $ldate = date('Y-m-d');

        $order_toDay =  DB::connection('mongodb')->collection("orders")
            ->where('date','=',$ldate)
            ->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])
            ->count();

        $order_paid_toDay =  DB::connection('mongodb')->collection("orders")
            ->where('status_payment','=',1)
            ->where('date','=',$ldate)
            ->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])
            ->count();

        $order_total =  DB::connection('mongodb')->collection("orders")->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])->count();

        $order_sened = DB::connection('mongodb')->collection("orders")->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])->where('status','=','success')->count();
        
        $order_paid = DB::connection('mongodb')->collection("orders")
            ->where('status_payment','=',1)
            ->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])
            ->count();

        $order_paid_yet = DB::connection('mongodb')->collection("orders")
            ->where('status_payment','!=',1)
            ->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])
            ->count();


        $order_sened = DB::connection('mongodb')->collection("orders")->where('status','=','success')->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])->count();

        $order_wait = DB::connection('mongodb')->collection("orders")->where('status','!=','success')->whereBetween('date', [$year.'-'.$month, $year.'-'.$month2])->count();

        return view("admin.order", [
            'orders_users_in' => $result_array_order_in,
            'orders_users_in_user' => $result_array_order_in_user,
            'orders_users_out' => $orders_users_out,
            'order_toDay' => $order_toDay,
            'order_paid_toDay' => $order_paid_toDay,
            'order_total' => $order_total,
            'order_paid' => $order_paid,
            'order_sened' => $order_sened,
            'order_wait' => $order_wait,
            'order_paid_yet' => $order_paid_yet,
            'all_year' => $all_year,
            'select_year' => $year,
            'date_m_now' => $month,
            'count_list_order' => $count_list_order
            ]);
    }

    public function showOrder($id)
    {
        $order_items = OrderItem::collection('order_items')
            ->select('order_items.id as id','order_items.product_name as product_name','order_items.product_quantity as product_quantity','order_items.product_price as product_price','products.image as image')
            ->leftjoin('products','order_items.product_id','products.id')
            ->groupby('order_items.id','order_items.product_name','order_items.product_quantity','order_items.product_price','products.image')
            ->where('order_items.order_id','=',$id*1)
            ->get();
            
        $get_user_id = DB::connection('mongodb')->collection("orders")->select('*')->where('id',"=",$id*1)->first();
        if($get_user_id['user_id']!='no'){
            $user_order = DB::connection('mongodb')->collection("users")->select('*')->where('id',"=",$get_user_id['user_id']*1)->first();  
        }else{
            $array_user = array([
                'name' => $get_user_id['full_name'],
                'email' => $get_user_id['email'],
                'address' => $get_user_id['address'],
                'phone' => $get_user_id['phone'],
                'image' => 'no',
            ]);
            $user_order = $array_user[0];
        }

        return view('admin.showOrder',[
            'id' => $id,
            'order_items' => $order_items,
            'user_order' => $user_order,
            'order' => $get_user_id
            ]);
    }

    public function updateOrderSuccess($id)
    {
        $save = DB::connection('mongodb')->collection("orders")
            ->where('id',"=",$id*1)
            ->update([
                'status' => 'success',
                'updated_at' => date('Y-m-d H:i:s')
                ]);
        return redirect()->route('adminDisplayOrders');
    }

    public function updateOrderWait($id)
    {
        $save = DB::connection('mongodb')->collection("orders")
            ->where('id',"=",$id*1)
            ->update([
                'status' => 'wait',
                'updated_at' => date('Y-m-d H:i:s')
                ]);
        return redirect()->route('adminDisplayOrders');
    }


}
