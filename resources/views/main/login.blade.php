@extends('layouts.app')

@section('content')

<div class="limiter">
    <div class="container-login100" style="background-image: url('images/img-01.jpeg');">
        <div class="wrap-login100">
          <div class="card" style="padding: 2rem; background-color: #203564; border: 1px solid #999;">

            <div class="" style="text-align: center;">
                <img src="{{asset('/images/logo-black.png')}}" alt="" class="logo-index">
            </div>

            <form method="POST" action="{{ action('login') }}" class="login100-form validate-form">
                @csrf


                <div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input100" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Correo electrónico">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input100" name="password" required autocomplete="current-password" placeholder="Contraseña">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock"></i>
                    </span>
                </div>

                <div class="container-login100-form-btn p-t-10">
                    <button class="login100-form-btn" type="submit">
                        {{ __('Login') }}
                    </button>
                    </button>
                </div>

                <div class="text-center w-full p-t-30">
                    <a class="txt1" href="" style="text-shadow: 1px 1px #00000077;">
                        Olvide mi contraseña
                    </a>
                </div>

                <!--div class="text-center w-full p-t-25 p-b-230">

                    @if (Route::has('password.request'))
                        <a class="txt1" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif

                </div-->


            </form>
          </div>

        </div>
    </div>
</div>
@endsection
