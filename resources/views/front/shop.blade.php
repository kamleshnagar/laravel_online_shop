@extends('front.layouts.app')


@section('content')
    <main>

        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Shop</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-6 pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 sidebar">

                        <div class="sub-title">
                            <h2>categories</h3>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-flush" id="accordionExample">
                                    @if ($categories->isNotEmpty())
                                        <div class="accordion-item">
                                            @foreach ($categories as $key => $category)
                                                @if ($category->sub_categories->isNotEmpty())
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <a href="{{ route('front.shop', $category->slug) }}">
                                                        <button
                                                            class="accordion-button collapsed {{ $categorySelected == $category->id ? 'text-primary' : '' }}"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse-{{ $key }}"
                                                            aria-expanded="false" aria-controls="collapseOne">

                                                            {{ $category->name }}
                                                        </button>
                                                        </a>
                                                    </h2>
                                                @else
                                                    <a href="{{ route('front.shop', $category->slug) }}"
                                                        class="nav-item nav-link {{ $categorySelected == $category->id ? 'text-primary' : '' }}">{{ $category->name }}</a>
                                                @endif

                                                @if ($category->sub_categories->isNotEmpty())
                                                    <div id="collapse-{{ $key }}"
                                                        class="accordion-collapse collapse {{ $categorySelected == $category->id ? 'show' : '' }} "
                                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                        style="">
                                                        <div class="accordion-body">
                                                            <div class="navbar-nav">
                                                                @foreach ($category->sub_categories as $sub_category)
                                                                    <a href="{{ route('front.shop', [$category->slug, $sub_category->slug]) }}"
                                                                        class="nav-item nav-link {{ $subCategorySelected == $sub_category->id ? 'text-primary' : '' }} ">{{ $sub_category->name }}</a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="sub-title mt-5">
                            <h2>Brand</h3>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                @if ($brands->isNotEmpty())
                                    @foreach ($brands as $brand)
                                        <div class="form-check mb-2">
                                            {{-- {{ dd($brandsArray) }} --}}
                                            <input {{ in_array($brand->id, $brandsArray) ? 'checked' : '' }}
                                                class="form-check-input brand-label" name="brand[]" type="checkbox"
                                                value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="sub-title mt-5">
                            <h2>Price</h3>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <input type="text" class="js-range-slider" name="my_range" value="" />
                            </div>
                        </div>

                    </div>
                    <div class="col-md-9">
                        <div class="row pb-3">


                            {{-- sorting dropdown --}}
                            <div class="col-6 pb-1">
                                <div class="d-flex align-items-center justify-content-start mb-4">
                                    <div class="ml-2">
                                        <a href= "{{ route('front.shop') }}" class=" btn bg-white border rounded">Reset Filters</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 pb-1">
                                <div class="d-flex align-items-center justify-content-end mb-4">
                                    <div class="ml-2">

                                        <select name="sort" id="sort" class="form-control">Sort
                                            <option {{ $sort == 'latest' ? 'selected' : '' }} value="latest">Latest
                                            </option>
                                            <option {{ $sort == 'price_desc' ? 'selected' : '' }} value="price_desc">Price
                                                Hight</option>
                                            <option {{ $sort == 'price_asc' ? 'selected' : '' }} value="price_asc">Price
                                                Low</option>
                                        </select>


                                    </div>
                                </div>
                            </div>

                            {{-- Product List --}}
                            @if ($products->isNotEmpty())
                                @foreach ($products as $product)
                                    @php
                                        $productImage = $product->product_images->first();
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="card product-card">
                                            <div class="product-image position-relative">

                                                <a href="{{ route('front.product',$product->slug) }}" class="product-img">

                                                    <img class="card-img-top"
                                                        src="{{ asset(!empty($productImage->image) ? 'uploads/products/small/' . $productImage->image : 'uploads/default.png') }}"
                                                        alt=""></a>
                                                <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                                <div class="product-action">
                                                    <a class="btn btn-dark" href="#">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body text-center mt-3">
                                                <a class="h6 link" href="product.php">{{ $product->title }}</a>
                                                <div class="price mt-2">
                                                    <span class="h5"><strong>${{ $product->price }}</strong></span>
                                                    @if ($product->compare_price > 0)
                                                        <span
                                                            class="h6 text-underline"><del>{{ $product->compare_price }}</del></span>
                                                    @endif()
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12 bg-white p-2 m-2 rounded text-center">
                                    <p>No products found.</p>
                                </div>
                            @endif

                            <div class="col-md-12 pt-5">
                                <nav aria-label="Page navigation example">

                                    {{ $products->links() }}

                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection


@section('customJs')
    <script>
        renageSilider = $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: 10000,
            from: {{ $priceMin }},
            step: 10,
            to: {{ $priceMax }},
            stepskin: "round",
            max_postfix: "+",
            prefix: "Rs: ",
            onFinish: function() {
                apply_Filters();
            }
        });

        var slider = $(".js-range-slider").data("ionRangeSlider");

        $(".brand-label").change(function() {
            apply_Filters();
        });

        function apply_Filters() {

            var brands = [];

            $(".brand-label").each(function() {
                if ($(this).is(":checked") == true) {
                    brands.push($(this).val());
                }
            });

            //Price Range Filter
            var url = '{{ url()->current() }}?';
            url += "&price_min=" + slider.result.from + "&price_max=" + slider.result.to;

            // Brand Filter
            if (brands.length > 0) {
                url += '&brand=' + brands.toString();
            }

            //sorting

            url += '&sort=' + $("#sort").val();



            console.log((brands.toString()));
            window.location.href = url;
        }


        $("#sort").change(function() {

            apply_Filters();

        });
    </script>
@endsection
