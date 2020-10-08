<?php

namespace App;

use Nantaburi\Mongodb\MongoNativeDriver\Model as NanModel ;
// use Jenssegers\Mongodb\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;

class Product extends NanModel
{
    protected $connection = "mongodb";

    protected $database = "Abpon" ;
 
    protected $collection = 'products';

    protected $schema = [
        'products' => [
            "id" => [
                'AutoInc' => true,
                // 'AutoIncStartwith' => 10,
                // 'Index' => true,
                'Unique' => true
            ],
            "name",
            "stock",
            "description",
            "standard",
            "material",
            "coating",
            "code",
            "update",
            "details",
            "image",
            "price",
            "ratting",
            "categorie_id",
            "created_at",
            "updated_at",
        ],
    ];

    public function getPriceAttribute($value)
    {
        $newForm = "$".$value;
        return $newForm;
    }


}
