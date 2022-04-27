@extends('layouts.app')

@section('content')

<div class="limiter">
    <div class="container-login100" style="background-image: url('images/img-01.jpeg');">
        <div class="wrap-login100">
          <div class="card" style="padding: 2rem; background-color: #203564; border: 1px solid #999;">

            <div class="" style="text-align: center;">
                <img src="{{asset('/images/logo-black.png')}}" alt="" class="logo-index">
            </div>

            <form method="POST" action="{{ action('MainController@login') }}" class="login100-form validate-form">
                @csrf


                <div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror input100" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email or Phone') }}">

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
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input100" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

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
                      {{ __('Forgot Your Password?') }}
                    </a>
                </div>

                @if (config('locale.status') && count(config('locale.languages')) > 1)
                  <div class="top-right links">
                    @foreach (array_keys(config('locale.languages')) as $lang)
                      @if ($lang != App::getLocale())
                        <a href="{!! route('lang.swap', $lang) !!}">
                          {!! $lang !!} <small>{!! $lang !!}</small>
                        </a>
                      @endif
                    @endforeach
                  </div>
                @endif

                <!--div class="text-center w-full p-t-60">
                  <a href="{{ url('lang', ['en']) }}"style="margin-right: 10px;"><img src="{{asset('images/en.png')}}" style="height: 28px;" alt="en"></a>
                  <a href="{{ url('lang', ['es']) }}"style="margin-left: 10px;"><img src="{{asset('images/es.svg')}}" style="height: 28px;" alt="es"></a>
                </div-->

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
