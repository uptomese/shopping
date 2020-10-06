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
                ->select('amount')
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
            ->select('order_items.id as order_items_id','order_items.product_id as product_id', 'categories.id as categories_id', 'categories.name as categories_name','orders.status_payment','order_items.product_quantity as product_quantity')
            ->leftjoin('orders','order_items.order_id','orders.id')
            ->leftjoin('products','order_items.product_id','products.id')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->groupby('$selected')
            ->orderby('order_items.id','desc')
            // ->where("orders.date" , "like" , "2019-09-17")
            // ->where('orders.created_at','like',"%'.$year.'%")
            ->where('orders.status_payment','=',1)
            ->get();

        // dd($categorie_quantity);

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
        // dd($categorie_quantity);


        return view('admin.displayDashboard', [
            'total' => '$' . number_format($total,2),
            'all_orders' => $all_orders,
            'all_year' => $all_year,
            'select_year' => $year,
            'total_month' => array_values($total_month),
            'total_order_count' => array_values($total_order_count),
            'categorie_quantity' => $categorie_quantity
        ]);
    }
}
