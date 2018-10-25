@extends('master')
@push('styles')
<style>
#carouselExampleIndicators .carousel-indicators li {
    /*background-color: #669966;*/
    display: none;
}
.carousel-inner img {
  margin: auto;
}
/*
.carousel-control-prev-icon {
  background-color: #669966 !important;
}
.carousel-control-next-icon {
  background-color: #669966 !important;
}
*/
.dashboardinfo {
  width:600px;
  background-image: linear-gradient(to bottom, #4facfe 0%, #00f2fe 100%);;
  /*display: inline;*/
  height: auto;
  text-align: center;
  margin: 0px auto;
  border-radius:1%;
}

.carouselExampleIndicators .carousel-inner > .item > img {
/*.carousel-inner > .item > a > img{*/
/*width: 100%;  use this, or not */
margin: auto;
}

.infostream {
  width:500px;
  /*height:auto;*/
  /*display: inline;*/
  /*height: auto;*/
  text-align: center;
  margin: 1rem auto
}
.carouselExampleIndicators2 .carousel-inner > .item > img{
  max-width:480px !important;
  max-height:270px !important;
}
.dashfooter {
  margin: 30px auto;
}
.stepsgaugeitem {
    position: absolute;
    left: 6.5em;
    top: 3em;
    clear:both;
}
.calsgaugeitem {
    position: absolute;
    left: 4em;
    top: 3em;
    clear:both;
}
.gaugeitem img {
  max-width: 40px;
}
#gaugecaption {
    display: block;
    font-weight: 500;
    color: #555;
    text-transform: uppercase;
    padding-top:10px;
    border-bottom:2px #555 solid;
}
#gaugecount {
    display: block;
    /*font-weight: 500;*/
    font-size:1.5em;
    color: #555;
    text-transform: uppercase;
    padding-top:5px;
    /*border-bottom:2px #555 solid;*/
}

.yesterdayitem {
   /* position: absolute;
    left: 6em;
    bottom: 3em;
    clear:both;*/
}

#yesterdaycaption {
    display: block;
    font-weight: 400;
    color: #555;
    text-transform: uppercase;
    /*padding-top:10px;*/
    font-size:0.7em;
    /*border-bottom:2px #555 solid;*/
}
#yesterdaycount {
    display: block;
    /*font-weight: 500;*/
    font-size:1.5em;
    color: #555;
    text-transform: uppercase;
    padding-top:5px;
    /*border-bottom:2px #555 solid;*/
}

.footeritem img {
  max-width: 40px;
}

