<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'orders';

    public $fillable = [
        'id',
        'customer_name',
        'customer_email',
        'customer_mobile',
        'status'
    ];

}
