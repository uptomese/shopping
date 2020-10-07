<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Order;
use App\OrderItem;
use App\Payment;

class AdminDashboardsController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year');
        $year ? $year = $year : $year = '2020';
  
        // !-----------------------------------------------------------------

        $array_year = [];
        $get_year = DB::connection('mongodb')->collection("payment")
            ->select('id','created_at')
            ->where('txn_xtatus','=','0')
            ->groupBy('created_at')
            ->orderby('created_at','desc')
            ->get();

        foreach($get_year as $key => $item){
            array_push($array_year, date('y', strtotime($get_year[$key]['created_at'])));
        }
        $all_year = array_count_values($array_year);

        // !-----------------------------------------------------------------

        $amount = DB::connection('mongodb')->collection("payment")
            ->select('amount')
            ->whereBetween('created_at', [$year,trim($year+1)])
            ->where('txn_xtatus','=','0')
            ->get();
        $total = 0;
        foreach($amount as $item){
            $total = $total + ($item['amount']*1.0);
        }
        

        // !-----------------------------------------------------------------

        $all_orders = DB::connection('mongodb')->collection("payment")->whereBetween('created_at', [$year,trim($year+1)])->where('txn_xtatus','=','0')->count();

        // !-----------------------------------------------------------------

        $total_order_count = [];
        $order_count = 0;
        $total_month = [];
        $count = 1;
        for ($i=2; $i <= 13; $i++) {
            ($i < 10) ? $i = "0".$i : $i ;
            $j = ($i <= 10) ? "0".($i - 1) : $i - 1 ;
            $price = DB::connection('mongodb')->collection("payment")
                ->select('amount')
                ->whereBetween('created_at', [$year.'-'.$j, $year.'-'.$i])
                ->where('txn_xtatus','=','0')
                ->get();

            $order_count = DB::connection('mongodb')->collection("payment")
                ->whereBetween('created_at', [$year.'-'.$j, $year.'-'.$i])
                ->where('txn_xtatus','=','0')
                ->count();

            $amount_payment = 0;
            foreach($price as $item){
                $amount_payment = $amount_payment + ($item['amount']*1.0);
            }
            $total_month[$count] = $amount_payment;
            $total_order_count[$count] = $order_count;
            $count++;
        }


        // !-----------------------------------------------------------------

        $categorie_quantity = OrderItem::collection('order_items')
            ->select('order_items.id as order_items_id','order_items.product_id as product_id', 'categories.id as categories_id', 'categories.name as categories_name','orders.status_payment','order_items.product_quantity as product_quantity','orders.created_at')
            ->leftjoin('orders','order_items.order_id','orders.id')
            ->leftjoin('products','order_items.product_id','products.id')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->where('order_items.created_at','>=', $year."-00-00 00:00:00")
            ->andwhere('order_items.created_at','<=', $year."-12-31 23:59:59")
            ->andwhere('orders.status_payment','=',1)
            ->groupby('$selected')
            ->orderby('order_items.id','desc')
            ->get();

        $array_categorie_quantity = [];
        foreach($categorie_quantity as $key => $item){
            if(isset($item['categories_id'])){
                $categories_id = $item['categories_id'];
                $categories_name = $item['categories_name'];
            }else{
                $categories_id = null;
                $categories_name = null;
            }
            array_push($array_categorie_quantity, array(
                'order_items_id' => $item['order_items_id'],
                'categories_id' => $categories_id,
                'categories_name' => $categories_name,
                'quantity' => $item['product_quantity']
            ));
        }

        foreach($array_categorie_quantity as $key => $item){
            if($array_categorie_quantity[$key]['categories_id'] == null){
                unset($array_categorie_quantity[$key]);
            }
        }

        $array_categorie_quantity_format = array_values($array_categorie_quantity);

        $result = array_reduce($array_categorie_quantity_format,function($carry,$item){
            if(!isset($carry[$item['categories_id']])){
                $carry[$item['categories_id']] = ['categories_name'=>$item['categories_name'],'quantity'=>$item['quantity']];
            } else {
                $carry[$item['categories_id']]['quantity'] += $item['quantity'];
            }
            return $carry;
        });

        $arr = array_slice(array_reverse(array_sort($result, 'quantity')),0,3);

        $arr_catename = [];
        $arr_catequan = [];
        foreach($arr as $item){
            array_push($arr_catename, $item['categories_name']);
            array_push($arr_catequan, $item['quantity']);
        }
        $categorie_quantity = [
            $arr_catename,
            $arr_catequan
        ];

        // !-----------------------------------------------------------------

        $top_products = OrderItem::collection('order_items')
            ->select('order_items.id as order_items_id','order_items.product_quantity as product_quantity','products.name as products_name','order_items.product_id as product_id','categories.name as categorie_name','orders.created_at')
            ->leftjoin('orders','order_items.order_id','orders.id')
            ->leftjoin('products','order_items.product_id','products.id')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->where('order_items.created_at','>=', $year."-00-00 00:00:00")
            ->andwhere('order_items.created_at','<=', $year."-12-31 23:59:59")
            ->andwhere('orders.status_payment','=',1)
            ->groupby('$selected')
            ->get();

        $array_top_products = [];
        foreach($top_products as $key => $item){
            if(isset($item['product_id']) && isset($item['products_name']) && isset($item['categorie_name'])){
                $product_id = $item['product_id'];
                $products_name = $item['products_name'];
                $product_quantity = $item['product_quantity'];
                $categorie_name = $item['categorie_name'];
            }else{
                $product_id = null;
                $products_name = null;
                $categorie_name = null;
            }
            array_push($array_top_products, array(
                'order_items_id' => $item['order_items_id'],
                'product_id' => $product_id,
                'products_name' => $products_name,
                'product_quantity' => $item['product_quantity'],
                'categorie_name' => $categorie_name
            ));
        }

        foreach($array_top_products as $key => $item){
            if($array_top_products[$key]['product_id'] == null && $array_top_products[$key]['products_name'] == null){
                unset($array_top_products[$key]);
            }
        }

        $array_top_products_format = array_values($array_top_products);

        $result_top_products = array_reduce($array_top_products_format,function($carry,$item){
            if(!isset($carry[$item['product_id']])){
                $carry[$item['product_id']] = ['products_name'=>$item['products_name'],'product_quantity'=>$item['product_quantity'],'categorie_name'=>$item['categorie_name']];
            } else {
                $carry[$item['product_id']]['product_quantity'] += $item['product_quantity'];
            }
            return $carry;
        });

        $arr_top_product = array_slice(array_reverse(array_sort($result_top_products, 'product_quantity')),0,5);
        $arr_top_product_all = array_reverse(array_sort($result_top_products, 'product_quantity'));
        $sum = 0;
        foreach( $arr_top_product_all as $item){ $sum += $item['product_quantity']; }

        foreach( $arr_top_product as $key => $item){ 
            $percen = ($item['product_quantity']*100) / $sum; 
            $arr_top_product[$key]['percen'] = sprintf("%0.2f", $percen);
        }

        // !-----------------------------------------------------------------

        $top_users_in = Order::collection('orders')
            ->select('orders.id as orders_id','orders.price as orders_price','users.name as user_name','orders.user_id as user_id','users.email as user_email','orders.created_at')
            ->leftjoin('users','orders.user_id','users.id')
            ->where('orders.created_at','>=', $year."-00-00 00:00:00")
            ->andwhere('orders.created_at','<=', $year."-12-31 23:59:59")
            ->andwhere('orders.status_payment','=',1)
            ->groupby('$selected')
            ->andwhere('orders.user_id','!=','no')
            ->get();

        $top_users_out = Order::collection('orders')
            ->select('id as orders_id','price as orders_price','full_name as user_name','email as user_email','created_at')
            ->where('created_at','>=', $year."-00-00 00:00:00")
            ->andwhere('created_at','<=', $year."-12-00 00:00:00")
            ->andwhere('status_payment','=',1)
            ->andwhere('user_id','=','no')
            ->get();

        foreach($top_users_out as $key => $item){ $top_users_out[$key]['user_id'] = $item['user_name']; }

        foreach($top_users_out as $key => $item){ array_push($top_users_in, $item); }

        $result_top_users_in = array_reduce($top_users_in,function($carry,$item){
            if(!isset($carry[$item['user_id']])){
                $carry[$item['user_id']] = [
                    'user_id' => $item['user_id'],
                    'user_name' => $item['user_name'],
                    'orders_price' => $item['orders_price'],
                    'user_email' => $item['user_email']
                ];
            } else {
                $carry[$item['user_id']]['orders_price'] += $item['orders_price'];
            }
            return $carry;
        });

        $arr_top_users = array_slice(array_reverse(array_sort($result_top_users_in, 'orders_price')),0,5);


        return view('admin.displayDashboard', [
            'total' => '$' . number_format($total,2),
            'all_orders' => $all_orders,
            'all_year' => $all_year,
            'select_year' => $year,
            'total_month' => array_values($total_month),
            'total_order_count' => array_values($total_order_count),
            'categorie_quantity' => $categorie_quantity,
            'arr_top_product' => $arr_top_product,
            'arr_top_users' => $arr_top_users

        ]);
    }
}
