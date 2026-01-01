@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            @include('admin.message')
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('index.products') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="{{ route('update.product', $product->id) }}" method="post" enctype="multipart/form-data"
            name="createProductForm" id="createProductForm">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title"
                                                class="form-control {{ $errors->first('title') ? 'is-invalid' : 0 }}"
                                                placeholder="Title" value="{{ $product->title }}">
                                            <small class="text-danger">
                                                @error('title')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" readonly name="slug" id="slug"
                                                class="form-control {{ $errors->first('slug') ? 'is-invalid' : 0 }}"
                                                placeholder="Slug" value="{{ $product->slug }}">
                                            <small class="text-danger">
                                                @error('slug')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">  {{ $product->short_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">  {{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="shipping_returns">Shipping & Returns</label>
                                            <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote"
                                                placeholder="Description">  {{ $product->shipping_returns }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="product-gallery" class="row">
                            @if ($productImage->isNotEmpty())
                                @foreach ($productImage as $image)
                                    <input type="hidden" name="image_array[]" value="{{ $image->id }}">
                                    <div class="col-md-3 text-center " id="image_row-{{ $image->id }}">
                                        <div class="card">
                                            <div class="card-body">
                                                <img src="{{ asset('uploads/products/small/' . $image->image) }}"
                                                    class="card-img-top" alt="img">
                                            </div>
                                            <a href="javascript:void(0)" onclick="deleteImage({{ $image->id }})"
                                                class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price"
                                                class="form-control {{ $errors->first('price') ? 'is-invalid' : 0 }}"
                                                placeholder="Price" value="{{ $product->price }}">
                                            <small class="text-danger">
                                                @error('price')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control {{ $errors->first('compare_price') ? 'is-invalid' : 0 }} "
                                                placeholder="Compare Price" value="{{ $product->compare_price }}">
                                            <small class="text-danger">
                                                @error('compare_price')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku"
                                                class="form-control {{ $errors->first('sku') ? 'is-invalid' : 0 }}"
                                                placeholder="sku" value="{{ $product->sku }}">
                                            <small class="text-danger">
                                                @error('sku')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode"
                                                class="form-control {{ $errors->first('barcode') ? 'is-invalid' : 0 }}"
                                                placeholder="Barcode" value="{{ $product->barcode }}">
                                            <small class="invalid-feedback">
                                                @error('sku')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input
                                                    class="custom-control-input {{ $errors->first('track_qty') ? 'is-invalid' : 0 }}"
                                                    type="checkbox" id="track_qty" name="track_qty" value="Yes"
                                                    {{ $product->track_qty == 'Yes' ? 'checked' : '' }}>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <br>
                                                <small class="text-danger">
                                                    @error('track_qty')
                                                        {{ $message }}
                                                    @enderror
                                                </small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control {{ $errors->first('qty') ? 'is-invalid' : 0 }}"
                                                placeholder="Qty" value="{{ $product->qty }}">
                                            <small class="text-danger">
                                                @error('qty')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Related Product</h2>
                                <div class="mb-3">
                                    <select name="related_products[]" id="related_products"
                                        class="related-products w-100 text-dark" multiple>
                                        @if (!empty($relatedProducts))
                                            @foreach ($relatedProducts as $relProduct)
                                                <option selected value="{{ $relProduct->id }}">{{ $relProduct->title }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-danger">
                                        @error('related_products')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status"
                                        class="form-control {{ $errors->first('status') ? 'is-invalid' : 0 }}">
                                        <option {{ $product->status == 1 ? 'checked' : '' }} value="1">Active
                                        </option>
                                        <option {{ $product->status == 0 ? 'checked' : '' }} value="0">Block</option>
                                    </select>
                                    <small class="text-danger">
                                        @error('status')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product Category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category"
                                        class="form-control {{ $errors->first('category') ? 'is-invalid' : 0 }}">
                                        <option value="">Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ $category->id == $product->category_id ? 'selected' : '' }}
                                                    value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-danger">
                                        @error('category')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label for="sub_category">Sub category</label>
                                    <select name="sub_category" id="sub_category"
                                        class="form-control {{ $errors->first('sub_category') ? 'is-invalid' : 0 }}">
                                        <option value="">Select a Sub Category</option>
                                        @if (!empty($subCategories))
                                            @foreach ($subCategories as $subCategory)
                                                <option
                                                    {{ $subCategory->id == $product->sub_category_id ? 'selected' : '' }}
                                                    value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                    <small class="text-danger">
                                        @error('sub_category')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand"
                                        class="form-control {{ $errors->first('brand') ? 'is-invalid' : 0 }}">
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option {{ $brand->id == $product->brand_id ? 'selected' : '' }}
                                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-danger">
                                        @error('brand')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured"
                                        class="form-control {{ $errors->first('is_featured') ? 'is-invalid' : '' }}">
                                        <option {{ $product->is_featured == 'No' ? 'selected' : '' }} value="No">No
                                        </option>
                                        <option {{ $product->is_featured == 'Yes' ? 'selected' : '' }} value="Yes">Yes
                                        </option>
                                    </select>
                                    <small class="text-danger">
                                        @error('is_featured')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('index.products') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('customJs')
    <script>
        $('.related-products').select2({
            placeholder: 'Search products',
            minimumInputLength: 3,
            width: '100%',

            ajax: {
                tags: true,
                url: '{{ route('products.getProducts') }}',
                multiple: true,
                dataType: 'json',
                delay: 250,

                data: function(params) {
                    return {
                        q: params.term
                    };
                },

                processResults: function(data) {
                    return {
                        results: data.tags.map(item => ({
                            id: item.id,
                            text: item.title
                        }))
                    };
                }
            },

            createTag: function(params) {
                return {
                    id: params.term,
                    text: params.term,
                    newTag: true
                };
            }
        });




        $('#title').keyup(function(e) {
            e.preventDefault();
            let element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response.status == true) {
                        $('#slug').val(response.slug);
                    }
                }
            });
        });

        $('#category').change(function() {

            var category_id = $(this).val();
            const oldCat = {!! json_encode(old('category')) !!} || null;
            console.log(oldCat)
            $.ajax({
                url: '{{ route('index.product-subcategories') }}',
                type: 'get',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(response) {
                    $('#sub_category').find("option").not(":first").remove();
                    $.each(response["subCategories"], function(key, item) {
                        $("#sub_category").append(
                            `<option  value = '${item.id}'>${item.name}</option>`)
                    });
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });


        });

        Dropzone.autoDiscover = false;

        new Dropzone("#image", {
            url: "{{ route('update.product-image') }}",
            methiod: "post",
            maxFiles: 10,
            paramName: "image",
            params: {
                'product_id': '{{ $product->id }}'
            },
            acceptedFiles: "image/*",
            addRemoveLinks: true,

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function(file, response) {
                console.log(response.ImagePath);

                var html =

                    `<input type="hidden" name="image_array[]" value="${response.image_id}">
                            <div class="col-md-3 text-center " id="image_row-${response.image_id}">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="${response.ImagePath}" class="card-img-top" alt="img">
                                    </div>
                                    <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                                </div>
                            </div>`;
                $('#product-gallery').append(html);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        function deleteImage(id) {
            // console.log(id);
            $("#image_row-" + id).remove();
            if (confirm("Are you sure to delete this image?") == false) {}
            $.ajax({
                url: '{{ route('destroy.product-image') }}',
                type: 'delete',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.status == true) {
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }


                }
            });




        }
    </script>
@endsection
