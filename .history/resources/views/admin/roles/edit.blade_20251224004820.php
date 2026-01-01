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
                                            placeholder="name" value="{{ $role->name }}">
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
                                                name="permissions[{{ $permission->name }}]"
                                                value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked'  }}  />
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
