<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;

class Session_jsg extends Model
{
    protected $collection = "sessions";
    protected $database = "Abpon";

    protected $fillable = ['user_id1', 'user_id2', 'unread', 'reading'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
