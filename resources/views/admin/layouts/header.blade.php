@php
    use App\Models\Alert;
    $count = Alert::where('read', false)->count();
    $alerts = Alert::where('read', false)->limit(5)->get();
@endphp
<!--  Header Start -->
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                @yield('title')
              </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end"> 
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-bell-ringing"></i>
                    @if($count > 0)
                        <div class="notification bg-primary rounded-circle"></div>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" style="width:300px" aria-labelledby="drop1">
                    <div class="message-body">
                        @if($count > 0)
                            <a class="d-flex align-items-center gap-2 dropdown-item">
                                <p class="my-2 fs-4 text-center m-auto"><b>Vous avez {{ $count }} notifications non lues</b></p>
                            </a>
                            @foreach($alerts as $alert)
                                <a class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-mail fs-8"></i>
                                    <p class="my-1 fs-4">{{ substr($alert->alert, 0, 25) }} ... <br>
                                    <b class="fs-2">{{ $alert->created_at }}</b></p>
                                </a>
                            @endforeach
                        @else
                            <a class="d-flex align-items-center gap-2 dropdown-item">
                                <p class="my-6 fs-4 text-center m-auto">Aucune notification</p>
                            </a>
                        @endif

                        <a href="{{ route('admin.notifications') }}" class="btn btn-outline-primary mx-3 mt-2 d-block">Notifications </a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="{{asset(auth()->user()->url_profile ?: 'profile/user-1.jpg' )}}" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                    <div class="message-body">
                    <a href="{{route('admin.profil')}}" class="d-flex align-items-center gap-2 dropdown-item">
                        <i class="ti ti-user fs-6"></i>
                        <p class="mb-0 fs-3">Mon Profil</p>
                    </a>
                    <a href="{{route('logout')}}" class="btn btn-outline-danger mx-3 mt-2 d-block">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
        </div>
    </nav>
</header>
<!--  Header End -->