@extends('admin.layouts.app')
@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Charges</h1>
            </div>
        </div>
    </div>
</section>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

{{-- ERROR MESSAGE --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

{{-- VALIDATION ERRORS --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<section class="content">
    <div class="container-fluid">

        <form action="{{ route('shipping.store') }}" method="post">
            @csrf
            <!-- SHIPPING FORM -->
            <div class="card">
                <div class="card-header">
                    <h5>Add Shipping Charge</h5>
                </div>
                <form action="">

                </form>
                <div class="card-body">
                    <div class="row">

                        <!-- Country -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Country</label>

                                <select id="ship_country" name="country_id" class="form-control">

                                    <option value="">Select A Country</option>

                                    <option value="rest_of_world" {{ old('country_id')=='rest_of_world' ? 'selected'
                                        : '' }}>
                                        Rest of World
                                    </option>

                                    @if(!empty($countries))
                                    @foreach($countries as $country)

                                    <option value="{{ $country->id }}" {{ old('country_id')==$country->id ? 'selected' :
                                        '' }}>
                                        {{ $country->name }}
                                    </option>

                                    @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>


                        <!-- Charge -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Shipping Charge</label>
                                <input type="number" name="shipping_charge" class="form-control"
                                    placeholder="Enter charge" value="{{ old('shipping_charge') }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



        <!-- INDEX TABLE -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>Shipping List</h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Country</th>
                            <th>Charge</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(!empty($shippings))
                        @foreach ($shippings as $shipping)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shipping->country->name ?? 'Rest Of World' }}</td>
                            <td>{{ $shipping->shipping_charge }}</td>
                            <td class="text-center">
                                <a class="btn btn-danger btn-sm" title="Delete"
                                    href="{{ route('shipping.destroy',$shipping->id) }}">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </a>
                            </td>
                        </tr>

                        @endforeach

                        @endif
                    </tbody>

                </table>

            </div>
        </div>


    </div>
</section>
@endsection