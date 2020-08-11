<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use GuzzleHttp\Client;


class OrderController extends Controller
{
    public function __construct(){
        $this->api_url = env('API_ENDPOINT');
        $this->api_login = env('API_LOGIN');
        $this->api_secretKey = env('API_SECRET_KEY');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return view('order.index', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource Order and Payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # Validations fields
        $validateData = $request->validate([
            'name' => 'required|max:80',
            'email' => 'required|max:120',
            'currency' => 'required|max:40',
            'description' => 'required|max:200',
            'currency' => 'required',
            'total' => 'required'
        ]);
        
        # Save Order
        $order = new Order;
        $order->customer_name = $request->get('name');
        $order->customer_email = $request->get('email');
        $order->customer_mobile = $request->get('mobile');
        $order->status = 'CREATED';
        $order->save();
        
        # Save payment
        $payment = new Payment();
        $payment->description = $request->get('description');
        $payment->reference = '5976030f5575d';
        $payment->currency = $request->get('currency');
        $payment->ip_address = '192.168.10.10';
        $payment->user_agent = 'fhaiber';
        $payment->total = $request->get('total');
        $payment->order_id = $order->id;
        $payment->save();

        return redirect(url('order', [$order->id]))->with(['status' => 'NEW']);
    }

    /**
     * Display the Order resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('order.detail', ['order' => $order]);
    }

    /**
     * Generate auth data for login API WebCheckout
     * @return array
     */
    public function auth_data(){

        $seed = date('c');
        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }
        
        $nonceBase64 = base64_encode($nonce);
        $secretKey = $this->api_secretKey;
        $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));
        $date_expiration = date('c', strtotime('+10 minute', strtotime($seed)));

        $body = ["auth" => [
                        "login" => $this->api_login,
                        "tranKey" => $tranKey,
                        "nonce" => $nonceBase64,
                        "seed" => $seed
                    ]   
                ];
                
        $arr_auth_data = ['auth' => $body, 
                          'date_expiration' => $date_expiration];

        return $arr_auth_data;
    }

    /**
     * Generate the entire body for payment request in API WebCheckout
     * @param Obj $order
     * @return json
     */
    public function generate_body_request($order){

        $seed = date('c');
        $auth_data = $this->auth_data()['auth']['auth'];
        $date_expiration = $this->auth_data()['date_expiration'];

        $return_url = url('order/webcheckout/finish', ['id' => $order->id]);
        $description = $order->payment->description;
        $currency = $order->payment->currency;
        $reference = $order->payment->reference;
        $total = $order->payment->total;
        $ip_address = $order->payment->ip_address;
        $user_agent = $order->payment->user_agent;

        $body = ["auth" => $auth_data,
                "payment" => [
                    "reference" => $reference,
                    "description" => $description,
                    "amount" => [
                            "currency" => $currency,
                            "total" => $total
                        ]
                ],
                "expiration" => $date_expiration,
                "returnUrl" => $return_url,
                "ipAddress" => $ip_address,
                "userAgent" => $user_agent
            ];

        return json_encode($body, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Make the request to webcheckout and redirect to the merchant PlacetoPay
     * @param int $order_id
     * @return redirect
     */
    public function webCheckout($order_id){

        $order = Order::findOrFail($order_id);
        $body_request = $this->generate_body_request($order);
        $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $request = $client->post($this->api_url, ["body" => $body_request]);
        $response = $request->getBody();
        $decode_response = json_decode($response);
        $processUrl = $decode_response->processUrl;
        $requestId = $decode_response->requestId;
        $order->payment->request_id = $requestId;
        $order->payment->save();

        return redirect($processUrl);
    }

    /** 
     * Shows the information of the payment made in the webcheckout
     * @param int $id
     * @return redirec
     * */    
    public function payment_finish($id){

        $order = Order::findOrFail($id);
        $requestId = $order->payment->request_id;
        $auth_data = $this->auth_data()['auth'];
        $body = json_encode($auth_data);
        
        $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $request = $client->post($this->api_url . $requestId, ["body" => $body]);
        $response = json_decode($request->getBody());

        $payment_status = $response->status->status;
        switch($payment_status){
            case "APPROVED":
                  $order->status = 'PAYED';
                  $order->save();
                  return redirect(url('order', ['id' => $order->id]))->with(['status' => 'APPROVED']);  
            break;

            case "PENDING":
                return redirect(url('order', ['id' => $order->id]))->with(['status' => 'PENDING']);
            break;

            case "REJECTED":
                $order->status = 'REJECTED';
                $order->save();
                return redirect(url('order/create'));
            break;

        }
    }

}
