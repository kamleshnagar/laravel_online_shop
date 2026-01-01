@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="subcategory.html" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="{{ route('users.store') }}" method="post">

            @csrf
            <div class="card">

                <div class="card-body">
                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Name">
                            <p></p>
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email">Email:</label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Email">
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password">password:</label>
                            <input type="password" name="password" value=""
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" name="confirm_password" value=""
                                class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password">
                            @error('confirm_password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 w-100 mt-3">
                            <label for="Role">Roles:</label>
                            <div class="mb-3">
                                <select class="form-select w-100" name="roles[]" multiple>
                                    <option selected >Select Role</option>
                                    @foreach ($roles as $role)
                                    <option {{ old('roles' === $role->name) }} value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    $('#subCategoryForm').submit(function(e) {
            e.preventDefault();

            var element = $(this);

            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('sub-categories.store') }}',
                type: 'POST',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response['status'] == true) {

                        window.location.href = "{{ route('sub-categories.index') }}";

                        
                        $('#name').Class('is-invalid').siblings('p').Class('invalid-feedback').html("");
                        $('#slug').Class('is-invalid').siblings('p').Class('invalid-feedback').html("");
                        $('#category').Class('is-invalid').siblings('p').Class('invalid-feedback').html("");



                    } else {

                        var errors = response['errors'];
                        if (errors['name']) {
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['name']);

                        } else {
                            
                            $('#name').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }


                        if (errors['slug']) {
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['slug']);

                        } else {
                            $('#slug').Class('is-invalid').siblings('p').Class('invalid-feedback').html(
                                "");

                        }

                        if (errors['category']) {
                            $('#category').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['category']);
    
                        } else {
                            $('#category').Class('is-invalid').siblings('p').Class('invalid-feedback').html(
                                "");
    
                        }
                    }


                },
                error: function(jqXHR, exception) {
                    console.log('error found')
                }
            });
        });



        $('#name').keyup(function(e) {
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
</script>
@endsection