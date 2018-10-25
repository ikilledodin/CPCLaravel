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
          <h2>COMPANY LEADERBOARD</h2>
      </div>
      <!-- Striped Rows -->
      <div class="row clearfix">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                  <!-- <div class="header">
                      <h2>
                          STRIPED ROWS
                          <small>Use <code>.table-striped</code> to add zebra-striping to any table row within the <code>&lt;tbody&gt;</code></small>
                      </h2>
                      <ul class="header-dropdown m-r--5">
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
                      </ul>
                  </div> -->
                  <div class="body table-responsive">
                      <table class="table table-striped">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th>RANK</th>
                                  <th>NAME</th>
                                  <th>STEPS</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($leaderboard as $user)
                                <tr>
                                  <th class="avatarholder" scope="row"><img src="{!! $user->avatar ? asset('storage').'/'.$user->avatar : asset('storage').'/'.'avatars/avatar.png' !!}" class="avatarimg"></th>
                                  <td>{!! $loop->index+1 !!}</td>
                                  <td>{!! $user->fullname !!}</td>
                                  <td>{!! number_format($user->totalsteps) !!}</td>
                                  <!-- <td>@mdo</td> -->
                                </tr>
                            @endforeach
                              <!-- <tr>
                                  <th scope="row">1</th>
                                  <td>Mark</td>
                                  <td>Otto</td>
                                  <td>@mdo</td>
                              </tr>
                              <tr>
                                  <th scope="row">2</th>
                                  <td>Jacob</td>
                                  <td>Thornton</td>
                                  <td>@fat</td>
                              </tr>
                              <tr>
                                  <th scope="row">3</th>
                                  <td>Larry</td>
                                  <td>the Bird</td>
                                  <td>@twitter</td>
                              </tr>
                              <tr>
                                  <th scope="row">4</th>
                                  <td>Larry</td>
                                  <td>Jellybean</td>
                                  <td>@lajelly</td>
                              </tr>
                              <tr>
                                  <th scope="row">5</th>
                                  <td>Larry</td>
                                  <td>Kikat</td>
                                  <td>@lakitkat</td>
                              </tr> -->
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
      <!-- #END# Striped Rows -->
    </div>
</section>
@endsection