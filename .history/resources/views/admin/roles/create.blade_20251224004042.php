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

    <form method="POST" action="{{ route('roles.store') }}">
        
        @csrf
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control {{ $errors->first('name') ? 'is-invalid' : 0 }}"
                                            placeholder="name" value="{{ old('name') }}">
                                        <small class="text-danger">
                                            @error('name')
                                            {{ $message }}
                                            @enderror
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Permissions</label>
                                        @foreach ($permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="permiossions[{{ $permission->name }}]"
                                                value="{{ $permission->name }}" />
                                            <label class="form-check-label" for=""> {{ $permission->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pb-5 p-3">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{ route('index.products') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                        </div>
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
                                `<option value = '${item.id}'>${item.name}</option>`
                                )
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


        });
</script>
@endsection