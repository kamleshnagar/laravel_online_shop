@extends('admin.layouts.app')

@section('content')


    <!-- Content Header (Page header) -->
    <section class="content-header">

        <div class="container-fluid my-2">
            @include('admin.message')
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
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

        <form action="{{ route('store.product') }}" method="post" enctype="multipart/form-data" name="createProductForm"
            id="createProductForm">
            @csrf
            <input type="hidden" name="category_id" value="{{ $category_id ?? '' }}">

            <div class="container-fluid">
                <div class="row">

                    {{-- ---------------------jugaadd----------------- --}}
                    @php
                        if (!empty($subCategories)) {
                            print_r($subCaregories);
                        }
                    @endphp
                    {{-- ---------------------jugaadd----------------- --}}
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title"
                                                class="form-control {{ $errors->first('title') ? 'is-invalid' : 0 }}"
                                                placeholder="Title" value="{{ old('title') }}">
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
                                                placeholder="Slug" value="{{ old('slug') }}">
                                            <small class="text-danger">
                                                @error('slug')
                                                    {{ $message }}
                                                @enderror
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">  {{ old('description') }}</textarea>
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
                                                placeholder="Price" value="{{ old('price') }}">
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
                                                placeholder="Compare Price" value="{{ old('compare_price') }}">
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
                                                placeholder="sku" value="{{ old('sku') }}">
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
                                                placeholder="Barcode" value="{{ old('barcode') }}">
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
                                                    {{ old('track_qty') == 'Yes' ? 'checked' : '' }}>
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
                                                placeholder="Qty" value="{{ old('qty') }}">
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
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status"
                                        class="form-control {{ $errors->first('status') ? 'is-invalid' : 0 }}">
                                        <option {{ old('status') == '1' ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ old('status') == '0' ? 'selected' : '' }} value="0">Block</option>
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
                                                <option data-bs-cat-id="{{ old('category') }}"
                                                    value="{{ $category->id }}"
                                                    {{ old('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
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
                                                <option {{ old('brand') == $brand->id ? 'selected' : '' }}
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
                                        class="form-control {{ $errors->first('is_featured') ? 'is-invalid' : 0 }}">
                                        <option {{ old('is_featured') == 'No' ? 'selected' : '' }} value="No">No
                                        </option>
                                        <option {{ old('is_featured') == 'Yes' ? 'selected' : '' }} value="Yes">Yes
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
                    <button type="submit" class="btn btn-primary">Create</button>
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
        $(document).ready(function() {
            var oldCat = $('#category')
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
                            `<option value = '${item.id}'>${item.name}</option>`)
                    });
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });


        });


        Dropzone.autoDiscover = false;

        new Dropzone("#image", {
            url: "{{ route('temp-images.create') }}",
            maxFiles: 10,
            paramName: "image",
            acceptedFiles: "image/*",
            addRemoveLinks: true,

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function(file, response) {

                var html = ` 
                <input type="hidden" name="image_array[]" value="${response.image_id}">
                <div class="col-md-3 text-center " id="image_row-${response.image_id}">
                                <div class="card">
                                    <img src="${response.imagePath}" class="card-img-top" alt="img">
                                    <div class="card-body">
                                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" " class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>`;

                $('#product-gallery').append(html);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        function deleteImage(id) {
            console.log(id);
            $("#image_row-" + id).remove();
        }
    </script>
@endsection
