<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/home">HealthQuest by TupeloLife</a>
        </div>

        <!-- Navbar Right -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                <li class="active"><a href="/dashboard">Dashboard</a></li>
                <li><a href="/leaderboard">Leaderboard</a></li>
                <li><a href="/challenges">Challenges</a></li>
                <li><a href="/tickets">NewsFeeds</a></li>
                @endif
                <!-- @if (Auth::check() && Auth::user()->hasRole('manager'))
                    <li><a href="/admin/posts/">Posts</a></li>
                    <li><a href="/admin/categories/">Categories</a></li>
                @endif -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Sign In 
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        @if (Auth::check())
                            @role('SuperAdmin')
                                <li><a href="/admin">Admin</a></li>
                            @endrole
                            <li><a href="/users/logout">Logout</a></li>
                        @else
                            <li><a href="/users/register">Register</a></li>
                            <li><a href="/users/login">Login</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>