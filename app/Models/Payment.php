<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    public $table = 'payments';

    public $fillable = [
        'id',
        'description',
        'reference',
        'request_id',
        'currency',
        'ip_address',
        'user_agent',
        'total',
        'order_id'
    ];

    public function order(){
        return $this->belognsTo('App\Models\Order');
    }    
}
