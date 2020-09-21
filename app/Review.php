<?php

namespace App;

use Nantaburi\Mongodb\MongoNativeDriver\Model as NanModel ;
// use Illuminate\Database\Eloquent\Model;

class Review extends NanModel
{
    protected $connection = "mongodb";

    protected  $database = "Abpon";

    protected  $collection = "reviews";  

    protected $schema = [
        'reviews' => [
            "id" => [
                'AutoInc' => true,
                // 'AutoIncStartwith' => 101,
                // 'Index' => true,
                'Unique' => true
            ],
            "product_id",
            "user_id",
            "text",
            "ratting",
            "created_at",
            "updated_at",
        ],
    ];

}
