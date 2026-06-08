@php
    // LOGIKA BARU: Deteksi multi-role dari tabel perantara (pivot) roles()
    $user = auth()->user();
    $userRole = 'Anggota'; // Default fallback

    if ($user && $user->anggota) {
        if ($user->anggota->roles->contains('nama_role', 'Pengurus')) {
            $userRole = 'Pengurus';
        } elseif ($user->anggota->roles->contains('nama_role', 'Pelatih')) {
            $userRole = 'Pelatih';
        }
    }
@endphp

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- ======================================================== --}}
        {{-- MENU DASHBOARD (Semua Role Bisa Akses)                       --}}
        {{-- ======================================================== --}}
        <li class="nav-item">
            @if($userRole === 'Anggota')
                <!-- Link khusus untuk Anggota -->
                <a class="nav-link {{ request()->routeIs('anggota.dashboard') ? '' : 'collapsed' }}" href="{{ route('anggota.dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span> 
                </a>
            @else
                <!-- Link untuk Pelatih dan Pengurus -->
                <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        </li>

        {{-- ======================================================== --}}
        {{-- MENU MANAJEMEN ANGGOTA (Hanya Pelatih & Pengurus)            --}}
        {{-- ======================================================== --}}
        @if($userRole == 'Pengurus' || $userRole == 'Pelatih')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs(['pelatih.*', 'anggota.*', 'kolat.*']) ? '' : 'collapsed' }}"
               data-bs-target="#anggota-nav" data-bs-toggle="collapse" href="#"
               aria-expanded="{{ request()->routeIs(['pelatih.*', 'anggota.*', 'kolat.*']) ? 'true' : 'false' }}">
                <i class="bi bi-people"></i><span>Manajemen Anggota</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="anggota-nav" class="nav-content collapse {{ request()->routeIs(['pelatih.*', 'anggota.*', 'kolat.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('pelatih.index') }}" class="{{ request()->routeIs('pelatih.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Data Pelatih</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('anggota.anggota') }}" class="{{ request()->routeIs('anggota.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Data Anggota</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kolat.index') }}" class="{{ request()->routeIs('kolat.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Data Kolat</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        {{-- ======================================================== --}}
        {{-- MENU MANAJEMEN LATIHAN (Semua Bisa Akses)                    --}}
        {{-- ======================================================== --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs(['presensi.*', 'jadwal.*', 'penilaian.*']) ? '' : 'collapsed' }}"
               data-bs-target="#latihan-nav" data-bs-toggle="collapse" href="#"
               aria-expanded="{{ request()->routeIs(['presensi.*', 'jadwal.*', 'penilaian.*']) ? 'true' : 'false' }}">
                <i class="bi bi-journal-check"></i><span>Manajemen Latihan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="latihan-nav" class="nav-content collapse {{ request()->routeIs(['presensi.*', 'jadwal.*', 'penilaian.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

                {{-- SUB-MENU ANGGOTA --}}
                @if($userRole == 'Anggota')
                    <li>
                        <a href="{{ route('presensi.anggota.index') }}" class="{{ request()->routeIs('presensi.anggota.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Kehadiran Latihan</span>
                        </a>
                    </li>
                    <!-- Link Penilaian masih manual (sesuaikan id pelatihnya nanti) -->
                    <li>
                        <!-- href saya ganti '#' sementara, karena anggota belum punya rute index penilaian sendiri -->
                        <a href="{{ route('penilaian.anggota_index') }}" class="{{ request()->routeIs('penilaian.anggota_index') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Isi Nilai Pelatih</span>
                        </a>
                    </li>

                {{-- SUB-MENU PELATIH & PENGURUS --}}
                @else
                    <li>
                        <a href="{{ route('presensi.index') }}" class="{{ request()->routeIs('presensi.index', 'presensi.kehadiran') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Presensi Anggota</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Jadwal Latihan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('penilaian.index') }}" class="{{ request()->routeIs('penilaian.index', 'penilaian.show') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Rekap Penilaian</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        {{-- ======================================================== --}}
        {{-- MENU TRANSAKSI (Semua Akses, Isi Berbeda)                    --}}
        {{-- ======================================================== --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs(['kas.*', 'spp.*']) ? '' : 'collapsed' }}"
               data-bs-target="#transaksi-nav" data-bs-toggle="collapse" href="#"
               aria-expanded="{{ request()->routeIs(['kas.*', 'spp.*']) ? 'true' : 'false' }}">
                <i class="bi bi-wallet2"></i><span>Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="transaksi-nav" class="nav-content collapse {{ request()->routeIs(['kas.*', 'spp.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

                {{-- Kas Cabang HANYA Pengurus --}}
                @if($userRole == 'Pengurus')
                    <li>
                        <a href="{{ route('kas.index') }}" class="{{ request()->routeIs('kas.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Kas Cabang</span>
                        </a>
                    </li>
                @endif

                {{-- SPP --}}
                <li>
                    @if($userRole == 'Anggota')
                        <a href="{{ route('spp.anggota.index') }}" class="{{ request()->routeIs('spp.anggota.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Tagihan SPP Saya</span>
                        </a>
                    @else
                        <a href="{{ route('spp.index') }}" class="{{ request()->routeIs('spp.index', 'spp.create') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Kelola SPP Anggota</span>
                        </a>
                    @endif
                </li>
            </ul>
        </li>

        {{-- ======================================================== --}}
        {{-- MENU LAPORAN (Hanya Pelatih & Pengurus)                      --}}
        {{-- ======================================================== --}}
        @if($userRole == 'Pengurus' || $userRole == 'Pelatih')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('laporan.*') ? '' : 'collapsed' }}"
               data-bs-target="#laporan-nav" data-bs-toggle="collapse" href="#"
               aria-expanded="{{ request()->routeIs('laporan.*', 'rekap.*') ? 'true' : 'false' }}">
                <i class="bi bi-file-earmark-bar-graph"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="laporan-nav" class="nav-content collapse {{ request()->routeIs('laporan.*', 'rekap.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

                {{-- Hanya Pengurus --}}
                @if($userRole == 'Pengurus')
                    <li>
                        <a href="{{ route('laporan.anggota') }}" class="{{ request()->routeIs('laporan.anggota') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Laporan Anggota</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.kas') }}" class="{{ request()->routeIs('laporan.kas') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Laporan Keuangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.spp') }}" class="{{ request()->routeIs('laporan.spp') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Laporan SPP</span>
                        </a>
                    </li>

                @endif

                {{-- Pengurus & Pelatih --}}
                <li>
                    <a href="{{ route('laporan.presensi') }}" class="{{ request()->routeIs('laporan.presensi') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Laporan Presensi</span>
                    </a>
                </li>
                 <li>
                        <a href="{{ route('rekap.index') }}" class="{{ request()->routeIs('rekap.index') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Rekap Kelayakan Anggota</span>
                        </a>
                    </li>

            </ul>
        </li>
        @endif

        {{-- ======================================================== --}}
        {{-- MENU AKUN (Semua Bisa Akses)                                 --}}
        {{-- ======================================================== --}}
        {{-- <li class="nav-heading mt-3">Akun</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('profile.edit') }}">
                <i class="bi bi-person"></i><span>Profile Saya</span>
            </a>
        </li>
        <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            <a class="nav-link collapsed text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i><span>Logout</span>
            </a>
        </li> --}}
    </ul>
</aside>
