<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ asset('asset/img/logo.png') }}" alt="">
            <span class="d-none d-lg-block">TapakMP</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    {{-- <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar --> --}}

    <nav class="header-nav ms-auto"> 
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->

            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    {{-- Menampilkan angka total notifikasi --}}
                    @if (isset($globalUnreadCount) && $globalUnreadCount > 0)
                        <span class="badge bg-primary badge-number">{{ $globalUnreadCount }}</span>
                    @elseif(isset($globalNotifications) && count($globalNotifications) > 0)
                        <span class="badge bg-primary badge-number">{{ count($globalNotifications) }}</span>
                    @endif
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications shadow">
                    <li class="dropdown-header">
                        @php
                            $totalNotif =
                                isset($globalUnreadCount) && $globalUnreadCount > 0
                                    ? $globalUnreadCount
                                    : (isset($globalNotifications)
                                        ? count($globalNotifications)
                                        : 0);
                        @endphp
                        Anda memiliki {{ $totalNotif }} notifikasi baru
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Lihat Semua</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- Loop Notifikasi Dinamis --}}
                    @if (isset($globalNotifications) && count($globalNotifications) > 0)
                        @foreach ($globalNotifications as $notif)
                            <li class="notification-item">
                                <a href="{{ $notif['link'] }}" class="d-flex text-decoration-none">
                                    <i class="bi {{ $notif['icon'] }} me-3"></i>
                                    <div>
                                        <h4 class="text-dark">{{ $notif['title'] }}</h4>
                                        {{-- Mendukung key 'msg' atau 'message' agar tidak error --}}
                                        <p class="text-muted small mb-0">{{ $notif['msg'] ?? ($notif['message'] ?? '') }}
                                        </p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endforeach
                    @else
                        <li class="notification-item text-center">
                            <p class="text-muted small mb-0 p-3">Tidak ada notifikasi baru</p>
                        </li>
                    @endif

                    <li class="dropdown-footer">
                        <a href="#">Tampilkan semua notifikasi</a>
                    </li>
                </ul><!-- End Notification Dropdown Items -->
            </li><!-- End Notification Nav -->


            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                    @if (Auth::user()->anggota?->foto_profil)
                        <img src="{{ asset('storage/' . Auth::user()->anggota->foto_profil) }}" alt="Profile"
                            class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->anggota?->nama_lengkap ?? Auth::user()->email) }}&background=e9ecef&color=0d6efd"
                            alt="Profile" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                    @endif

                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        {{ Auth::user()->anggota?->nama_lengkap ?? Auth::user()->email }}
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ Auth::user()->anggota?->nama_lengkap ?? Auth::user()->email }}</h6>
                        <span>{{ Auth::user()->role?->nama_role ?? 'Pengguna' }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    {{--
            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li> --}}

                    {{-- <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li> --}}

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header>
