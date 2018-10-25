@extends('master')
@section('name', 'Forbidden')

@section('content')
@push('body-class', 'four-zero-four')
<div class="four-zero-four-container">
    <div class="error-code">403</div>
    <div class="error-message">FORBIDDEN</div>
    <div class="button-place">
        <a href="/" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
    </div>
</div>
@endsection
