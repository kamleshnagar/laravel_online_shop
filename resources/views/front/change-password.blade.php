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

    <section class="section-11">
        <div class="container mt-5">
            <div class="row">

                <div class="col-md-3">
                    @include('front.includes.sidebar')
                </div>

                <div class="col-md-9">
                    <div class="card">

                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                        </div>

                        <div class="card-body p-4">
                            <form action="{{ route('account.password.update') }}" method="POST">
                                @csrf

                                <div class="row">

                                    <!-- OLD PASSWORD -->
                                    <div class="mb-3">
                                        <label>Old Password</label>
                                        <input type="password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            placeholder="Old Password">

                                        @error('current_password')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <!-- NEW PASSWORD -->
                                    <div class="mb-3">
                                        <label>New Password</label>
                                        <input type="password" name="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            placeholder="New Password">

                                        @error('new_password')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <!-- CONFIRM PASSWORD -->
                                    <div class="mb-3">
                                        <label>Confirm Password</label>
                                        <input type="password" name="new_password_confirmation"
                                            class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                            placeholder="Confirm Password">

                                        @error('new_password_confirmation')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">
                                            Save
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
@endsection