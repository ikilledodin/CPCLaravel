
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
<div class="container">
<!--Grid column-->
    <div class="row mt-5">
        <div class="col-md-6 mx-auto mt-5 mb-5">
            
                <div class="card mx-xl-5">
                    <div class="card-body">

                        <!--Header-->
                        <div class="form-header deep-blue-gradient rounded">
                            <h3><i class="fa fa-lock"></i> Login:</h3>
                        </div>

                        <!-- Material input email -->
                        <form method="post">
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
                            <div class="md-form font-weight-light">
                            <i class="fa fa-envelope prefix grey-text"></i>
                            <input type="email" id="email" name="email" class="form-control">
                            <label for="email">Your email</label>
                            </div>

                            <!-- Material input password -->
                            <div class="md-form font-weight-light">
                                <i class="fa fa-lock prefix grey-text"></i>
                                <input type="password" id="password" name="password" class="form-control">
                                <label for="password">Your password</label>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-light-blue waves-effect waves-light" type="submit">Login</button>
                            </div>
                        </form>
                        

                    </div>

                    <!--Footer-->
                    <div class="modal-footer">
                        <div class="options font-weight-light">
                            <!-- <p>Not a member? <a href="#">Sign Up</a></p> -->
                            <p><a href="/password/reset"> Forgot Password?</a></p>
                        </div>
                    </div>
                </div>
                  
        </div>
          <!--Grid column-->
    </div>
</div>


         

@endsection
