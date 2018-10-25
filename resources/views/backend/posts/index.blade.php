
@extends('master')
@section('title', 'All Posts')
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2> All posts </h2>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($posts->isEmpty())
                        <p> There is no post.</p>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Created At</th>
                                <th>Updated At</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{!! $post->id !!}</td>
                                    <td>
                                        <a href="{!! action('Admin\PostsController@edit', $post->id) !!}">{!! $post->title !!} </a>
                                    </td>
                                    <td>{!! $post->slug !!}</td>
                                    <td>{!! $post->created_at !!}</td>
                                    <td>{!! $post->updated_at !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
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