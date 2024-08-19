    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <div class="row container mt-2">
            <div  class=" col-6">
              <a href="#">
                    <img src='{{asset("images/icon/icon.png")}}' alt="Smart Irrigation" width="80" height="auto"/>
                </a>
            </div>
            <div class="mt-3 align-items-center justify-content-between col-6">
              <h4 class="text-white fw-bolder " >Smart Irrigation</h4>
            </div>
            </div>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu text-success" >{{ auth()->user()->firstname }} {{ auth()->user()->lastname}}</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link @yield('dashboard')" href="{{route('admin.dashboard')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link  @yield('users')" href="{{route('admin.users')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu" >Utilisateurs</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link @yield('admin')" href="{{route('admin.admins')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-building-bank"></i>
                </span>
                <span class="hide-menu">Administrateurs</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link @yield('sites')" href="{{route('admin.sites')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-list"></i>
                </span>
                <span class="hide-menu">Sites</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link @yield('cultures')" href="{{route('admin.cultures')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-grid"></i>
                </span>
                <span class="hide-menu">Cultures</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link  @yield('vannes')" href="{{route('admin.vannes')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Vanne</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link  @yield('demos')" href="{{route('admin.contacts')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-list"></i>
                </span>
                <span class="hide-menu">Souscription</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link  @yield('comments')" href="{{route('admin.comments')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-message"></i>
                </span>
                <span class="hide-menu">Commentaires</span>
              </a>
            </li>
            <li class="sidebar-item ">
              <a class="sidebar-link text-danger" href="{{ route('logout') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-logout"></i>
                </span>
                <span class="hide-menu">Deconnexion</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->