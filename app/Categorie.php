<?php

namespace App;

use Nantaburi\Mongodb\MongoNativeDriver\Model as NanModel ;
// use Illuminate\Database\Eloquent\Model;

class Categorie extends NanModel
{
    protected $connection = "mongodb";

    protected  $database = "Abpon" ;

    protected  $collection = "categories" ;

    protected $schema = [
        'categories' => [
            "id" => [
                'AutoInc' => true,
                // 'AutoIncStartwith' => 10,
                // 'Index' => true,
                'Unique' => true
            ],
            "name",
            "created_at",
            "updated_at",
        ],
    ];

}
