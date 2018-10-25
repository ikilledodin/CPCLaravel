
@extends('master')
@section('title', 'All Users')
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2> All users </h2>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($users->isEmpty())
                        <p> There is no user.</p>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined at</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{!! $user->id !!}</td>
                                    <td>
                                        <a href="{!! action('Admin\UsersController@edit', $user->id) !!}">{!! $user->name !!} </a>
                                    </td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! $user->created_at !!}</td>
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
