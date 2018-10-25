
@extends('master')
@section('name', 'Reset Password')

@section('content')
<!-- <div class="container">
    <div class="row wow fadein">
        <div class="col-md-6 mb-4 col-md-offset-3">
                <form class="form-horizontal" method="post">

                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach

                     {!! csrf_field() !!}

                    <fieldset>
                        <legend>Login</legend>

                        <div class="form-group">
                            <label for="email" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control"  name="password">
                            </div>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" > Remember Me?
                            </label>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
        </div>
    </div>
</div> -->
@push('body-class', 'fp-page')
    <div class="fp-box">
        <div class="logo">
            <a href="/">HealthQuest</a>
            <small>a Healthier New You!</small>
        </div>
        <div class="card">
            <div class="body">
                 <form method="POST" action="{{ route('password.request') }}">
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                    {!! csrf_field() !!}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="msg">
                        {{ __('Reset Password') }}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}" placeholder="E-mail" required autofocus>
                        </div>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"  placeholder="Password"  required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" placeholder="Confirm Password" required autofocus>
                        </div>
                    </div>
                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">RESET MY PASSWORD</button>

                  <!--   <div class="row m-t-20 m-b--5 align-center">
                        <a href="/login">Sign In!</a>
                    </div> -->
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

<!-- Waves Effect Plugin Js -->
<script src="{!! asset('adminbsb/plugins/node-waves/waves.js') !!}"></script>

<!-- Validation Plugin Js -->
<script src="{!! asset('adminbsb/plugins/jquery-validation/jquery.validate.js') !!}"></script>

<!-- Custom Js -->
<script src="{!! asset('adminbsb/js/admin.js') !!}"></script>
<script src="{!! asset('adminbsb/js/pages/examples/forgot-password.js') !!}"></script>
@endpush
