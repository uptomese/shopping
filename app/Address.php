<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Nantaburi\Mongodb\MongoNativeDriver\Model as NanModel ;

class Address extends NanModel
{
    protected $connection = "mongodb";

    protected  $database = "Abpon";

    protected  $collection = "address";  

    protected $schema = [
        'address' => [
            "id" => [
                'AutoInc' => true,
                // 'AutoIncStartwith' => 101,
                // 'Index' => true,
                'Unique' => true
            ],
            "user_id",
            "title" => [
                'Unique' => true
            ],
            "address",
            "created_at",
            "updated_at",
        ],
    ];
}