</style>
@endpush
@section('title', 'Home')
@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="dashboardinfo col-md-8 offset-md-2 mt-5">
          <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-interval="false" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner dashboardsection">
              <div class="carousel-item active">
                <!-- <img class="d-block w-100" src="http://via.placeholder.com/600x400" alt="First slide"> -->
                <div class="row mt-5">
                  <div class="col-md-4 offset-md-4 text-center dashgauge">
                    <canvas id="stepsgauge" width="200" height="200"></canvas>
                    <div class="gaugeitem stepsgaugeitem">
                      <img src="{!! asset('img/dashboard/steps.png') !!}">
                      <span id="gaugecaption">STEPS</span>
                      <!-- <hr> -->
                      <span id="gaugecount">{!! $dashboardinfo['todaySteps'] !!}</span>
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <!-- <img class="d-block w-100" src="http://via.placeholder.com/600x400" alt="Second slide"> -->
                <div class="row mt-5">
                  <div class="col-md-4 offset-md-4 text-center dashgauge">
                    <canvas id="calsgauge" width="200" height="200"></canvas>
                    <div class="gaugeitem calsgaugeitem">
                      <img src="{!! asset('img/dashboard/calories.png') !!}">
                      <span id="gaugecaption">Active Calories</span>
                      <!-- <hr> -->
                      <span id="gaugecount">{!! $dashboardinfo['todayCalories'] !!}</span>
                    </div>
                    
                  </div>
                </div>
              </div>
              <!-- <div class="carousel-item">
                <img class="d-block w-100" src="http://via.placeholder.com/600x400" alt="Third slide">
              </div> -->
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
            </a>
          </div>
          <div class="row">
            <div class="col-md-8 offset-md-2 text-center infostream">
              <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel2">
              <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators2" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators2" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators2" data-slide-to="2"></li>
              </ol>
              <div class="carousel-inner">
                <div class="carousel-item active infostream">
                  <img class="d-block" src="http://via.placeholder.com/480x270" alt="First slide">
                </div>
                <div class="carousel-item infostream">
                  <img class="d-block" src="http://via.placeholder.com/480x270" alt="Second slide">
                </div>
                <div class="carousel-item infostream">
                  <img class="d-block" src="http://via.placeholder.com/480x270" alt="Third slide">
                </div>
              </div>
              <!--
              <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>              
              </a>
              -->
            </div>

            </div>
          </div>
          <div class="row dashfooter">
            <div class="col-md-4 text-center">
              <div class="footeritem yesterdayitem">
                <img src="{!! asset('img/dashboard/yesterdaysteps.png') !!}">
                <span id="yesterdaycount">{!! $dashboardinfo['yesterdaySteps'] !!}</span>
                <span id="yesterdaycaption">Yesterday's Steps</span>
                <!-- <hr> -->
               
              </div>
              <!-- <span>Yesterday's Steps</span> -->
            </div>
             <div class="col-md-4 text-center">
              <!-- <span>Best Single Day</span> -->
              <div class="footeritem yesterdayitem">
                <img src="{!! asset('img/dashboard/singleday.png') !!}">
                <span id="yesterdaycount">24,500</span>
                <span id="yesterdaycaption">Best Single Day</span>
                <!-- <hr> -->
               
              </div>
            </div>
             <div class="col-md-4 text-center">
              <div class="footeritem yesterdayitem">
                <img src="{!! asset('img/dashboard/totalsteps.png') !!}">
                <span id="yesterdaycount">224,500</span>
                <span id="yesterdaycaption">Total Steps</span>
                <!-- <hr> -->
               
              </div>
              <!-- <span>Total Steps</span> -->
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
@push('scripts')
<script src="{!! asset('js/gauge.min.js') !!}"></script>
<script type="text/javascript">
var opts = {
angle: 0.35, // The span of the gauge arc
lineWidth: 0.1, // The line thickness
radiusScale: 1, // Relative radius
pointer: {
  length: 0.6, // // Relative to gauge radius
  strokeWidth: 0.035, // The thickness
  color: '#000000' // Fill color
},
limitMax: false,     // If false, max value increases automatically if value > maxValue
limitMin: false,     // If true, the min value of the gauge will be fixed
colorStart: '#6F6EA0',   // Colors
colorStop: '#abecd6',//'#C0C0DB',    // just experiment with them
strokeColor: '#EEEEEE',  // to see which ones work best for you
generateGradient: true,
highDpiSupport: true,     // High resolution support

};
var stepstarget = document.getElementById('stepsgauge'); // your canvas element
var stepsgauge = new Donut(stepstarget).setOptions(opts); // create sexy gauge!
stepsgauge.maxValue = 10000; // set max gauge value
stepsgauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
stepsgauge.animationSpeed = 32; // set animation speed (32 is default value)
stepsgauge.set({!! $dashboardinfo['todaySteps'] !!}); // set actual value

var calstarget = document.getElementById('calsgauge'); // your canvas element
var calsgauge = new Donut(calstarget).setOptions(opts); // create sexy gauge!
calsgauge.maxValue = 500; // set max gauge value
calsgauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
calsgauge.animationSpeed = 32; // set animation speed (32 is default value)
calsgauge.set({!! $dashboardinfo['todayCalories'] !!}); // set actual value
/*
window.Echo.channel('private-d_pm.{!! Auth::user()->id !!}')
    .listen('.DPMsAdded', (e) => {
        console.log(e.user);
    });
    */

window.Echo.private('d_pm.{!! Auth::user()->id !!}')
  .listen('DPMsAdded', (e) => {
    console.log('dpmsadded triggered');
    console.log(e);
});

</script>
@endpush