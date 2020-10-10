<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

Use Auth;

use App\Order;
use App\OrderItem;

class HomeController extends Controller
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
        $orders = Order::collection('orders')
            ->select('*')
            ->where('user_id','=',Auth::user()->id*1)
            ->orderBy('id','desc')
            ->paginate(5);

        $array_orders = array();
        foreach($orders->items as $item){
            $order_items = OrderItem::collection('order_items')
                ->select('order_items.id as id','order_items.product_id as product_id','order_items.product_name as product_name','order_items.product_quantity as product_quantity','order_items.product_price as product_price','products.image as image')
                ->leftjoin('products','order_items.product_id','products.id')
                ->groupby('$selected')
                ->where('order_items.order_id','=',$item['id']*1)
                ->get();
            array_push($array_orders, array('order'=>$item,'items'=>$order_items));
        }

        // dd($orders, $array_orders);

        return view('user.home',[
            'orders'=>$orders,
            'array_orders' => $array_orders
            ]);
    }
}
