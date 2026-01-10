@extends('front.layouts.app')


@section('content')
<main>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8 cart-wrapper">
                    @if($cartContent->isEmpty())
                    <div class="card w-100 h-100 text-center shadow">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center ">
                            <h4 class="card-title mb-2">Your cart is empty</h4>
                            <p class="text-muted mb-4">
                                Looks like you haven’t added anything to your cart yet.
                            </p>
                            <a href="{{ route('front.shop') }}" class="btn btn-primary">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                    @else

                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody id="cart-table">
                                @if ($cartContent->isEmpty())
                                <tr>
                                    <td colspan="5">
                                        <p>No records</p>
                                    </td>
                                </tr>
                                @else
                                @foreach ($cartContent as $item)
                                <tr id="cartItemRowId-{{ $item->rowId }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('uploads/products/small/' . $item->options->productImage->image) }}"
                                                width="" height="">
                                            <h2>{{ ucwords($item->name) }}</h2>
                                        </div>
                                    </td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>
                                        <div class="input-group quantity mx-auto" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub"
                                                    data-id="{{ $item->rowId }}">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text"
                                                class="form-control qty-element form-control-sm  border-0 text-center"
                                                value="{{ $item->qty }}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add"
                                                    data-id="{{ $item->rowId }}">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="item-total-{{ $item->rowId }}" class="">
                                        ₹{{ number_format($item->price * $item->qty, 2) }}
                                    </td>
                                    <td>
                                        <button onclick="deleteCartItem('{{ $item->rowId }}');"
                                            class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card cart-summery">
                        <div class="sub-title">
                            <h2 class="bg-white">Cart Summery</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div class="cart-subtotal">₹{{ Cart::subtotal(); }}</div>
                            </div>
                            <div class="d-flex justify-content-between pb-2">
                                <div>Shipping</div>
                                <div>₹0</div>
                            </div>
                            <div class="d-flex justify-content-between summery-end">
                                <div>Total</div>
                                <div class="cart-subtotal">₹{{ Cart::subtotal(); }}</div>
                            </div>
                            <div class="pt-5">
                                <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                    <div class="input-group apply-coupan mt-4">
                        <input type="text" placeholder="Coupon Code" class="form-control">
                        <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection


@push('customJs')
<script>
    $('.add').click(function(){
        let qtyElement = $(this).parent().prev(); //Qty Input
        let qtyValue = parseInt(qtyElement.val());
        if(qtyValue < 10){
            qtyElement.val(qtyValue+1)
            let rowId = $(this).data('id'); //Qty Input
            let newQty = qtyElement.val(); //Qty Input
            updateCart(rowId,newQty);
        }
    });
    
    
    $('.sub').click(function(){
        let qtyElement = $(this).parent().next(); //Qty Input
        let qtyValue = parseInt(qtyElement.val());
        if(qtyValue > 1){
            qtyElement.val(qtyValue-1)
            let rowId = $(this).data('id'); //Qty Input
            let newQty = qtyElement.val(); //Qty Input
            updateCart(rowId,newQty);
           
        }
    });

    function updateCart(rowId,newQty){
        $.ajax({
            url: '{{ route('front.updateCart') }}',
            type: 'post',
            dataType: 'json',
            data:{rowId:rowId,qty:newQty},
            success:function(response){
                if(response.status ==true){
                    // window.location.href = '{{ route('front.cart') }}';
                    $('.cart-subtotal').text(
                        '₹' + Number(response.total).toFixed(2)
                        );

                    $('#item-total-' + rowId).text(
                            '₹' + Number(response.product_total).toFixed(2)
                        );

                    showAlert(response.status,response.message);
                }else{
                     showAlert(response.status,response.error);
                }
            }
        });
    }

    
        function deleteCartItem(rowId) {
            $.ajax({
                url: '{{ route('front.deletCartItem') }}',
                type: 'POST',
                data: {
                    rowId: rowId
                },
                dataType: 'json',
                success: function(response) {

                    if (response.cartCount > 0) {
                        $('.cart-badge').text(response.cartCount);
                    } else {
                        $('.cart-badge').addClass('d-none');
                        let norecord =
                            `<div class="card w-100 h-100 text-center shadow">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center ">
                            <h4 class="card-title mb-2">Your cart is empty</h4>
                            <p class="text-muted mb-4">
                                Looks like you haven’t added anything to your cart yet.
                            </p>
                            <a href="{{ route('front.shop') }}" class="btn btn-primary">
                                Continue Shopping
                            </a>
                        </div>
                    </div>`;
                        $('.cart-wrapper').html(norecord);
                    }

                    if(response.status == true){
                        
                        $('.cart-subtotal').text(
                       '₹' + Number(response.total).toFixed(2)
                       );

                        $('#cartItemRowId-' + rowId).remove();


                        showAlert(response.status,response.message);

                    }else{
                        showAlert(response.status,response.error);
                    }   
                  
                }
            });
        };

     

</script>
@endpush