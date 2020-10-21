<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Auth;
use Redirect;

use App\Product;
use App\Cart;
use App\Categorie;
use App\Order;
use App\User;
use App\Review;
use App\OrderItem;
use App\User_nan;
use App\Session as Session_model;
use App\Message;

class ProductsController extends Controller
{

    public function aasort (&$array, $key) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        arsort($sorter); //asort
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret;
    }

    public function top_sell () {

        $order_item_product_id = DB::connection('mongodb')->collection("order_items")
            ->select('product_id')
            ->groupBy('product_id')
            ->take(15)
            ->get();
        $array_order_item_product_id = array();
        foreach($order_item_product_id as $item){
            $count = DB::connection('mongodb')->collection("order_items")->where('product_id','=',$item['product_id'])->count();
            array_push($array_order_item_product_id, array('product_id'=>$item['product_id'],'count'=>$count));
        }
        self::aasort($array_order_item_product_id,'count');
        $top_products_id = array_values($array_order_item_product_id);

        $top_products = array();
        $array_categorie = array();
        foreach($top_products_id as $item){
            $product = DB::connection('mongodb')->collection("products")->where('id','=',$item['product_id'])->first();
            if($product){
                array_push($top_products, $product);
                array_push($array_categorie, $product['categorie_id']);
            }
        }

        foreach($array_categorie as $key => $item){
            $categorie = DB::connection('mongodb')->collection("categories")->select('name')->where('id','=',$item)->first();
            if($categorie){
                $top_products[$key]['categorie_name'] = $categorie['name'];
            }
        }

        return array_splice($top_products, 0, 9);
    }

    public function index(Request $request)
    {
        $products = Product::collection("products")
            ->select('products.id as id','products.name as name','products.price as price','products.image as image','categories.name as categorie_name','products.ratting as ratting','products.stock as stock')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->groupby('$selected')
            ->orderby('products.id','desc')
            ->limit(10)
            ->get();

        $ratting_products = Product::collection("products")
            ->select('products.id as id','products.name as name','products.price as price','products.image as image','categories.name as categorie_name','products.ratting as ratting','products.stock as stock')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->groupby('$selected')
            ->orderby('products.ratting','desc')
            ->limit(10)
            ->get();

        $top_products = self::top_sell();

        $prevCart = $request->session()->get('cart');
 
        $cart = new Cart($prevCart);
        $cart->updatePriceAndQunatity();

        $totalQuantity = $cart->totalQuantity;
        $totalPrice = $cart->totalPrice;
        $cartItems = $cart;

        return view("allproducts", [
            "products" => $products, 
            'totalQuantity' => $totalQuantity, 
            'cartItems' => $cartItems, 
            'totalPrice' => $totalPrice,
            'ratting_products' => $ratting_products,
            'top_products' => $top_products ? $top_products : array(0)
            ]); 
    }

    public function addProductToCart(Request $request, $id)
    {
        // $request->session()->forget("cart");
        // $request->session()->flush();


        $prevCart = $request->session()->get('cart');
        $cart = new Cart($prevCart);

        $product = Product::collection("products")->where('id',"=",$id*1)->first();

        $qunatity = $request->input('qunatity');

        if($qunatity){

            $cart->addItem($id,$product,$qunatity);
            $cart->updatePriceAndQunatity();
    
            $request->session()->put('cart',$cart);
            
            return back()->withsuccess("Add product success");
            
        }else return back()->with('fail', 'Add product fail');

    }

    public function showCart(Request $request)
    {
        $cart = self::getCart($request);
        if($cart){
            if(Auth::check()){
                $address = DB::connection('mongodb')->collection("address")->where('user_id','=',Auth::user()->id*1)->orderBy('id','desc')->get();
                return view('cartproducts',$cart, [
                    'address' => $address
                    ]);
            }else{
                return view('cartproducts',$cart);
            }
        }else{
            // return redirect()->route('allProducts');
            // self::index();
            $products = Product::all();
            return view("allproducts", compact("products")); 
        }
    }

    public function deleteItemFromCart(Request $request, $id)
    {
        $cart = $request->session()->get("cart");

        if(array_key_exists($id,$cart->items)){
            unset($cart->items[$id]);
        }

        $prevCart = $request->session()->get("cart");
        $updateCart = new Cart($prevCart);
        $updateCart->updatePriceAndQunatity();
        $request->session()->put("cart", $updateCart);

        return back()->withsuccess("Delete product success");
    }

    public function getStore(Request $request)
    {
        $myselect = $request->input('myselect')*1;
        $pattern = $request->input('pattern');

        if(!$pattern) $pattern = 'table';
        if(!$myselect) $myselect = 5;

        $check = $request->input('categories_checked');
        if($check){
            $array_check = explode(",", $check);
            $categories_id = array();
            foreach($array_check as $to_int){
                array_push($categories_id, (int)$to_int);
            }
        }else{
            $array_check = [];
        }
        $top_products = self::top_sell();
 
        $cart = self::getCart($request);

        $categories = Product::collection("categories")
            ->select('*')
            ->get();

        $array_categories = array();
        foreach($categories as $item){
            $count = DB::connection('mongodb')->collection("products")->where('categorie_id','=',$item['id'])->count();
            array_push($array_categories, array($item,'count' => $count));
        }

        if($check){
            $products = Product::collection("products")
                ->select('*')
                ->wherein('categorie_id',$categories_id)
                ->orderBy('id','desc')
                ->paginate($myselect); 
        }else{
            $products = Product::collection("products")
                ->select('*')
                ->where('id','!=',0)
                ->orderBy('id','desc')
                ->paginate($myselect);
        }

        return view('store', $cart, [
            'array_categories' => $array_categories,
            'array_products' => $products,
            'categories_id' => $array_check,
            'myselect' => $myselect,
            'pattern' => $pattern,
            'top_products' => $top_products ? $top_products : array(0)
            ]);
    }

    public function chooseCategories(Request $request)
    {
        $myselect = $request->input('myselect')*1;
        $pattern = $request->input('pattern');
        if(!$pattern) $pattern = 'card';
        if(!$myselect) $myselect = 5;

        $check = $request->input('categories');
        if($check){
            $categories_id = array();
            foreach($request->input('categories') as $to_int){
                array_push($categories_id, (int)$to_int);
            }
        }else{
            return self::getStore($request);
        }

        $top_products = self::top_sell();

        $cart = self::getCart($request);

        $categories = Product::collection("categories")
            ->select('*')
            ->get();

        $array_categories = array();
        foreach($categories as $item){
            $count = DB::connection('mongodb')->collection("products")->where('categorie_id','=',$item['id'])->count();
            array_push($array_categories, array($item,'count' => $count));
        }

        $array_products = Product::collection("products")
            ->select('*')
            ->wherein('categorie_id',$categories_id)
            ->orderBy('id','desc')
            ->paginate($myselect); 
     
        return view('store', $cart, [
            'array_categories' => $array_categories,
            'array_products' => $array_products,
            'categories_id' => $categories_id,
            'myselect' => $myselect,
            'pattern' => $pattern,
            'top_products' => $top_products ? $top_products : array(0)
            ]);
    }

    public function choosePrice(Request $request)
    {
        $min_price = $request->input('price-min');
        $max_price = $request->input('price-max');

        $myselect = $request->input('myselect')*1;
        $pattern = $request->input('pattern');
        if(!$pattern) $pattern = 'card';
        if(!$myselect) $myselect = 5;

        $check = $request->input('categories');


        if($check){
            if(gettype($check) == 'string'){
                 $check = substr_replace($check, "", -1);
                $check = substr($check, 1);
                $array_check = explode(",",$check);

                $categories_id = array();
                foreach($array_check as $to_int){
                    array_push($categories_id, (int)$to_int);
                }
            }else{
                $categories_id = array();
                foreach($check as $to_int){
                    array_push($categories_id, (int)$to_int);
                }
            }
    
        }else{
            return self::getStore($request);
        }

        $top_products = self::top_sell();

        $cart = self::getCart($request);

        $categories = Product::collection("categories")
            ->select('*')
            ->get();

        $array_categories = array();
        foreach($categories as $item){
            $count = DB::connection('mongodb')->collection("products")->where('categorie_id','=',$item['id'])->count();
            array_push($array_categories, array($item,'count' => $count));
        }

        if($check){
            $products = Product::collection("products")
                ->wherein('categorie_id',$categories_id)
                ->where('price','>',$min_price*1.0)
                ->andWhere('price','<=',$max_price*1.0)
                ->orderBy('price','desc')
                ->paginate($myselect);
        }else{
            $products = Product::collection("products")
                ->where('price','>',$min_price*1.0)
                ->andWhere('price','<=',$max_price*1.0)
                ->orderBy('price','desc')
                ->paginate($myselect);
        }
        

        return view('store', $cart, [
            'array_categories' => $array_categories,
            'array_products' => $products,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'categories_id' => $check ? $categories_id : array(0), 
            'myselect' => $myselect,
            'pattern' => $pattern,
            'top_products' => $top_products ? $top_products : array(0)
            ]);
    }

    public function getProduct(Request $request, $id)
    {
        $prevCart = $request->session()->get("cart");
        $cart = new Cart($prevCart);
        $cart->updatePriceAndQunatity();

        $totalQuantity = $cart->totalQuantity;
        $totalPrice = $cart->totalPrice;
        $cartItems = $cart;

        $new_product = DB::connection('mongodb')->collection("products")->orderBy('id', 'desc')->take(4)->get();

        $product = DB::connection('mongodb')->collection("products")->where('id','=',$id*1)->first();

        $reviews = Order::collection('reviews')
            ->select('users.name as user_name', 'reviews.created_at as date','reviews.text as review','reviews.ratting as ratting','reviews.id')
            ->leftjoin('users','reviews.user_id','users.id')
            ->groupby('$selected')
            ->orderby('reviews.id','desc')
            ->where('reviews.product_id','=',$id*1)
            ->paginate(3);

        $review_count = DB::connection('mongodb')->collection("reviews")->where('product_id','=',$id*1)->count();

        $sum_ratting_product = DB::connection('mongodb')->collection("reviews")->where('product_id','=',$id*1)->sum('ratting');

        if($sum_ratting_product != 0){
            DB::connection('mongodb')->collection("products")
                ->where('id',"=",$id*1)
                ->update([
                    'ratting' => $sum_ratting_product/($review_count*5)*5,
                    ]);
        }

        $sum_ratting_product != 0 ? $ratting_product = $sum_ratting_product/($review_count*5)*5 : $ratting_product = 0;

        $review_count_star = array();
        for ($i=1; $i <= 5; $i++) { 
            $ratting = DB::connection('mongodb')->collection("reviews")->where('product_id','=',$id*1)->where('ratting','=',$i)->count();
            $ratting != 0 ?  $percen = ($ratting/$review_count)*100 : $percen = 0 ;
            array_push($review_count_star, array($ratting, $percen));
        }
   
        return view('product', [
            'product' => $product,
            'new_product' => $new_product,
            'totalQuantity'=> $totalQuantity,
            'totalPrice'=> $totalPrice,
            'cartItems' => $cartItems,
            'reviews' => $reviews,
            'review_count' => $review_count,
            'review_count_star' => $review_count_star,
            'ratting_product' => round($ratting_product,1)
            ]);
    }

    public function getCart($request)
    {
        $prevCart = $request->session()->get("cart");
        $cart = new Cart($prevCart);
        $cart->updatePriceAndQunatity();
        // $totalQuantity = 0;
        // $totalPrice = 0;

        $totalQuantity = $cart->totalQuantity;
        $totalPrice = $cart->totalPrice;

        return array('cartItems' => $cart, 'totalQuantity' => $totalQuantity, 'totalPrice' => $totalPrice,);
    }

    public function searchText(Request $request)
    {
        $search_text = $request->input('searchText');
        if($search_text){
            $products = Product::collection("products")
                ->select('products.id as id','products.name as name','products.price as price','products.image as image','categories.name as categorie_name','products.ratting as ratting')
                ->leftjoin('categories','products.categorie_id','categories.id')
                ->groupby('products.id','products.name','products.price','products.image','categories.name','products.ratting')
                ->where("products.name" , "like" , "%".$search_text."%" )
                ->get();
        }else{
            $products = Product::collection("products")
                ->select('products.id as id','products.name as name','products.price as price','products.image as image','categories.name as categorie_name','products.ratting as ratting')
                ->leftjoin('categories','products.categorie_id','categories.id')
                ->groupby('products.id','products.name','products.price','products.image','categories.name','products.ratting')
                ->get();
        }

        $ratting_products = Product::collection("products")
            ->select('products.id as id','products.name as name','products.price as price','products.image as image','categories.name as categorie_name','products.ratting as ratting')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->groupby('$selected')
            ->orderby('products.ratting','desc')
            ->limit(10)
            ->get();
        
        $top_products = self::top_sell();
    
        $cart = self::getCart($request);

        return view("allproducts", $cart, [
            'searchText' => 'true',
            'products' => $products,
            'ratting_products' => $ratting_products,
            'top_products' => $top_products
            ]); 
    }

    public function createOrder(Request $request)
    {
        $cart = Session::get('cart');

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $address = $request->input('address');
        $tel = $request->input('tel');
        $payment = $request->input('payment');

        if($request->input('new_address')){
            $address = $request->input('new_address');
        }

        if($address=='address_b'){
            $address = $request->input('address_mult');
        }

        if($request->input('create_account')=='on'){

            $validatedData = $request->validate([
                'email' => 'required|unique:users|email|max:255',
                'password' => 'required',
            ]);

            $new_user = array(
                'id' => User_nan::database()->collection("users")->getModifySequence('user_id'),
                'name' => $first_name." ".$last_name,
                'admin' => 0,
                'sale' => 0,
                'email' => $email,
                'image' => 'default.jpg',
                'status' => "offline",
                'password' => Hash::make($request->input('password')),
                'address' => array('address_a', $address),
                'phone' => $tel,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $save_user = User_nan::database()->collection("users")->insertGetId($new_user, 'id');

            $user_sale = User_nan::database()->collection("users")->select('id','name')->where("sale","=",1)->andwhere("status","=","online")->groupby('id','name')->random(1);

            if($user_sale==null){
                $user_sale = User_nan::database()->collection("users")->select('id','name')->where("sale","=",1)->groupby('id','name')->random(1);
            }
            
            $session = Session_model::database()->collection("sessions")->insertGetId([
                'id' => Session_model::database()->collection("sessions")->getModifySequence('sessions_id'),
                'user_id1' => $save_user[2]*1,
                'user_id2' => $user_sale[0]['id']*1,
                'unread' => "1,0",
                'reading' => 0
            ]);

            self::firshMessage($session, $user_sale[0]['id']);

            // if(!$save_user)  return redirect()->route('allProducts');
            if($cart){
                $date = date('Y-m-d H:i:s');
                $newOrderArray = array(
                    'id' => Order::database()->collection("orders")->getModifySequence('order_id'),
                    'status' => 'wait',
                    'status_payment' => 0,
                    'date' => date('Y-m-d'),
                    'del_date' => date('Y-m-d'),
                    'quantity' => $cart->totalQuantity*1.0,
                    'price' => $cart->totalPrice*1.0,
                    'user_id' => $save_user[2]*1,
                    'full_name' => null,
                    'email' => null,
                    'address' => $address,
                    'phone' => null,
                    'created_at' => $date,
                    'updated_at' => $date
                );
    
                $create_order = DB::connection('mongodb')->collection("orders")->insertGetId($newOrderArray);

                $get_order_id = json_encode($create_order, true);
                $get_order_id = substr($get_order_id, 9);
                $get_order_id = substr($get_order_id, 0,-2);
                
                $order_id = DB::connection('mongodb')->collection("orders")->select('id','user_id')->where('_id','=',$get_order_id)->first();

                $array_product_name = array();
    
                foreach($cart->items as $cart_item){

                    array_push($array_product_name, $cart_item['data'][0]['name']);

                    $item_id = $cart_item['data'][0]['id'];
                    $item_name = $cart_item['data'][0]['name'];
                    $item_quantity = $cart_item['quantity'];
                    $item_price = $cart_item['data'][0]['price'];
    
                    $newItemInCurrentOrder = array(
                        'id' => OrderItem::database()->collection("order_items")->getModifySequence('order_items_id'),
                        'product_id' => $item_id,
                        'order_id' => $order_id['id'],
                        'product_quantity' => $item_quantity,
                        'product_name' => $item_name,
                        'product_price' => ($item_price*1.0) * $item_quantity,
                        'created_at' => $date,
                        'updated_at' => $date
                    );
                    $create_order_items = DB::connection('mongodb')->collection("order_items")->insert($newItemInCurrentOrder);
                }
                
                // !..............................................................................
                if($payment=='credit_card'){

                    $random_str = self::random_str(3, 'abcdefghijklmnopqrstuvwxyz');
                    
                    $order_srt_id = "Order#".$order_id['id'];
                    $price_total = sprintf('%0.2f', $cart->totalPrice*1.0);
                    $payment_id = "SITTBP1".sprintf("%010d", $order_id['id']).$random_str;

                    $payment_for = "Screwshop ".$order_srt_id;
     
                    // ! รอแก้ stock เมื่อ จำนวน ที่ชื้อ มากกว่า จำนวน stock ที่มี

                    dd($cart);
                    return self::creditCard($order_srt_id,$price_total,$payment_id,$payment_for,$order_id['user_id']);
                }elseif($payment=='pay_pal'){
                    return redirect()->route('allProducts')->with('fail', 'Web page is unavailable.');
                }
                
            }else{
                return redirect()->route('allProducts');
            }

        }else{

            if($cart){
                
                $date = date('Y-m-d H:i:s');
                $newOrderArray = array(
                    'id' => Order::database()->collection("orders")->getModifySequence('order_id'),
                    'status' => 'wait',
                    'status_payment' => 0,
                    'date' => date('Y-m-d'),
                    'del_date' => date('Y-m-d'),
                    'quantity' => $cart->totalQuantity*1.0,
                    'price' => $cart->totalPrice*1.0,
                    'user_id' => Auth::check() ? Auth::user()->id : 'no',
                    'full_name' => Auth::check() ? null : $first_name." ".$last_name,
                    'email' => Auth::check() ? null : $email,
                    'address' => $address,
                    'phone' => Auth::check() ? null : $tel,
                    'created_at' => $date,
                    'updated_at' => $date
                );

                $create_order = DB::connection('mongodb')->collection("orders")->insertGetId($newOrderArray);

                $get_order_id = json_encode($create_order, true);
                $get_order_id = substr($get_order_id, 9);
                $get_order_id = substr($get_order_id, 0,-2);
                
                $order_id = DB::connection('mongodb')->collection("orders")->select('id','user_id')->where('_id','=',$get_order_id)->first();

                foreach($cart->items as $cart_item){

                    $item_id = $cart_item['data'][0]['id'];
                    $item_name = $cart_item['data'][0]['name'];
                    $item_quantity = $cart_item['quantity'];
                    $item_price = $cart_item['data'][0]['price'];

                    $newItemInCurrentOrder = array(
                        'id' => OrderItem::database()->collection("order_items")->getModifySequence('order_items_id'),
                        'product_id' => $item_id,
                        'order_id' => $order_id['id'],
                        'product_quantity' => $item_quantity*1,
                        'product_name' => $item_name,
                        'product_price' => ($item_price*1.0) * $item_quantity,
                        'created_at' => $date,
                        'updated_at' => $date
                    );
                    $create_order_items = DB::connection('mongodb')->collection("order_items")->insert($newItemInCurrentOrder);

                }
                
                // return redirect()->route('allProducts')->withsuccess("Thank For Choosing Us");
                // ----------------------------------------------------------------------------------------------------------------
      
                // !..............................................................................
                if($payment=='credit_card'){

                    $random_str = self::random_str(3, 'abcdefghijklmnopqrstuvwxyz');
                    
                    $order_srt_id = "Order#".$order_id['id'];
                    $price_total = sprintf('%0.2f', $cart->totalPrice*1.0);
                    $payment_id = "SITTBP1".sprintf("%010d", $order_id['id']).$random_str;
    
                    $payment_for = "Screwshop ".$order_srt_id;
 
                    // ! หน้า product ปุ่ม out by sell
                    $pass_cart = [];
                    foreach($cart->items as $item){
                        if($item['quantity'] > $item['data'][0]['stock']){
                            return back()->with('fail', $item['data'][0]['name'] . ' can not for sell');
                        }else{
                            return self::creditCard($order_srt_id,$price_total,$payment_id,$payment_for,$order_id['user_id']);
                        }
                    }

                    // return self::creditCard($order_srt_id,$price_total,$payment_id,$payment_for,$order_id['user_id']);
                }elseif($payment=='pay_pal'){
                    return redirect()->route('allProducts')->with('fail', 'Web page is unavailable.');
                }


            }else{
                return redirect()->route('allProducts');
            }
        }
    }

    public function reviewProduct(Request $request, $id)
    {
        $product_id = $id*1;
        $user_id = Auth::user()->id*1;
        $text = $request->input('text_review');
        $ratting = $request->input('rating')*1.0;
        $date = date('Y-m-d H:i:s');

        $newRevireArray = array(
            'id' => Review::database()->collection("reviews")->getModifySequence('reviews_id'),
            'product_id' => $product_id,
            'user_id' => $user_id,
            'text' => $text,
            'ratting' => $ratting,
            'created_at' => $date,
            'updated_at' => $date
        );
        $save = Review::database()->collection("reviews")->insert($newRevireArray);
        if($save){
            return redirect()->route('getProduct',['id' => $id]);
        }
    }

    public function creditCard($order_srt_id,$price_total,$payment_id,$product_name,$user_id)
    {
        $config = DB::connection('mongodb')->collection("config")->get();

        $payment_url = $config[3]['value'] ? $config[3]['value'] : \Config::get('adminConfig.payment.payment_url');
        $web_url = $config[4]['value'] ? $config[4]['value'] : \Config::get('adminConfig.payment.url_myweb');
        $web_currencycode = $config[5]['value'] ? $config[5]['value'] : \Config::get('adminConfig.payment.currencycode');
        $web_custip = $config[6]['value'] ? $config[6]['value'] : \Config::get('adminConfig.payment.custip');
        $web_custname = $config[7]['value'] ? $config[7]['value'] : \Config::get('adminConfig.payment.custname');
        $web_custemail = $config[8]['value'] ? $config[8]['value'] : \Config::get('adminConfig.payment.custemail');
        $web_custphone = $config[9]['value'] ? $config[9]['value'] : \Config::get('adminConfig.payment.custphone');
        $web_pagetimeout = $config[10]['value'] ? $config[10]['value'] : \Config::get('adminConfig.payment.pagetimeout');
  
        if(Auth::check()){
            $oid_user = Auth::id();
        }else{
            if($user_id!="no"){
                $oid = DB::connection('mongodb')->collection("users")->select('_id')->where('id','=',$user_id*1)->first();
                $get_order_id = json_encode($oid['_id'], true);
                $get_order_id2 = substr($get_order_id, 9);
                $get_order_id3 = substr($get_order_id2, 0,-2);
                $oid_user = $get_order_id3;
            }else{
                $oid_user = "no";
            }
        }

        $oid_user=="no" ? $encode_id_user = null : $encode_id_user = self::encode($oid_user,'tbp123');
        
        $encode = base64_encode($order_srt_id.''.$payment_id);
        $encode2 = self::encode($encode,'tbp123');

        $URL = $payment_url;
        $TransactionType = 'SALE';
        $PymtMethod = 'ANY';
        $OrderNumber = $order_srt_id;
        $PaymentDesc = $product_name;
        $ServiceID = 'SIT';
        $PaymentID = $payment_id;
        $MerchantReturnURL = $web_url . '/tbpapi/'.$encode2;
        $MerchantCallBackURL = $web_url;
        $Amount = $price_total;
        $CurrencyCode = $web_currencycode;
        $CustIP = $web_custip;
        $PageTimeout = $web_pagetimeout;
        $CustName = $web_custname;
        $CustEmail = $web_custemail;
        $CustPhone = $web_custphone;
        $MerchantTermsURL = $web_url;

        $HashValue =  hash('sha256', 'sit12345' . $ServiceID . $PaymentID . $MerchantReturnURL . $MerchantCallBackURL . $Amount . $CurrencyCode . $CustIP . $PageTimeout);
        
        return view('user.payment',[
                'URL' => $payment_url,
                'TransactionType' => 'SALE',
                'PymtMethod' => 'ANY',
                'OrderNumber' => $order_srt_id,
                'PaymentDesc' => $product_name,
                'ServiceID' => 'SIT',  
                'PaymentID' => $payment_id, 
                'MerchantReturnURL' => $web_url . '/tbpapi/' . $encode2,
                'MerchantCallBackURL' => $web_url,
                'Amount' => $price_total,
                'CurrencyCode' => $web_currencycode,
                'CustIP' => $CustIP,
                'PageTimeout' => $PageTimeout,
                'CustName' => $CustName,
                'CustEmail' => $CustEmail,
                'CustPhone' => $CustPhone,
                'MerchantTermsURL' => $web_url,
                'HashValue' => $HashValue,
                'Param6' => $encode_id_user
            ]);
    }

    public function paymentResponse(Request $request, $id)
    {
        $TransactionType = $request->input('TransactionType');
        $PymtMethod = $request->input('PymtMethod');
        $ServiceID = $request->input('ServiceID');
        $PaymentID = $request->input('PaymentID');
        $OrderNumber = $request->input('OrderNumber');
        $Amount = $request->input('Amount');
        $CurrencyCode = $request->input('CurrencyCode');
        $HashValue = $request->input('HashValue');
        $HashValue2 = $request->input('HashValue2');
        $TxnID = $request->input('TxnID');
        $IssuingBank = $request->input('IssuingBank');
        $TxnStatus = $request->input('TxnStatus');
        $AuthCode = $request->input('AuthCode');
        $BankRefNo = $request->input('BankRefNo');
        $TokenType = $request->input('TokenType');
        $Token = $request->input('Token');
        $RespTime = $request->input('RespTime');
        $TxnMessage = $request->input('TxnMessage');
        $CardNoMask = $request->input('CardNoMask');
        $CardHolder = $request->input('CardHolder');
        $CardType = $request->input('CardType');
        $CardExp = $request->input('CardExp');
        $Param6 = $request->input('Param6');

        $order_id = substr($OrderNumber,6);

        $array_payment = array(
            "id" => OrderItem::database()->collection("payment")->getModifySequence('payment_id'),
            "order_id" => $order_id*1,
            "transaction_type" => $TransactionType,
            "pymt_method" => $PymtMethod,
            "service_id" => $ServiceID,
            "payment_id" => $PaymentID,
            "order_number" => $OrderNumber,
            "amount" => $Amount,
            "currency_code" => $CurrencyCode,
            "hash_value" => $HashValue,
            "hash_value2" => $HashValue2,
            "txn_id" => $TxnID,
            "issuing_bank" => $IssuingBank,
            "txn_xtatus" => $TxnStatus,
            "auth_code" => $AuthCode,
            "bank_refNo" => $BankRefNo,
            "token_type" => $TokenType,
            "token" => $Token,
            "resp_time" => $RespTime,
            "txn_message" => $TxnMessage,
            "card_no_mask" => $CardNoMask,
            "card_holder" => $CardHolder,
            "card_type" => $CardType,
            "card_exp" => $CardExp,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
        );
        
        $create_payment = DB::connection('mongodb')->collection("payment")->insert($array_payment);

        if($create_payment){

            $order_id = DB::connection('mongodb')->collection("payment")->select('order_id')->orderBy('id','desc')->first();

            $update_status_in_order = DB::connection('mongodb')->collection("orders")
                ->where('id',"=",$order_id['order_id']*1)
                ->update([
                    'status_payment' => $TxnStatus*1==0 ? 1 : 0,
                    ]);

            $encode_order = str_rot13(base64_encode($order_id['order_id']*1));


            $order_item = DB::connection('mongodb')->collection("order_items")->where('order_id','=',$order_id['order_id']*1)->get();
            foreach($order_item as $item){
                $stock = DB::connection('mongodb')->collection("products")->select('stock')->where('id','=',$item['product_id']*1)->first();
                if(($item['product_quantity']*1) <= ($stock['stock']*1)){
                    $remaining = ($stock['stock']*1) - ($item['product_quantity']*1);
                    $update_stock = DB::connection('mongodb')->collection("products")->where('id','=',$item['product_id']*1)->update(['stock'=>$remaining]);
                }else{
                    $update_status_fail_at_orders = DB::connection('mongodb')->collection("orders")
                        ->where('id',"=",$order_id['order_id']*1)
                        ->update([
                            'status_payment' => 0,
                        ]);
                        
                    $update_status_fail_at_payment = DB::connection('mongodb')->collection("payment")
                        ->where('order_id',"=",$order_id['order_id']*1)
                        ->update([
                            'txn_xtatus' => 1,
                        ]);

                    $decode_test_id_user = self::decode($Param6,'tbp123');
                    Auth::loginUsingId($decode_test_id_user);
                    return back()->with('fail', 'Not enough product to buy');
                }
            }


            if(!Auth::check()){
                Session::forget('cart');
                // Session::flush();

                $decode_test_id_user = self::decode($Param6,'tbp123');
 
                Auth::loginUsingId($decode_test_id_user);

                return redirect()->route('allProducts')->withsuccess($TxnMessage." Code = ".$encode_order);
            }else{

                Session::forget('cart');

                return redirect()->route('allProducts')->withsuccess($TxnMessage." Code = ".$encode_order);
            }
        }
    }


    public function encode($value,$srt) {
        if (!$value) {
            return false;
        }
    
        $key = sha1($srt);
        $strLen = strlen($value);
        $keyLen = strlen($key);
        $j = 0;
        $crypttext = '';
    
        for ($i = 0; $i < $strLen; $i++) {
            $ordStr = ord(substr($value, $i, 1));
            if ($j == $keyLen) {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j++;
            $crypttext .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
        }
    
        return $crypttext;
    }
    
    
    public function decode($value,$srt) {
        if (!$value) {
            return false;
        }
    
        $key = sha1($srt);
        $strLen = strlen($value);
        $keyLen = strlen($key);
        $j = 0;
        $decrypttext = '';
    
        for ($i = 0; $i < $strLen; $i += 2) {
            $ordStr = hexdec(base_convert(strrev(substr($value, $i, 2)), 36, 16));
            if ($j == $keyLen) {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j++;
            $decrypttext .= chr($ordStr - $ordKey);
        }
    
        return $decrypttext;
    }

    public function random_str(
        int $length = 64,
        string $keyspace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public function checkOrder(Request $request)
    {
        $prevCart = $request->session()->get('cart');
 
        $cart = new Cart($prevCart);

        $totalQuantity = $cart->totalQuantity;
        $totalPrice = $cart->totalPrice;
        $cartItems = $cart;

        return view("checkoeder", [
            'totalQuantity' => $totalQuantity, 
            'cartItems' => $cartItems, 
            'totalPrice' => $totalPrice,
            ]); 
    }

    public function sendCheckOrder(Request $request)
    {

        $order = $request->input('order');

        $decode_order = base64_decode(str_rot13($order));

        if(is_numeric($decode_order)){
            $orders = Order::collection('orders')
                ->select('orders.id as id','orders.price as price','orders.status as status','orders.status_payment as status_payment','users.name as name','orders.full_name as full_name')
                ->leftjoin('users','orders.user_id','users.id')
                ->groupby('$selected')
                ->where('orders.id','=',$decode_order*1)
                ->orderBy('orders.id','desc')
                ->first();

            $array_orders = array();
            foreach($orders as $item){
                $order_items = OrderItem::collection('order_items')
                    ->select('order_items.id as id','order_items.product_name as product_name','order_items.product_quantity as product_quantity',
                    'order_items.product_price as product_price','products.image as image')
                    ->leftjoin('products','order_items.product_id','products.id')
                    ->leftjoin('orders','order_items.order_id','orders.id')
                    ->groupby('$selected')
                    ->where('order_items.order_id','=',$item['id']*1)
                    ->orderBy('order_items.id','asc')
                    ->get();
                array_push($array_orders, array('order' => $item, 'items' => $order_items));
            }
    
            // dd($order, $array_orders);
    
            $prevCart = $request->session()->get('cart');
     
            $cart = new Cart($prevCart);
    
            $totalQuantity = $cart->totalQuantity;
            $totalPrice = $cart->totalPrice;
            $cartItems = $cart;
    
            return view("checkoeder", [
                'totalQuantity' => $totalQuantity, 
                'cartItems' => $cartItems, 
                'totalPrice' => $totalPrice,
                'orders' => $orders,
                'array_orders' => $array_orders
                ]); 
        }else{
            $prevCart = $request->session()->get('cart');
     
            $cart = new Cart($prevCart);
    
            $totalQuantity = $cart->totalQuantity;
            $totalPrice = $cart->totalPrice;
            $cartItems = $cart;

            return view("checkoeder",[
                'totalQuantity' => $totalQuantity, 
                'cartItems' => $cartItems, 
                'totalPrice' => $totalPrice,
            ]);
        }

    }

    public function viewCart(Request $request)
    {
        $prevCart = $request->session()->get('cart');

        $cart = new Cart($prevCart);

        $totalQuantity = $cart->totalQuantity;
        $totalPrice = $cart->totalPrice;
        $cartItems = $cart;
        return view('viewcart', [
            'totalQuantity' => $totalQuantity,
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function increaseSingleProduct(Request $request, $id)
    {
        $prevCart = $request->session()->get('cart');
        $cart = new Cart($prevCart);

        $product = Product::collection("products")->where('id', "=", $id * 1)->first();
        
        foreach($cart->items as $item){
            $stock = Product::collection("products")->select('stock')->where('id', "=", $item['data'][0]['id'] * 1)->first();
            if(($item['quantity']*1) >= ($stock[0]['stock']*1)){
                return back()->with('fail', 'Not enough product to buy');
            }
        }
        
        $cart->addItem($id, $product, 1);
        $request->session()->put('cart', $cart);
        // return redirect()->route("getProduct");
        return back()->withsuccess("Plus product success.");
    }

    public function decreaseSingleProduct(Request $request, $id)
    {
        $prevCart = $request->session()->get('cart');
        $cart = new Cart($prevCart);

        if ($cart->items[$id]['quantity'] > 1) {
            $product = Product::collection("products")->where('id', "=", $id * 1)->first();
            $cart->items[$id]['quantity'] = $cart->items[$id]['quantity'] - 1;
            $cart->items[$id]['totalSinglePrice'] = $cart->items[$id]['quantity'] * $product[0]['price'];
            $cart->updatePriceAndQunatity();
            $request->session()->put('cart', $cart);
        }
        // return redirect()->route("cartproducts");
        return back()->withsuccess("Minus product success.");
    }

    public function firshMessage($session, $sale_id)
    {
        $config = DB::connection('mongodb')->collection("config")->where('config','=','first_messages')->first();

        if ($session) {
            $message_insert = Message::collection("messages")->insert(
                [
                    'id' => Message::collection("messages")->getModifySequence('id'),
                    "user_id" => $sale_id * 1,
                    "session" => $session[2] * 1,
                    "message" => $config['value'] ? $config['value'] : \Config::get('adminConfig.first_messages'),
                    "status" => 1,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s")
                ]
            );
        }
    }
}
