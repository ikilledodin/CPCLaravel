@extends('master')
@push('styles')
<link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<style>
.user {
  display: inline-block;
  width: 150px;
  height: 150px;
  border-radius: 50% !important;

  object-fit: cover;
}
</style>
@endpush
@section('title', 'Contact')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 offset-md-2 mt-5">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Edit Profile</h4>
                    <p class="card-category">Complete your profile</p>
                </div>
                <div class="card-body">
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form enctype="multipart/form-data" method="post">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Email</label>
                                    <input type="text" class="form-control" value="{!! Auth::user()->email !!}" disabled>
                                </div>
                            </div>
                            <div class="col-md-3 offset-md-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Profile Picture</label>
                                    <!-- <img src="http://placehold.it/400x200" class="user"> -->
                                    <img src="{!! $profileinfo->avatar ? asset('storage').'/'.$profileinfo['avatar'] : asset('storage').'/'.'avatars/avatar.png' !!}" class="user">
                                    <div class="text-center pt-2"><span> Edit </span></div>
                                    <input type="file" name="avatar">
                                </div>
                            </div>
                            <!--
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Email address</label>
                                    <input type="email" class="form-control">
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{!! $profileinfo->first_name ? $profileinfo->first_name : '' !!}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{!! $profileinfo->last_name ? $profileinfo->last_name : '' !!}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="weightPref" id="weightPref1" value="lbs" {!! $profileinfo->weight_pref == 'lbs' ? 'checked':'' !!}>
                                  <label class="form-check-label" for="weightPref1">lbs</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="weightPref" id="weightPref2" value="kg" {!! $profileinfo->weight_pref == 'kg' ? 'checked':'' !!}>
                                  <label class="form-check-label" for="weightPref2">kg</label>
                                </div>
                                <div class="form-group">
                                    <label class="bmd-label-floating">weight</label>
                                    <input type="text" name="weight"  value="{!! $profileinfo->weight ? $profileinfo->weight : '' !!}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="heightPref" id="heightPref1" value="ft" {!! $profileinfo->height_pref == 'ft' ? 'checked':'' !!}>
                                  <label class="form-check-label" for="heightPref1">ft</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="heightPref" id="heightPref2" value="cm" {!! $profileinfo->height_pref == 'cm' ? 'checked':'' !!}>
                                  <label class="form-check-label" for="heightPref2">cm</label>
                                </div>
                                <div class="form-group">
                                    <label class="bmd-label-floating">height</label>
                                    <input type="text" name="height" value="{!! $profileinfo->height ? $profileinfo->height : '' !!}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender" class="form-control">
                                        <option selected disabled>Choose...</option>
                                        <option value=0 {!! $profileinfo->gender == 0 ? 'selected':'' !!}>Male</option>
                                        <option value=1 {!! $profileinfo->gender == 1 ? 'selected':'' !!}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">City</label>
                                    <input type="text" name="city" value="{!! $profileinfo->city ? $profileinfo->city : '' !!}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Country</label>
                                    <input type="text" name="country" value="{!! $profileinfo->country ? $profileinfo->country : '' !!}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Birthdate</label>
                                    <input type="text" name="birthdate" value="{!! $profileinfo->birthdate ? $profileinfo->birthdate : '' !!}" id="datepicker" class="form-control">
                                    <!-- <input type="text" class="datepicker form-control" placeholder="Please choose a date..."> -->
                                </div>
                            </div>
                        </div>
                       <!--  <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>About Me</label>
                                    <div class="form-group">
                                        <label class="bmd-label-floating"> Lamborghini Mercy, Your chick she so thirsty, I'm in that two seat Lambo.</label>
                                        <textarea class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <button type="submit" class="btn btn-primary pull-right">Update Profile</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4 mt-5">
            <div class="card card-profile">
                <div class="card-avatar">
                    <a href="#pablo">
                        <img class="img" src="../assets/img/faces/marc.jpg" />
                    </a>
                </div>
                <div class="card-body">
                    <h6 class="card-category text-gray">CEO / Co-Founder</h6>
                    <h4 class="card-title">Alec Thompson</h4>
                    <p class="card-description">
                        Don't be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...
                    </p>
                    <a href="#pablo" class="btn btn-primary btn-round">Follow</a>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection
@push('scripts')
 <!-- <script src="//code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="{!! asset('js/jquery-ui.min.js') !!}"></script>
<script type="text/javascript">
// $( function() {
$(document).ready(function(){
    $( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd', maxDate: '-1D',changeMonth:true,changeYear:true,yearRange: "-100:+0"});
});
</script>
@endpush