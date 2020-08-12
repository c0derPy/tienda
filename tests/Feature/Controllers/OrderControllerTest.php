<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/order');

        $response->assertStatus(200);

        $orders = \App\Models\Order::all();
        $response->assertViewIs('order.index');
        $response->assertViewHas('orders', $orders);
    }

    public function testCreate(){
        $response = $this->get('/order/create');
        $response->assertStatus(200);
    }

    public function testStorage(){

        $response = $this->post('/order', [
            'customer_name' => 'bar',
            'customer_email' =>  'foo@gmail.com',
            'customer_mobile' => '3182298741',
            'status' => 'CREATED',
            'description' => 'Pago prueba',
            'reference' =>  '788844456',
            'currency' => 'COP',
            'ip_address' => '192.168.10.10',
            'user_agent' => 'Fhaiber',
            'total' => '2000000'
        ]);
        $response->assertStatus(302);   
        $order = \App\Models\Order::where(['customer_name' => 'bar',
                                           'customer_email' =>  'foo@gmail.com',
                                           'customer_mobile' => '3182298741',
                                           'status' => 'CREATED'])->first();

        $response->assertRedirect('/order/'.$order->id);
    }

}
