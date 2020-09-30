<?php

namespace App;

use Nantaburi\Mongodb\MongoNativeDriver\Model ;
// use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $connection = "mongodb";

    protected $database = "Abpon";

    protected $collection = "sessions";
    
    protected $schema = [
            'sessions' => [
                "id" => [
                    'AutoInc' => true,
                    // 'AutoIncStartwith' => 10,
                    // 'Index' => true,
                    'Unique' => true
                ],
                "user_id1",
                "user_id2",
                "unread",
                "reading"
            ],
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
