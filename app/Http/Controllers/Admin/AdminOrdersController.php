<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Order;
use App\OrderItem;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orders_users_in = Order::collection('orders')
            ->select('orders.id as id','orders.date as date','orders.status as status','orders.price as price','users.name as name','users.address as address','users.image as image','users.phone as phone','orders.quantity as quantity','orders.status_payment as status_payment')
            ->leftjoin('users','orders.user_id','users.id')
            ->groupby('$selected')
            ->orderby('orders.id','desc')
            ->where('orders.user_id','!=','no')
            ->paginate(10);

        $orders_users_out = DB::connection('mongodb')->collection("orders")
            ->orderby('id','desc')
            ->where('user_id','=','no')
            ->paginate(10,['*'],'orders_users_out');

        $ldate = date('Y-m-d');

        $order_toDay =  DB::connection('mongodb')->collection("orders")
            ->where('date','=',$ldate)
            ->count();

        $order_paid_toDay =  DB::connection('mongodb')->collection("orders")
            ->where('status_payment','=',1)
            ->where('date','=',$ldate)
            ->count();

        $order_total =  DB::connection('mongodb')->collection("orders")->count();

        $order_sened = DB::connection('mongodb')->collection("orders")->where('status','=','success')->count();
        
        $order_paid = DB::connection('mongodb')->collection("orders")
            ->where('status_payment','=',1)
            ->count();

        $order_paid_yet = DB::connection('mongodb')->collection("orders")
            ->where('status_payment','!=',1)
            ->count();


        $order_sened = DB::connection('mongodb')->collection("orders")->where('status','=','success')->count();

        $order_wait = DB::connection('mongodb')->collection("orders")->where('status','!=','success')->count();

        return view("admin.order", [
            'orders_users_in' => $orders_users_in,
            'orders_users_out' => $orders_users_out,
            'order_toDay' => $order_toDay,
            'order_paid_toDay' => $order_paid_toDay,
            'order_total' => $order_total,
            'order_paid' => $order_paid,
            'order_sened' => $order_sened,
            'order_wait' => $order_wait,
            'order_paid_yet' => $order_paid_yet
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

        return view('admin.showOrder',['id'=>$id,'order_items'=>$order_items,'user_order'=>$user_order]);
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
