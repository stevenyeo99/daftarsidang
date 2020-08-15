<header class="main-header">
  <!-- Logo -->
  <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>UIB</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>UIB</b></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{-- <img src="{{ Auth::guard()->user()->image_url }}" class="user-image" alt="User Image"> --}}
            {{-- <img src="../../../../assets/img/user2-160x160.jpg" class="user-image" alt="User Image"> --}}
            <img src="../../../../../../../assets/img/big-uib.png" class="user-image" alt="User Image">
            <span class="hidden-xs">Hi, {{ Auth::guard()->user()->username != null ? Auth::guard()->user()->username : Auth::guard('student')->user()->name }} </span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              {{-- <img src="{{ Auth::guard()->user()->image_url }}" class="img-circle" alt="User Image"> --}}
              {{-- <img src="../../../../assets/img/user2-160x160.jpg" class="img-circle" alt="User Image"> --}}
              <img src="../../../../../../../assets/img/big-uib.png" class="img-circle" alt="User Image">

              <p>
                {{ Auth::guard()->user()->username != null ? Auth::guard()->user()->username : Auth::guard('student')->user()->name }}
                <small>
                  {{ Auth::guard()->user()->username != null ? 'UIB\'s Staff' : 'UIB\'s Student' }}
                </small>
              </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              {{-- <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat">Profil</a>
              </div> --}}
              <div class="center">
                <a
                href="javascript:;"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="btn btn-default btn-flat">Sign out</a>

                @if (Auth::guard('web')->check())
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                @elseif(Auth::guard('prodis')->check())
                  <form id="logout-form" action="{{ route('prodi.logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                @elseif(Auth::guard('finance')->check())
                  <form id="logout-form" action="{{ route('finance.logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                @elseif(Auth::guard('lib')->check())
                  <form id="logout-form" action="{{ route('library.logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                @else
                  <form id="logout-form" action="{{ route('student.logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                @endif
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>