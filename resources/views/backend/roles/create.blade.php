
@extends('master')
@section('title', 'Create A New Role')
@section('program_title', 'Admin Portal')
@section('user_fullname', $email)
@section('user_email', $fullname)
@section('content')
@push('body-class', 'theme-red')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Super Admin</h2>
        </div>
        <div class="row clearfix">
            <div class="container col-md-8 col-md-offset-2">
                <div class="well well bs-component">

                    <form class="form-horizontal" method="post">

                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                        @endforeach

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                        <fieldset>
                            <legend>Create a new role</legend>
                            <div class="form-group">
                                <label for="name" class="col-lg-2 control-label">Name</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="name" name="name">
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
        </div>
    </div>
</section>
@endsection
@push('scripts')
<!-- <script src="{!! asset('adminbsb/js/pages/charts/morris.js') !!}"></script> -->
<!-- Sparkline Chart Plugin Js -->
<script src="{!! asset('adminbsb/plugins/jquery-sparkline/jquery.sparkline.js') !!}"></script>
<!-- Sparkline Plugin Js -->
<script src="{!! asset('adminbsb/plugins/jquery-sparkline/jquery.sparkline.js') !!}"></script>

<!-- Custom Js -->
<script src="{!! asset('adminbsb/js/admin.js') !!}"></script>
<script src="{!! asset('adminbsb/js/pages/charts/sparkline.js') !!}"></script>
@endpush
