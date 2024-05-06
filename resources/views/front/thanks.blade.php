@extends('front.layouts.app')
@section('content')
<section class="container">
    <div class="col-md-12 text-center py-5">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <h1>Thank You !</h1>
        <p>YOur Order Id is : <span class="text-success text-white p-1 bg-black ">{{ ($id) }}</span></p>
    </div>

</section>
@endsection
@section('customJs')
@endsection

