@extends('base')

@section('title', 'Order')

@section('navbar')
    @parent
@endsection

@section('content')

    <h1>Order</h1>
    <hr>

    <form action="{{ url('order') }}" method="POST">
        @csrf
        @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
        @endif
        <p>
            <label for="">Name:</label><br>
            <input type="text" name="name" id="" class="form-control form-control-sm" maxlength="80">
        </p>
        <p>
            <label for="">Email:</label><br>
            <input type="email" name="email" id="" class="form-control form-control-sm" maxlength="120">
        </p>
        <p>
            <label for="">Mobile:</label><br>
            <input type="text" name="mobile" id="" class="form-control form-control-sm" maxlength="40">
        </p>
        <p>
            <label for="">Description:</label><br>
            <input type="text" name="description" id="" class="form-control form-control-sm" value="Cicla Turismo / Promocion 50% OFF" readonly>
        </p>
        <div class="form-row">
            <div class="col">
                <label for="">currency:</label><br>
                <input type="text" name="currency" id="" class="form-control form-control-sm" value="COP" readonly>
            </div>
            <div class="col">
                <label for="">Total:</label><br>
                <input type="text" name="total" id="" class="form-control form-control-sm" value="1000000" readonly>
            </div>    
        </div>
        <br>
        <p>
            <input type="submit" name="" id="" class="btn btn-success" value="Continue..">
        </p>    
    </form>

@endsection