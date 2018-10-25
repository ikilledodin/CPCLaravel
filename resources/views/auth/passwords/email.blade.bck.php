@extends('master')
@section('name', 'Login')

@section('content')
<div class="container">
<!--Grid column-->
    <div class="row mt-5">
        <div class="col-md-6 mx-auto mt-5 mb-5">
            
                <div class="card mx-xl-5">
                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        <!--Header-->
                        <div class="form-header deep-blue-gradient rounded">
                            <h3><i class="fa fa-lock"></i> Reset Password:</h3>
                        </div>

                        <!-- Material input email -->
                          <form method="POST" action="{{ route('password.email') }}">
                            @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                            @endforeach
                            @csrf
                            <div class="md-form font-weight-light">
                                <i class="fa fa-envelope prefix grey-text"></i>
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                <!-- @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif -->
                                <label for="email">{{ __('E-Mail Address') }}</label>
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-light-blue waves-effect waves-light" type="submit">{{ __('Send Password Reset Link') }}</button>
                            </div>
                        </form>
                        

                    </div>
                </div>
                  
        </div>
          <!--Grid column-->
    </div>
</div>
<!-- <div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-8  mt-5">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
