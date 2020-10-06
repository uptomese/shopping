<?php

namespace App;

use Nantaburi\Mongodb\MongoNativeDriver\Model as NanModel ;
// use Illuminate\Database\Eloquent\Model;

class Order extends NanModel
{
    protected $connection = "mongodb";

    protected  $database = "Abpon" ;

    protected  $collection = "orders" ;  

    protected $schema = [
        'orders' => [
            "id" => [
                'AutoInc' => true,
                // 'AutoIncStartwith' => 10,
                // 'Index' => true,
                'Unique' => true
            ],
            "date",
            "status",
            "status_payment",
            "del_date",
            "price",
            "created_at",
            "updated_at",
        ],
    ];

}
