@extends('base')

@section('title', 'Welcome')

@section('navbar')
    @parent
@endsection

@section('content')

    <h1>Order detail</h1>
    <hr>
    <br><br>
    <table class="table">
        <thead>
            <tr><td colspan="2"><b>Resume Order</b></td></tr>
        </thead>
        <tbody>
            <tr>
                <td>name:</td><td>{!! $order->customer_name !!}</td>
            </tr>
            <tr>
                <td>email:</td><td>{!! $order->customer_email !!}</td>
            </tr>
            <tr>
                <td>mobile:</td><td>{!! $order->customer_mobile !!}</td>
            </tr>
            <tr>
                <td>status:</td><td>{!! $order->status !!}</td>
            </tr>
        </tbody>
    </table>
    @if (session('status') == 'NEW')
        <a href="{{ url('order/webcheckout', ['id' => $order->id]) }}"><button class="btn btn-success">Continue</button></a>
    @endif

    @if (session('status') == 'PENDING')
        <a href="{{ url('order/webcheckout', ['id' => $order->id]) }}"><button class="btn btn-success">Continue</button></a>
    @endif

    @if (session('status') == 'APPROVED')
        <a href="{{ url('/') }}"><button class="btn btn-info">Home</button></a>
    @endif

@endsection