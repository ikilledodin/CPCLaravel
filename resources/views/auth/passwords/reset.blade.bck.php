@extends('master')
@section('name', 'Reset Password Page')

@section('content')
<!-- <div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container">
<!--Grid column-->
    <div class="row mt-5">
        <div class="col-md-6 mx-auto mt-5 mb-5">
            
                <div class="card mx-xl-5">
                    <div class="card-body">

                        <!--Header-->
                        <div class="form-header deep-blue-gradient rounded">
                            <h3><i class="fa fa-lock"></i> {{ __('Reset Password') }}:</h3>
                        </div>

                        <!-- Material input email -->
                        <form method="POST" action="{{ route('password.request') }}">
                            @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                            @endforeach
                            {!! csrf_field() !!}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="md-form font-weight-light">
                                <i class="fa fa-envelope prefix grey-text"></i>
                                 <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}" required autofocus>
                                <label for="email">{{ __('E-Mail Address') }}</label>
                            </div>

                            <!-- Material input password -->
                            <div class="md-form font-weight-light">
                                <i class="fa fa-lock prefix grey-text"></i>
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                <label for="password">{{ __('Password') }}</label>
                            </div>

                            <div class="md-form font-weight-light">
                                <i class="fa fa-lock prefix grey-text"></i>
                                <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>
                                <label for="password">{{ __('Confirm Password') }}</label>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-light-blue waves-effect waves-light" type="submit">{{ __('Reset Password') }}</button>
                            </div>
                        </form>
                    
                    </div>
                </div>
                  
        </div>
          <!--Grid column-->
    </div>
</div>
@endsection
