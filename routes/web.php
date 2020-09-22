<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ["uses"=>"ProductsController@index", 'as' => 'allProducts']);

Route::get('/products', ["uses"=>"ProductsController@index", 'as' => 'allProducts']);

Route::get('/search', ["uses"=>"ProductsController@searchText", 'as' => 'searchText']);

Route::get('product/addToCart/{id}', ["uses"=>"ProductsController@addProductToCart", 'as' => 'AddToCartProduct']);

Route::get('/store', ["uses"=>"ProductsController@getStore", 'as' => 'allStore']);

Route::get('/choose_categories', ["uses"=>"ProductsController@chooseCategories", 'as' => 'chooseCategories']);

Route::get('/choose_price', ["uses"=>"ProductsController@choosePrice", 'as' => 'choosePrice']);

Route::get('/product/{id}', ["uses"=>"ProductsController@getProduct", 'as' => 'getProduct']);

//show cart items
Route::get('/cart', ["uses"=>"ProductsController@showCart", 'as' => 'cartproducts']);
//delete item from cart
Route::get('product/deleteItemFromCart/{id}', ["uses"=>"ProductsController@deleteItemFromCart", 'as' => 'DeleteItemFromCart']);

Route::post('create_order', ["uses"=>"ProductsController@createOrder", 'as' => 'createOrder']);

Route::post('/product/review/{id}', ["uses"=>"ProductsController@reviewProduct", 'as' => 'reviewProduct']);

Route::get('/check_order', ["uses"=>"ProductsController@checkOrder", 'as' => 'checkOrder']);

Route::post('/send_order', ["uses"=>"ProductsController@sendCheckOrder", 'as' => 'sendCheckOrder']);



Route::get('/user/profile/', ["uses"=>"UsersController@getProfile", 'as' => 'getProfile'])->middleware('auth');

Route::post('/user/update_profile/', ["uses"=>"UsersController@updateProfile", 'as' => 'upfateProfile'])->middleware('auth');

Route::post('/user/update_image_profile/', ["uses"=>"UsersController@updateImageProfile", 'as' => 'updateImageProfile'])->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//Admin panel
Route::get('admin/products', ["uses"=>"Admin\AdminProductsController@index", 'as' => 'adminDisplayProducts'])->middleware('restricToAdmin');

Route::get('admin/createProductForm', ["uses"=>"Admin\AdminProductsController@createProductForm", 'as' => 'adminCreateProductForm'])->middleware('restricToAdmin');
Route::post('admin/sendCreateProductForm', ["uses"=>"Admin\AdminProductsController@sendCreateProductForm", 'as' => 'adminSendCreateProductForm'])->middleware('restricToAdmin');

Route::get('admin/editProductForm/{id}', ["uses"=>"Admin\AdminProductsController@editProductForm", 'as' => 'adminEditProductForm'])->middleware('restricToAdmin');
Route::post('admin/updateProduct/{id}', ["uses"=>"Admin\AdminProductsController@updateProduct", 'as' => 'adminUpdateProductForm'])->middleware('restricToAdmin');

Route::get('admin/editProductImageForm/{id}', ["uses"=>"Admin\AdminProductsController@editProductImageForm", 'as' => 'adminEditProductImageForm'])->middleware('restricToAdmin');
Route::post('admin/updateImage/{id}', ["uses"=>"Admin\AdminProductsController@updateProductImage", 'as' => 'adminUpdateProductImageForm'])->middleware('restricToAdmin');

Route::get('admin/deleteProduct/{id}', ["uses"=>"Admin\AdminProductsController@deleteProduct", 'as' => 'adminDeleteProduct'])->middleware('restricToAdmin');

Route::get('admin/testStorage', function(){
    // return "<img src=".Storage::url('product_images/saw.jpg').">";
    // return Storage::disk('local')->url('product_images/saw.jpg');
    // print_r(Storage::disk("local")->exists("public/product_images/saw.jpg"));
    // Storage::delete('public/product_images/saw.jpg');
});

Route::get('admin/categories', ["uses"=>"Admin\AdminProductsController@indexCategories", 'as' => 'adminDisplayCategories'])->middleware('restricToAdmin');
Route::post('admin/createCategorieForm', ["uses"=>"Admin\AdminProductsController@createCategorieForm", 'as' => 'adminCreateCategorieForm'])->middleware('restricToAdmin');

Route::get('admin/editCategorieForm/{id}', ["uses"=>"Admin\AdminProductsController@editCategorie", 'as' => 'adminEditCategorieForm'])->middleware('restricToAdmin');
Route::post('admin/updateCategorie/{id}', ["uses"=>"Admin\AdminProductsController@updateCategorie", 'as' => 'adminUpdateCategorieForm'])->middleware('restricToAdmin');

Route::get('admin/deleteCategorie/{id}', ["uses"=>"Admin\AdminProductsController@deleteCategorie", 'as' => 'adminDeleteCategorie'])->middleware('restricToAdmin');

Route::get('admin/orders', ["uses"=>"Admin\AdminOrdersController@index", 'as' => 'adminDisplayOrders'])->middleware('restricToAdmin');

Route::get('admin/order/{id}', ["uses"=>"Admin\AdminOrdersController@showOrder", 'as' => 'adminShowOrder'])->middleware('restricToAdmin');

Route::get('admin/update_order_success/{id}', ["uses"=>"Admin\AdminOrdersController@updateOrderSuccess", 'as' => 'updateOrderSuccess'])->middleware('restricToAdmin');
Route::get('admin/update_order_wait/{id}', ["uses"=>"Admin\AdminOrdersController@updateOrderWait", 'as' => 'updateOrderWait'])->middleware('restricToAdmin');

Route::get('admin/users', ["uses"=>"Admin\AdminUsersController@index", 'as' => 'getUsers'])->middleware('restricToAdmin');

Route::get('admin/create_user', ["uses"=>"Admin\AdminUsersController@createUser", 'as' => 'createUser'])->middleware('restricToAdmin');

Route::post('admin/created_user', ["uses"=>"Admin\AdminUsersController@createdUser", 'as' => 'createdUser'])->middleware('restricToAdmin');

Route::get('admin/update_user/{id}', ["uses"=>"Admin\AdminUsersController@updateUser", 'as' => 'updateUser'])->middleware('restricToAdmin');
Route::post('admin/updated_user/{id}', ["uses"=>"Admin\AdminUsersController@updatedUser", 'as' => 'updatedUser'])->middleware('restricToAdmin');


Route::post('tbpapi/{id}', ["uses"=>"ProductsController@paymentResponse", 'as' => 'paymentResponse']);



Route::post('reset_password_without_token', 'AccountsController@validatePasswordRequest');
Route::post('reset_password_with_token', 'AccountsController@resetPassword');