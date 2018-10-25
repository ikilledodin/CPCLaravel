@extends('master')
@section('name', 'Page Not Found')

@section('content')
@push('body-class', 'four-zero-four')
<div class="four-zero-four-container">
    <div class="error-code">500</div>
    <div class="error-message">Internal Server Error</div>
    <div class="button-place">
        <a href="/" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
    </div>
</div>
@endsection
