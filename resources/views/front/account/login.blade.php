@extends('front.layouts.app')


@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Login</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">
            <form action="{{ route('account.loginProcess') }}" method="post">
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>

                <div class="form-group">
                    <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                        placeholder="Email or Phone" required="required" value="{{ old('login') }}">
                    @error('login')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password" required="required">
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div>
                    <div class="form-group small">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <div class="form-group small">
                        <div class="text-start small">
                            Don't have an account?
                            <a href="{{ route('account.register') }}">Sign up</a>
                        </div>
                    </div>
                </div>

                <div class="w-100 text-center">
                    <button type="submit" class="btn btn-dark btn-block btn-lg">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection


@push('customJs')

@endpush