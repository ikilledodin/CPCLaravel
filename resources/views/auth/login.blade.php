
@extends('master')
@section('name', 'Login')

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
@push('body-class', 'login-page')
    <div class="login-box">
        <div class="logo">
            <a href="/">HealthQuest</a>
            <small>a Healthier New You!</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                    @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                    @if (session('info'))
                        <div class="alert alert-info">
                            {!! session('info') !!}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {!! session('error') !!}
                        </div>
                    @endif
                    {!! csrf_field() !!}
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required autofocus>
                            <!-- <input type="email" id="email" name="email" class="form-control"> -->
                            <!-- <label for="email">Your email</label> -->
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                            <!-- input type="password" id="password" name="password" class="form-control"> -->
                                <!-- <label for="password">Your password</label> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="/register">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="/password/reset">Forgot Password?</a>
                        </div>
                    </div>
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
<script src="{!! asset('adminbsb/js/pages/examples/sign-in.js') !!}"></script>
@endpush
