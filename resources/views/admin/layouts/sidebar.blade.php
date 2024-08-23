    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="#" class="text-nowrap logo-img align-items-center mt-3">
            <h2 class="text-light text-center">IYALO<span class="text-primary">.</span></h2>
          </a>
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
              <a class="sidebar-link  @yield('clients')" href="{{route('admin.clients')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu" >Clients</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link  @yield('announcer')" href="{{route('admin.announcers')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu" >Annonceurs</span>
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
              <a class="sidebar-link  @yield('categories')" href="{{route('admin.categories')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Catégories</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link @yield('properties')" href="{{route('admin.properties')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-list"></i>
                </span>
                <span class="hide-menu">Propriétés</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link @yield('visits')" href="{{route('admin.visits')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-grid"></i>
                </span>
                <span class="hide-menu">Bilans Entrants</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link  @yield('withdraws')" href="{{route('admin.withdraws')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-list"></i>
                </span>
                <span class="hide-menu">Bilans Sortants</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link  @yield('notes')" href="{{route('admin.notes')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-message"></i>
                </span>
                <span class="hide-menu">Notes</span>
              </a>
            </li>
            <li class="sidebar-item ">
              <a class="sidebar-link text-danger" href="{{ route('auth.logout') }}" aria-expanded="false">
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