@extends('front.layouts.app')


@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">
            <form action="{{ route('account.processRegister') }}" method="post">
                @csrf

                <h4 class="modal-title">Register Now</h4>

                {{-- Name --}}
                <div class="form-group">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Name"
                        id="name" name="name" value="{{ old('name') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                        id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="form-group">
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone"
                        id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password" id="password" name="password">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirm Password" id="cpassword" name="password_confirmation">
                </div>

                <div class="form-group small">
                    <div class="text-center small">
                        Already have an account?
                        <a href="{{ route('login') }}">Login Now</a>
                    </div>
                </div>

                <div class="w-100 text-center">
                    <button type="submit" class="btn btn-dark btn-block btn-lg">
                        Register
                    </button>
                </div>
            </form>



        </div>
    </div>
</section>

@endsection


@push('customJs')

@endpush