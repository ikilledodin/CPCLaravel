
@extends('master')
@section('title', 'Token Manager')
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
        	<passport-authorized-clients></passport-authorized-clients>
			<passport-clients></passport-clients>
			<passport-personal-access-tokens></passport-personal-access-tokens>
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