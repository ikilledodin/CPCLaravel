@extends('master')
@section('title', 'Dashboard')
@section('program_title', $companyinfo['description'])
@section('user_fullname', $email)
@section('user_email', $fullname)
@section('avatar',$avatar)
@section('content')
@push('body-class', 'theme-red')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>DASHBOARD</h2>
        </div>
       <!-- Widgets -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">directions_run</i>
                    </div>
                    <div class="content">
                        <div class="text">STEPS</div>
                        <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">{!! $dashboardinfo['todaySteps'] !!}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">fitness_center</i>
                    </div>
                    <div class="content">
                        <div class="text">ACTIVE CALORIES</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">{!! $dashboardinfo['todayCalories'] !!}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">transfer_within_a_station</i>
                    </div>
                    <div class="content">
                        <div class="text">DISTANCE COVERED</div>
                        <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20">{!! $dashboardinfo['todayDist'] !!} km</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">clear_all</i>
                    </div>
                    <div class="content">
                        <div class="text">FLOORS</div>
                        <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20">{!! $dashboardinfo['todayFloor'] !!}</div>
                    </div>
                </div>
            </div>
        </div>
          <!-- #END# Widgets -->
        <div class="row clearfix">
            <!-- Visitors -->
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card">
                    <div class="body bg-pink">
                        <div class="m-b--35 font-bold">STEPS (last 7 days)</div>
                        <br><br>
                        <div class="sparkline" data-type="line" data-spot-Radius="4" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#fff"
                             data-min-Spot-Color="rgb(255,255,255)" data-max-Spot-Color="rgb(255,255,255)" data-spot-Color="rgb(255,255,255)"
                             data-offset="90" data-width="100%" data-height="92px" data-line-Width="2" data-line-Color="rgba(255,255,255,0.7)"
                             data-fill-Color="rgba(0, 188, 212, 0)">
                            <!-- 12,10,9,6,5,6,10 -->{!! $dashboardinfo['stepstostring'] !!}
                        </div>
                        <ul class="dashboard-stat-list">
                            <li>
                                TODAY
                                <span class="pull-right"><b>{!! $dashboardinfo['todaySteps'] !!}</b> <small>STEPS</small></span>
                            </li>
                            <li>
                                YESTERDAY
                                <span class="pull-right"><b>{!! $dashboardinfo['yesterdaySteps'] !!}</b> <small>STEPS</small></span>
                            </li>
                            <li>
                                7 DAYS TOTAL
                                <span class="pull-right"><b>{!! $dashboardinfo['stepsdatasum'] !!}</b> <small>STEPS</small></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #END# Visitors -->
            <!-- Latest Social Trends -->
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card">
                     <div class="body bg-cyan">
                        <div class="m-b--35 font-bold">ACTIVE CALORIES (last 7 days)</div>
                        <br><br>
                        <div class="sparkline" data-type="line" data-spot-Radius="4" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#fff"
                             data-min-Spot-Color="rgb(255,255,255)" data-max-Spot-Color="rgb(255,255,255)" data-spot-Color="rgb(255,255,255)"
                             data-offset="90" data-width="100%" data-height="92px" data-line-Width="2" data-line-Color="rgba(255,255,255,0.7)"
                             data-fill-Color="rgba(0, 188, 212, 0)">
                            <!-- 12,10,9,6,5,6,10 -->{!! $dashboardinfo['calorietostring'] !!}
                        </div>
                        <ul class="dashboard-stat-list">
                            <li>
                                TODAY
                                <span class="pull-right"><b>{!! $dashboardinfo['todayCalories'] !!}</b> <small>ACTIVE CAL</small></span>
                            </li>
                            <li>
                                YESTERDAY
                                <span class="pull-right"><b>{!! $dashboardinfo['yesterdayCalories'] !!}</b> <small>ACTIVE CAL</small></span>
                            </li>
                            <li>
                                7 DAYS TOTAL
                                <span class="pull-right"><b>{!! $dashboardinfo['caloriedatasum'] !!}</b> <small>ACTIVE CAL</small></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #END# Latest Social Trends -->
            <!-- Answered Tickets -->
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card">
                     <div class="body bg-teal">
                        <div class="m-b--35 font-bold">DISTANCE (last 7 days)</div>
                        <br><br>
                        <div class="sparkline" data-type="line" data-spot-Radius="4" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#fff"
                             data-min-Spot-Color="rgb(255,255,255)" data-max-Spot-Color="rgb(255,255,255)" data-spot-Color="rgb(255,255,255)"
                             data-offset="90" data-width="100%" data-height="92px" data-line-Width="2" data-line-Color="rgba(255,255,255,0.7)"
                             data-fill-Color="rgba(0, 188, 212, 0)">
                            <!-- 12,10,9,6,5,6,10 -->{!! $dashboardinfo['disttostring'] !!}
                        </div>
                        <ul class="dashboard-stat-list">
                            <li>
                                TODAY
                                <span class="pull-right"><b>{!! $dashboardinfo['todayDist'] !!}</b> <small>km</small></span>
                            </li>
                            <li>
                                YESTERDAY
                                <span class="pull-right"><b>{!! $dashboardinfo['yesterdayDist'] !!}</b> <small>km</small></span>
                            </li>
                            <li>
                                7 DAYS TOTAL
                                <span class="pull-right"><b>{!! $dashboardinfo['distdatasum'] !!}</b> <small>km</small></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #END# Answered Tickets -->
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
<script type="text/javascript">
window.Echo.private('d_pm.{!! Auth::user()->id !!}')
.listen('DPMsAdded', (e) => {
  console.log('dpmsadded triggered');
  console.log(e);
});
</script>
@endpush