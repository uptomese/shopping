<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Product;
use App\Categorie;
use App\Review;

class AdminProductsController extends Controller
{
    
    public function index()
    {
        $products = Product::collection('products')
            ->select('products.id as product_id','products.name as product_name', 'products.description as description', 'categories.name as categorie_name', 'products.price as price','products.image as image')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->groupby('products.id','products.name','products.description','products.image','products.price','products.categorie_id','products.created_at','products.updated_at','categories.name','products.image')
            ->orderby('products.id','desc')
            ->paginate(10);

        return view("admin.displayProducts", ['products' => $products]);
    }

    public function createProductForm()
    {
        $categories = Categorie::all();
        return view('admin.createProductForm', ['categories' => $categories]);
    }

    public function sendCreateProductForm(Request $request)
    {
        $details = $request->input("addmore");
        if($details){
            $array_details = array();
            foreach ($details as $key => $item) {
                array_push($array_details, $item);
            }
        }else $array_details = null;

        $name = $request->input('name');
        $description = $request->input('description');
        $categorie_id = $request->input('categorie_id');
        $price = $request->input('price')*1.0;

        $standard = $request->input('standard');
        $material = $request->input('material');
        $coating = $request->input('coating');
        $code = $request->input('code');
        $update = $request->input('update');
        $date = date_create($update);

        Validator::make($request->all(), ['image' => "required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();
        $ext = $request->file('image')->getClientOriginalExtension();
        $stringIameReFormat = str_replace(str_split('+,'), "", $request->input('name'));

        $imageName = $stringIameReFormat.".".$ext;
        $imageEncoded = File::get($request->image);
        Storage::disk('local')->put('public/product_images/'.$imageName, $imageEncoded);

        $newProductArray = array(
            'id' => Product::database()->collection("products")->getModifySequence('product_id'),
            'name' => $name,
            'description' => $description,
            'categorie_id' => $categorie_id*1,
            'price' => $price,
            'ratting' => 0.0,
            'details' => $array_details,
            'standard' => $standard,
            'material' => $material,
            'coating' => $coating,
            'code' => $code,
            'update' => date_format($date,"d-m-Y"),
            'image' => $imageName,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $save = Product::database()->collection("products")->insert($newProductArray);
        if($save){
            return redirect()->route('adminDisplayProducts');
        }else{
            return redirect()->route('adminDisplayProducts');
        }
    }

    public function editProductForm($id)
    {        
        $product = Product::collection('products')
            ->select('products.id as product_id','products.name as product_name','products.description as description','products.price as price'
            ,'products.categorie_id as categorie_id','products.standard as standard','products.material as material','products.coating as coating'
            ,'products.code as code','products.update as update','products.details as details')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->where('products.id','=',$id*1)
            ->groupby('$selected')
            ->first();

        $date = date_create($product[0]['update'] ?? '');
        $format_date = date_format($date,"Y-m-d");

        array_push($product, $format_date);

        $categories = Categorie::all();

        $reviews = Review::collection('reviews')
            ->select('reviews.id as id','reviews.product_id as product_id','reviews.user_id as user_id','reviews.text as text','reviews.ratting as ratting','reviews.created_at as created_at','users.name as name','users.image as image')
            ->leftjoin('users','reviews.user_id','users.id')
            ->where('reviews.product_id','=',$id*1)
            ->groupby('$selected')
            ->orderby('reviews.created_at','desc')
            ->paginate(10);

        return view('admin.editProductForm', [
            'product' => $product, 
            'categories' => $categories,
            'reviews' => $reviews
            ]);
    }

    public function editProductImageForm($id)
    {
        $product = Product::collection("products")->where('id',"=",$id*1)->first();
        // return $product;

        return view('admin.editProductImageForm', ['product' => $product]);
    }

    public function updateProductImage(Request $request, $id)
    {
        Validator::make($request->all(), ['image' => "required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();
        if($request->hasFile("image")){
            $product = Product::collection("products")->where('id',"=",$id*1)->first();
            $exists = Storage::disk('local')->exists("public/product_images/".$product[0]['image']);
            if($exists){
                Storage::delete('public/product_images/'.$product[0]['image']);
            }
            $ext = $request->file('image')->getClientOriginalExtension();
            $request->image->storeAs("public/product_images/",$product[0]['image']);
            $arrayToUpdate = array('image' => $product[0]['image']);
            DB::table('products')->where('id',$id)->update($arrayToUpdate);

            return redirect()->route('adminDisplayProducts');
        }else{
            $error = "No Image was Selected";
            return $error;
        }
    }

    public function updateProduct(Request $request, $id)
    {
        $details = $request->input("addmore");
        if($details){
            $array_details = array();
            foreach ($details as $key => $item) {
                array_push($array_details, $item);
            }
        }else $array_details = null;

        $name = $request->input('name');
        $description = $request->input('description');
        $categorie_id = $request->input('categorie_id');
        $price = $request->input('price');

        $standard = $request->input('standard');
        $material = $request->input('material');
        $coating = $request->input('coating');
        $code = $request->input('code');
        $update = $request->input('update');
        $date = date_create($update);

        $save = DB::connection('mongodb')->collection("products")
            ->where('id',"=",$id*1)
            ->update([
                'name' => $name,
                'description' => $description,
                'categorie_id' => $categorie_id*1,
                'price' => $price*1.0,
                'details' => $array_details,
                'standard' => $standard,
                'material' => $material,
                'coating' => $coating,
                'code' => $code,
                'update' => date_format($date,"d-m-Y"),
                'updated_at' => date('Y-m-d H:i:s')
                ]);

        if($save){
            return redirect()->route('adminDisplayProducts');
        }else{
            return redirect()->route('adminDisplayProducts');
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::collection("products")->where('id',"=",$id*1)->first();
        $exists = Storage::disk('local')->exists("public/product_images/".$product[0]['image']);
        if($exists){
            Storage::delete('public/product_images/'.$product[0]['image']);
        }
        $deleteresult = DB::connection('mongodb')->collection("products")->where("id","=",$id*1)->delete();
        return redirect()->route('adminDisplayProducts');
    }


    //-----------------------------------------------------------------------------------------------------------------
    public function indexCategories()
    {
        $categories = Categorie::collection('categories')->select('*')->where('id','!=',0)->orderby('id','desc')->paginate(10);

        $array_categories = array();
        foreach($categories->items as $item){
            $count = DB::connection('mongodb')->collection("products")->where('categorie_id','=',$item['id'])->count();
            array_push($array_categories, array($item,'count' => $count));
        }

        return view('admin.displayCategories', [
            'array_categories' => $array_categories,
            'categories' => $categories
            ]);
    }

    public function createCategorieForm(Request $request)
    {
        $categorie = $request->input('name');
        $newCategorieArray = array(
            'id' => Categorie::database()->collection("categories")->getModifySequence('categorie_id'),
            'name' => $categorie,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $save = Categorie::database()->collection("categories")->insert($newCategorieArray);
        if($save!=0){
            return redirect()->route('adminDisplayCategories');
        }else{
            return $save;
        }
    }

    public function deleteCategorie($id)
    {
        $deleteresult = DB::connection('mongodb')->collection("categories")->where('id','=',$id*1)->delete();
        if($deleteresult){
            return redirect()->route('adminDisplayCategories');
        }else return redirect()->route('adminDisplayCategories');
    }

    public function editCategorie($id)
    {
        $categories = Categorie::collection('categories')->select('*')->where('id','!=',0)->orderby('id','desc')->paginate(10);

        $array_categories = array();
        foreach($categories->items as $item){
            $count = DB::connection('mongodb')->collection("products")->where('categorie_id','=',$item['id'])->count();
            array_push($array_categories, array($item,'count' => $count));
        }

        $edit = Product::collection("categories")->where('id',"=",$id*1)->first();

        return view('admin.displayCategories', [
            'array_categories' => $array_categories,
            'categories' => $categories,
            'edit' => $edit
            ]);
    }

    public function updateCategorie(Request $request, $id)
    {
        $categorie_name = $request->input('name');
        $save = DB::connection('mongodb')->collection("categories")
            ->where('id',"=",$id*1)
            ->update([
                'name' => $categorie_name,
                'updated_at' => date('Y-m-d H:i:s')
                ]);

        if($save){
            return redirect()->route('adminDisplayCategories');
        }else return redirect()->route('adminDisplayCategories');
    }
}
