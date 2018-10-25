@extends('master')
@section('name', 'Register')

@section('content')
    <!-- <div class="container col-md-6 col-md-offset-3">
        <div class="well well bs-component">

            <form class="form-horizontal" method="post">

                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach

                 {!! csrf_field() !!}

                <fieldset>
                    <legend>Register an account</legend>
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-lg-2 control-label">Email</label>
                        <div class="col-lg-10">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-lg-2 control-label">Password</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control"  name="password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-lg-2 control-label">Confirm password</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control"  name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
 -->

@push('body-class', 'signup-page')
    <div class="container">
<!--Grid column-->
    <div class="row mt-5">
        <div class="col-md-6 mx-auto mt-5 mb-5">
            
                <div class="card mx-xl-5">
                    <div class="card-body">

                        <!--Header-->
                        <div class="form-header deep-blue-gradient rounded">
                            <h3><i class="fa fa-lock"></i> Register:</h3>
                        </div>

                        <!-- Material input email -->
                        <form method="post">
                            @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                            @endforeach
                            {!! csrf_field() !!}
                           <!--  <div class="md-form font-weight-light">
                            <i class="fa fa-envelope prefix grey-text"></i>
                            <input type="email" id="email" name="email" class="form-control">
                            <label for="email">Your email</label>
                            </div> -->

                            <div class="form-group">
                             <!--    <label for="name" class="col-lg-2 control-label">First name</label> -->
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="first_name" placeholder="First name" name="first_name" value="{{ old('first_name') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label for="name" class="col-lg-2 control-label">Last name</label> -->
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="last_name" placeholder="Last name" name="last_name" value="{{ old('last_name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <label for="email" class="col-lg-2 control-label">Email</label> -->
                                <div class="col-lg-10">
                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <label for="password" class="col-lg-2 control-label">Password</label> -->
                                <div class="col-lg-10">
                                    <input type="password" class="form-control"  placeholder="Password"  name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <label for="password" class="col-lg-2 control-label">Confirm password</label> -->
                                <div class="col-lg-10">
                                    <input type="password" class="form-control"  placeholder="Confirm password"  name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-10 col-lg-offset-2">
                                    <button type="reset" class="btn btn-default">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                        

                    </div>
                </div>
                  
        </div>
          <!--Grid column-->
    </div>
</div>

@endsection