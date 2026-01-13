@extends('front.layouts.app')

@section('content')

<main>

    <!-- Breadcrumb -->
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item">
                        <a class="white-text" href="{{ route('front.home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Thank You</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Thank You -->
    <section class="section-9 pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card shadow-lg border-0 text-center p-5">

                        <div class="mb-4">
                            <i class="fa fa-check-circle text-success" style="font-size:70px"></i>
                        </div>

                        <h2 class="mb-3">Thank You for Your Order!</h2>

                        <p class="mb-4 text-muted">
                            Your order has been placed successfully.
                            <br>
                            We will contact you soon for delivery.
                        </p>

                        <div class="d-flex justify-content-center gap-3">

                            <a href="{{ route('front.home') }}" class="btn btn-dark px-4">
                                Continue Shopping
                            </a>

                            <a href="{{ route('front.orderSummery',$order_id) }}" class="btn btn-outline-dark px-4">
                                View Orders
                            </a>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

</main>

@endsection