@extends('base')

@section('title', 'Order')

@section('navbar')
    @parent
@endsection

@section('content')

    <h1>Orders</h1>
    <hr>
    <a href="{{ url('order/create') }}"><button class="btn btn-info">Generate Order</button></a>
    <br><br>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <br>
    <table class="table">
        <thead>
            <tr>
                <td>Name</td>
                <td>Email</td>
                <td>Mobile</td>
                <td>Estatus</td>
                <td>Date</td>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{!! $order->customer_name !!}</td>
                    <td>{!! $order->customer_email !!}</td>
                    <td>{!! $order->customer_mobile !!}</td>
                    <td>{!! $order->status !!}</td>
                    <td>{!! $order->created_at !!}</td>
                </tr> 
            @endforeach          
        </tbody>
    </table>

@endsection