@extends('front.layouts.app')


@section('content')

<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <form action="{{ route('front.processCheckout') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">
                                    @foreach($shippingAddresses as $shippingAddress)
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-check">
                                                <input @if ($loop->first)
                                                checked
                                                @endif name="saved_address" class="form-check-input" type="radio"
                                                value="{{ $shippingAddress->id}}" id="saved_address" data-id="{{
                                                $shippingAddress->country_id }}">
                                                @php

                                                @endphp
                                                <div>
                                                    {{ $shippingAddress->first_name . ' '.
                                                    $shippingAddress->last_name}}<br>
                                                    {{ $shippingAddress->address}},<br>
                                                    {{ $shippingAddress->phone}}<br>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="#" class="btn btn-danger btn-sm"> Delete Address</a>
                                            </div>
                                        </div>

                                    </div>
                                    @endforeach
                                    <div class="col-md-12">

                                        <div class="form-check mt-2">
                                            <input name="saved_address" class="form-check-input" type="radio"
                                                value="new" id="new_address" {{( old('saved_address')==='new' ||
                                                empty($shippingAddress)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="new_address">
                                                Use new address
                                            </label>
                                        </div>

                                    </div>


                                    <div id="address_area" @class([ 'row' , 'd-none'=>
                                        $shippingAddresses->count() > 0 &&
                                        old('saved_address') !== 'new' &&
                                        !$errors->any()
                                        ])>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="first_name" id="first_name"
                                                    class="form-control @error('first_name') is-invalid @enderror"
                                                    placeholder="First Name" value="{{ old('first_name') }}">
                                                @error('first_name')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="last_name" id="last_name"
                                                    class="form-control @error('last_name') is-invalid @enderror"
                                                    placeholder="Last Name" value="{{ old('last_name') }}">
                                                @error('last_name')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="email" id="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="Email" value="{{ old('email') }}">
                                                @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="phone" id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    placeholder="Mobile No." value="{{ old('phone') }}">
                                                @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <select name="country_id" id="country"
                                                    class="form-control @error('country_id') is-invalid @enderror">
                                                    <option value="">Select a Country</option>
                                                    @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ old('country_id')==$country->
                                                        id ?
                                                        'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('country_id')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <textarea name="address" id="address" cols="30" rows="3"
                                                    placeholder="Address"
                                                    class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                                                @error('address')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="apartment" id="apartment"
                                                    class="form-control @error('apartment') is-invalid @enderror"
                                                    placeholder="Apartment, suite, unit, etc. (optional)"
                                                    value="{{ old('apartment') }}">
                                                @error('apartment')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="text" name="city" id="city"
                                                    class="form-control @error('city') is-invalid @enderror"
                                                    placeholder="City" value="{{ old('city') }}">
                                                @error('city')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="text" name="state" id="state"
                                                    class="form-control @error('state') is-invalid @enderror"
                                                    placeholder="State" value="{{ old('state') }}">
                                                @error('state')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="text" name="zip" id="zip"
                                                    class="form-control @error('zip') is-invalid @enderror"
                                                    placeholder="Zip" value="{{ old('zip') }}">
                                                @error('zip')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2"
                                                placeholder="Order Notes (optional)"
                                                class="form-control @error('order_notes') is-invalid @enderror">{{ old('order_notes') }}</textarea>
                                            @error('order_notes')
                                            <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h2>
                        </div>

                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                    <div class="h6">₹{{ number_format($item->price,2) }}</div>
                                </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>₹{{ Cart::subtotal() }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong>₹ <span id="shippingAmount">0</span></strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5">
                                        <strong>₹<span id="totalAmount">{{ Cart::subtotal() }}</span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card payment-form">
                            <h3 class="card-title h5 mb-3">Payment Method</h3>

                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input name="payment_method" class="form-check-input" type="radio" value="cod"
                                        id="payment_method_cod" {{ old('payment_method','cod')=='cod' ? 'checked' : ''
                                        }}>
                                    <label class="form-check-label" for="payment_method"> COD </label>
                                </div>

                                <div class="form-check">
                                    <input name="payment_method" class="form-check-input" type="radio" value="stripe"
                                        id="payment_method_stripe" {{ old('payment_method')=='stripe' ? 'checked' : ''
                                        }}>
                                    <label class="form-check-label" for="payment_method"> Stripe </label>
                                </div>
                            </div>

                            @error('payment_method')
                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror

                            <div class="card-body p-0 mt-4 d-none" id="cart_payment_form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number"
                                        class="form-control @error('card_number') is-invalid @enderror"
                                        placeholder="Valid Card Number" value="{{ old('card_number') }}">
                                    @error('card_number')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date"
                                            class="form-control @error('expiry_date') is-invalid @enderror"
                                            placeholder="MM/YYYY" value="{{ old('expiry_date') }}">
                                        @error('expiry_date')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="mb-2">CVV Code</label>
                                        <input type="text" name="cvv" id="cvv"
                                            class="form-control @error('cvv') is-invalid @enderror" placeholder="123"
                                            value="{{ old('cvv') }}">
                                        @error('cvv')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </section>
</main>

@endsection
@push('customJs')
<script>
    $(document).ready(function(){

$('#payment_method_cod').on('click',function(){
    $('#cart_payment_form').addClass('d-none');
});

$('#payment_method_stripe').on('click',function(){
    $('#cart_payment_form').removeClass('d-none');
});

$('#new_address').on('click',function(){
    $('#address_area').removeClass('d-none');
});

$('#saved_address').on('click',function(){
    $('#address_area').addClass('d-none');
});


/* Subtotal (force number) */
let subtotal = parseFloat("{{ Cart::subtotal(2,'.','') }}");

/* Saved address change */
$('input[name="saved_address"]').on('change',function(){

    let countryId = $(this).data('id');
    if(countryId){
        getShipping(countryId);
    }
});

/* New address country */
$('#country').on('change',function(){
    let countryId = $(this).val();
    getShipping(countryId);
});

/* Fetch shipping */
function getShipping(countryId){

    $.ajax({
        url : "{{ route('front.getShipping') }}",
        type: "GET",
        data:{ country_id: countryId },
        success:function(res){

            let shipping = parseFloat(res.shipping) || 0;
            let total = subtotal + shipping;

            $('#shippingAmount').text(shipping.toFixed(2));
            $('#totalAmount').text(total.toFixed(2));
        }
    });
}

/* Load default */
let defaultCountry = $('input[name="saved_address"]:checked').data('id');
if(defaultCountry){
    getShipping(defaultCountry);
}

});
</script>
@endpush