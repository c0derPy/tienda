@extends('base')

@section('title', 'Order')

@section('navbar')
    @parent
@endsection

@section('content')

    <h1>Order</h1>
    <hr>
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
        <input type="text" name="" id="mobile" class="form-control form-control-sm" maxlength="40">
    </p>
    <p>
        <label for="">Description:</label><br>
        <input type="text" name="description" id="" class="form-control form-control-sm" value="Cicla Turismo / Promocion 50% OFF" readonly>
    </p>
    <div class="form-row">
        <div class="col">
            <label for="">Moneda:</label><br>
            <input type="text" name="money" id="" class="form-control form-control-sm" value="COP" readonly>
        </div>
        <div class="col">
            <label for="">Total:</label><br>
            <input type="text" name="total" id="" class="form-control form-control-sm" value="200.000" readonly>
        </div>    
    </div>
    <br>
    <p>
        <input type="submit" name="" id="" class="btn btn-success" value="Continuar..">
    </p>    

@endsection