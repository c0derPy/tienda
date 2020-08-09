@extends('base')

@section('title', 'Welcome')

@section('navbar')
    @parent
@endsection

@section('content')

    <h1>Welcome to Tienda Evertec</h1>
    <hr>
    <a href="{{ url('order/create') }}"><button class="btn btn-info">Generate Order</button></a>

@endsection