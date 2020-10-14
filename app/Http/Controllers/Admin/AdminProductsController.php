<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\File as imageFile;

use App\Product;
use App\Categorie;
use App\Review;

class AdminProductsController extends Controller
{
    
    public function index()
    {
        $products = Product::collection('products')
            ->select('products.id as product_id','products.name as product_name', 'products.description as description', 'categories.name as categorie_name', 'products.price as price','products.image as image' ,'products.stock as stock')
            ->leftjoin('categories','products.categorie_id','categories.id')
            ->groupby('$selected')
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

        $stock = $request->input('stock');
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

        Validator::make($request->all(), ['image.*' => "required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();

        $id_new_product = Product::database()->collection("products")->getModifySequence('product_id');

        if($request->hasFile('image'))
        {
            $test = Storage::disk('local')->makeDirectory('public/product_images/'.$id_new_product);
            $names = [];
            foreach($request->file('image') as $image)
            {
                $imageName = $image->getClientOriginalName();
                $imageEncoded = File::get($image);
                Storage::disk('local')->put('public/product_images/'.$id_new_product.'/'.$imageName, $imageEncoded);
                array_push($names, $imageName);          
            }
        }

        $newProductArray = array(
            'id' => $id_new_product,
            'name' => $name,
            'stock' => $stock*1,
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
            'image' => $names,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $save = Product::database()->collection("products")->insert($newProductArray);
        if($save){
            return redirect()->route('adminDisplayProducts')->withsuccess('Product created successfully');
        }else{
            return redirect()->route('adminDisplayProducts')->with('fail', 'Product created failed');
        }
    }

    public function editProductForm($id)
    {        
        $product = Product::collection('products')
            ->select('products.id as product_id','products.name as product_name','products.description as description','products.price as price'
            ,'products.categorie_id as categorie_id','products.standard as standard','products.material as material','products.coating as coating'
            ,'products.code as code','products.update as update','products.details as details', 'products.stock as stock')
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

    public function addProductImage(Request $request, $id)
    {
        Validator::make($request->all(), ['image' => "required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();

        if($request->hasFile("image")){

            $imageName = $request->file('image')->getClientOriginalName();

            $imageEncoded = File::get($request->file('image'));
                
            Storage::disk('local')->put('public/product_images/'.$id.'/'.$imageName, $imageEncoded);

            $all_images = DB::connection('mongodb')->collection("products")->select('image')->where('id','=',$id*1)->first();

            array_push($all_images['image'], $imageName);

            DB::table('products')->where('id',$id*1)->update(['image' => $all_images['image']]);

            return back()->withsuccess('Image Product add successfully');

        }else{
            return back()->with('fail', 'Image Product add fail');
        }
    }

    public function deleteImage(Request $request, $id, $index)
    {
        $all_images = DB::connection('mongodb')->collection("products")->select('image')->where('id','=',$id*1)->first();

        $image_del = $all_images['image'][$index];

        $exists = Storage::disk('local')->exists("public/product_images/".$id.'/'.$image_del);

        if($exists){

            Storage::delete('public/product_images/'.$id.'/'.$image_del);

            unset($all_images['image'][$index]);

            DB::table('products')->where('id',$id*1)->update(['image' => $all_images['image']]);

            return back()->withsuccess('Image Product delete successfully');
        }else{
            return back()->with('fail', 'Image Product delete fail');
        }

    }

    public function updateProductImage(Request $request, $id)
    {
        $input_image = $request->input("type_image");

        Validator::make($request->all(), ['image' => "required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();

        if($input_image=="image_a" && $request->hasFile("image")){

            $product = Product::collection("products")->where('id',"=",$id*1)->first();
            $exists = Storage::disk('local')->exists("public/product_images/".$product[0]['image']);
            if($exists){
                Storage::delete('public/product_images/'.$product[0]['image']);
            }
            $ext = $request->file('image')->getClientOriginalExtension();
            $request->image->storeAs("public/product_images/",$product[0]['image']);
            $arrayToUpdate = array('image' => $product[0]['image']);
            DB::table('products')->where('id',$id)->update($arrayToUpdate);

            return redirect()->route('adminDisplayProducts')->withsuccess('Image Product updated successfully');

        }else{
            if($request->hasFile("image")){

                $index = $request->input('index');
                $file_name = $request->input('file_name');
                $product = Product::collection("products")->where('id',"=",$id*1)->first();

                
                $exists = Storage::disk('local')->exists("public/product_images/".$id.'/'.$file_name);

                if($exists){
                    Storage::delete('public/product_images/'.$id.'/'.$file_name);
                }

                $imageName = $request->file('image')->getClientOriginalName();
                $imageEncoded = File::get($request->file('image'));
                
                Storage::disk('local')->put('public/product_images/'.$id.'/'.$imageName, $imageEncoded);

                $array_file_name = DB::connection('mongodb')->collection("products")->select('image')->where('id','=',$id*1)->first();

                foreach ($array_file_name['image'] as $key => $item) {
                    if($key==$index){
                        $array_file_name['image'][$key] = $imageName;
                    }
                 }

                DB::table('products')->where('id',$id*1)->update(['image' => $array_file_name['image']]);

                return back()->withsuccess('Image Product updated successfully');

            }else{
                
                return back()->with('fail', 'No Image was Selected');
            }
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

        $stock = $request->input('stock');
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
                'stock' => $stock*1,
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
            return redirect()->route('adminDisplayProducts')->withsuccess('Product updated successfully');
        }else{
            return redirect()->route('adminDisplayProducts')->with('fail', 'Product updated failed');
        }
    }

    public function deleteProduct($id)
    {
        
        $product = Product::collection("products")->where('id',"=",$id*1)->first();

        $exists = Storage::disk('local')->exists('public/product_images/'.$id);

        if(gettype($product[0]['image']) == "array"){

            if($exists){
    
                Storage::disk('local')->deleteDirectory('public/product_images/'.$id);
    
                DB::connection('mongodb')->collection("products")->where("id","=",$id*1)->delete();
    
                return back()->withsuccess('Product deleted successfully');
    
            }else{
    
            return back()->with('fail', 'Product deleted failed');

            }


        }else{

            $exists = Storage::disk('local')->exists("public/product_images/".$product[0]['image']);

            if($exists){

                Storage::delete('public/product_images/'.$product[0]['image']);
            }

            $deleteresult = DB::connection('mongodb')->collection("products")->where("id","=",$id*1)->delete();

            return back()->withsuccess('Product deleted successfully');

        }

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
        if($save){
            return redirect()->route('adminDisplayCategories')->withsuccess('Categorie created successfully');
        }else{
            return redirect()->route('adminDisplayCategories')->with('fail', 'Categorie created failed');
        }
    }

    public function deleteCategorie($id)
    {
        $deleteresult = DB::connection('mongodb')->collection("categories")->where('id','=',$id*1)->delete();
        if($deleteresult){
            return redirect()->route('adminDisplayCategories')->withsuccess('Categorie deleted successfully');
        }else return redirect()->route('adminDisplayCategories')->with('fail', 'Categorie deleted failed');
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
            return redirect()->route('adminDisplayCategories')->withsuccess('Categorie updated successfully');
        }else return redirect()->route('adminDisplayCategories')->with('fail', 'Categorie updated failed');
    }
}
