
@extends('master')
@section('title', 'Create a New Post')
@section('program_title', 'SuperAdmin Portal')
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
                            <legend>Create a new post</legend>
                            <div class="form-group">
                                <label for="title" class="col-lg-2 control-label">Title</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="title" placeholder="Title" name="title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="content" class="col-lg-2 control-label">Content</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" rows="3" id="content" name="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="categories" class="col-lg-2 control-label">Categories</label>

                                <div class="col-lg-10">
                                    <select class="form-control" id="category" name="categories[]" multiple>
                                        @foreach($categories as $category)
                                            <option value="{!! $category->id !!}">
                                                {!! $category->name !!}
                                            </option>
                                        @endforeach
                                    </select>
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
