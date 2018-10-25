@extends('master')
@section('title', 'Profile')
@section('program_title', $companyinfo['description'])
@section('content')
@push('styles')
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
@push('body-class', 'theme-red')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>PROFILE</h2>
        </div>
       <!-- Basic Card -->
        <div class="row clearfix">
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Edit Profile <small>Complete your profile</small>
                        </h2>
                        <!-- <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else here</a></li>
                                </ul>
                            </li>
                        </ul> -->
                    </div>
                    <div class="body">
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
                            <div class="row clearfix">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <div class="form-line">
                                            <input type="text" name="email" class="form-control" value="{!! Auth::user()->email !!}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 offset-md-3">
                                    <label for="avatar">Profile Picture</label>
                                    <div class="form-group">
                                        <div class="form-line text-center">
                                            <img src="{!! $profileinfo->avatar ? asset('storage').'/'.$profileinfo['avatar'] : asset('storage').'/'.'avatars/avatar.png' !!}" class="user">
                                            <div class="text-center pt-2"><span> Edit </span></div>
                                            <input type="file" id="avatar" name="avatar">
                                        </div>
                                        <!-- <img src="http://placehold.it/400x200" class="user"> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row cleafix">
                                <div class="col-md-6">
                                    <label for="first_name">First Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="first_name" value="{!! $profileinfo->first_name ? $profileinfo->first_name : '' !!}" placeholder="Enter First Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <label for="last_name">Last Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="last_name" value="{!! $profileinfo->last_name ? $profileinfo->last_name : '' !!}" placeholder="Enter Last Name">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row cleafix">
                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input name="weightPref" type="radio" id="weightPref1" value="lbs" {!! $profileinfo->weight_pref == 'lbs' ? 'checked':'' !!} />
                                            <label for="weightPref1">lbs</label>
                                            <input name="weightPref" type="radio" id="weightPref2" value="kg" {!! $profileinfo->weight_pref == 'kg' ? 'checked':'' !!}/>
                                            <label for="weightPref2">kg</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="weight">Weight</label>
                                            <div class="form-line">
                                                <input type="text" name="weight"  value="{!! $profileinfo->weight ? $profileinfo->weight : '' !!}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input name="heightPref" type="radio" id="heightPref1" value="ft" {!! $profileinfo->height_pref == 'ft' ? 'checked':'' !!} />
                                            <label for="heightPref1">ft</label>
                                            <input name="heightPref" type="radio" id="heightPref2" value="cm" {!! $profileinfo->height_pref == 'cm' ? 'checked':'' !!} />
                                            <label for="heightPref2">cm</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="height">Height</label>
                                            <div class="form-line">
                                                <input type="text" name="height" value="{!! $profileinfo->height ? $profileinfo->height : '' !!}" class="form-control">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="gender">Gender</label>
                                    <select name="gender"  class="form-control show-tick">
                                        <option selected disabled>Choose...</option>
                                        <option value=0 {!! $profileinfo->gender == 0 ? 'selected':'' !!}>Male</option>
                                        <option value=1 {!! $profileinfo->gender == 1 ? 'selected':'' !!}>Female</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="row cleafix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <div class="form-line">
                                            <input type="text" name="city" value="{!! $profileinfo->city ? $profileinfo->city : '' !!}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <div class="form-line">
                                            <input type="text" name="country" value="{!! $profileinfo->country ? $profileinfo->country : '' !!}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                               <!--  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="birthdate">Birthdate</label>
                                        <div class="form-line">
                                            <input type="text" name="birthdate" value="{!! $profileinfo->birthdate ? $profileinfo->birthdate : '' !!}" id="datepicker" class="datepicker form-control">
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row clearfix">
                                <button type="submit" class="btn btn-primary pull-right">Update Profile</button>
                            </div>

                        </form>
                        <!-- Quis pharetra a pharetra fames blandit. Risus faucibus velit Risus imperdiet mattis neque volutpat, etiam lacinia netus dictum magnis per facilisi sociosqu. Volutpat. Ridiculus nostra. -->
                    </div>
                </div>
            </div>
        </div>
            <!-- #END# Basic Card -->
       
    </div>
</section>
@endsection
@push('scripts')
<!-- Select Plugin Js -->
<!-- <script src="{!! asset('adminbsb/plugins/bootstrap-select/js/bootstrap-select.js') !!}"></script> -->
<script src="{!! asset('adminbsb/plugins/autosize/autosize.js') !!}"></script>
<script src="{!! asset('adminbsb/plugins/momentjs/moment.js') !!}"></script>
<script src="{!! asset('adminbsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}"></script>
<script src="{!! asset('adminbsb/js/pages/forms/basic-form-elements.js') !!}"></script>

@endpush