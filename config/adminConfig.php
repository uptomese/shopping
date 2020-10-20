<?php

return [

    //จำนวนวันที่ลบจลข้อความเก่า
    'delete_old_messages' => 60,

    //จำนวนที่จะแสดงข้อความออกมา ต้องมากกว่า messagesDisplayed ที่ config.js
    'limit_messages' => 50,

    'first_messages' => 'บริษัท T.B.P. Publication Co.,Ltd ยินดีให้บริการ',

    'payment' => [

        'payment_url' => 'https://test2pay.ghl.com/IPGSG/Payment.aspx',

        'url_myweb' => 'http://127.0.0.1:8000',
    
        'currencycode' => 'THB',

        'custip' => '127.0.0.1',
    
        'custname' => 'TBP test',
    
        'custemail' => 'sciant@gmail.com',
    
        'custphone' => '6627519274',

        'pagetimeout' => '780'

    ],

];