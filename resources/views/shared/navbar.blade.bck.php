<!-- Navbar -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
<div class="container">

  <!-- Brand -->
  <a class="navbar-brand" href="/">
    <strong>HealthQuest by TupeloLife</strong>
  </a>

  <!-- Collapse -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
    aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Links -->
  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <!-- Left -->
    <ul class="navbar-nav mr-auto">
        @if (Auth::check())
      <li class="nav-item {!! strcmp(Route::current()->uri(),'dashboard') == 0 ? 'active':'' !!}">
        <a class="nav-link" href="/dashboard">Dashboard
            @if(!strcmp(Route::current()->uri(),'dashboard'))
            <span class="sr-only">(current)</span>
            @endif
        </a>
      </li>
      <li class="nav-item {!! strcmp(Route::current()->uri(),'leaderboard') == 0 ? 'active':'' !!}">
        <a class="nav-link" href="/leaderboard">Leaderboard</a>
            @if(!strcmp(Route::current()->uri(),'leaderboard'))
            <span class="sr-only">(current)</span>
            @endif
      </li>
      <li class="nav-item {!! strcmp(Route::current()->uri(),'challenges') == 0? 'active':'' !!}">
        <a class="nav-link" href="/challenges">Challenges</a>
            @if(!strcmp(Route::current()->uri(),'challenges'))
            <span class="sr-only">(current)</span>
            @endif
      </li>
      <li class="nav-item {!! strcmp(Route::current()->uri(),'newsfeed') == 0 ? 'active':'' !!}">
        <a class="nav-link" href="/newsfeed">NewsFeed</a>
            @if(!strcmp(Route::current()->uri(),'newsfeed'))
            <span class="sr-only">(current)</span>
            @endif
      </li>
      @endif
    </ul>

    <!-- Right -->
    <ul class="navbar-nav nav-flex-icons">
    @if(Auth::check())
        @role('SuperAdmin')
         <li class="nav-item">
            <a href="/admin" class="nav-link">Admin</a>
        </li>
        @endrole
        <li class="nav-item {!! strcmp(Route::current()->uri(),'users/profile') == 0 ? 'active':'' !!}">
            <a href="/users/profile" class="nav-link">
              Profile
            </a>
            @if(!strcmp(Route::current()->uri(),'users/profile'))
            <span class="sr-only">(current)</span>
            @endif
        </li>
        <li class="nav-item">
            <a href="/users/logout" class="nav-link">
              Logout
            </a>
        </li>
    @else
    
      <li class="nav-item">
        <a href="https://www.facebook.com/TupeloLife" class="nav-link" target="_blank">
          <i class="fa fa-facebook"></i>
        </a>
      </li>
      <li class="nav-item">
        <a href="https://twitter.com/TupeloLife" class="nav-link" target="_blank">
          <i class="fa fa-twitter"></i>
        </a>
      </li>
      <li class="nav-item">
        <a href="/login" class="nav-link border border-light rounded">
          <i class="fa fa-github mr-2"></i>Log In
        </a>
      </li>
    @endif
    </ul>
  </div>

</div>
</nav>
  <!-- Navbar -->
