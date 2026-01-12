@extends('front.layouts.app')

@section('content')

<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.includes.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">

                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>

                        <div class="card-body p-4">

                            <form action="{{ route('account.profile.update') }}" method="POST">
                                @csrf
                                <div class="row">

                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $user->name ?? '') }}" placeholder="Enter Your Name">
                                    </div>

                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $user->email ?? '') }}"
                                            placeholder="Enter Your Email">
                                    </div>

                                    <div class="mb-3">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone', $user->phone ?? '') }}"
                                            placeholder="Enter Your Phone">
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <button type="submit" class="btn btn-dark">
                                        Update
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>
@endsection