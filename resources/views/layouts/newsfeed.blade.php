@extends('master')
@section('title', 'Leaderboard')
@section('program_title', $companyinfo['description'])
@section('user_fullname', $email)
@section('user_email', $fullname)
@section('avatar',$avatar)
@push('styles')
<style>
.avatarimg {
  width:60px;
  height:60px;
  border: 2px #a6c1ee solid;
  border-radius: 50%;
}
.avatarholder {
  text-align: center;
}
.table td, .table th { 
  vertical-align: middle !important;
}
</style>
@endpush

@section('content')
@push('body-class', 'theme-red')
<section class="content">
    <div class="container-fluid">
      <div class="block-header">
          <h2>NEWSFEEDS</h2>
      </div>
      <!-- #END# Striped Rows -->
    </div>
</section>
@endsection