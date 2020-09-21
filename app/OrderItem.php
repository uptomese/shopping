<?php

namespace App;

use Nantaburi\Mongodb\MongoNativeDriver\Model as NanModel ;
// use Illuminate\Database\Eloquent\Model;

class OrderItem extends NanModel
{
    protected $connection = "mongodb";

    protected  $database = "Abpon" ;

    protected  $collection = "order_items" ;  

    protected $schema = [
        'order_items' => [
            "id" => [
                'AutoInc' => true,
                // 'AutoIncStartwith' => 10,
                // 'Index' => true,
                'Unique' => true
            ],
            "product_id",
            "order_id",
            "product_quantity",
            "product_price",
            "created_at",
            "updated_at",
        ],
    ];
}
