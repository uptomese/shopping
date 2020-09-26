<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model_jsg;

class Message_jsg extends Model_jsg
{
    protected $collection = "messages";
    protected $database = "Abpon";
}
