<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs(['pelatih.*', 'anggota.*', 'kolat.*']) ? '' : 'collapsed' }}"
               data-bs-target="#anggota-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->routeIs(['pelatih.*', 'anggota.*', 'kolat.*']) ? 'true' : 'false' }}">
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

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs(['presensi.*', 'jadwal.*']) ? '' : 'collapsed' }}"
               data-bs-target="#latihan-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->routeIs(['presensi.*', 'jadwal.*']) ? 'true' : 'false' }}">
                <i class="bi bi-journal-check"></i><span>Manajemen Latihan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="latihan-nav" class="nav-content collapse {{ request()->routeIs(['presensi.*', 'jadwal.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('presensi.index') }}" class="{{ request()->routeIs('presensi.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Presensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Jadwal & Pengumuman</span>
                    </a>
                </li>
                <li><a href="#"><i class="bi bi-circle"></i><span>Penilaian Pelatih</span></a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#transaksi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-wallet2"></i><span>Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="transaksi-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li><a href="#"><i class="bi bi-circle"></i><span>Kas Cabang</span></a></li>
                <li><a href="#"><i class="bi bi-circle"></i><span>SPP Anggota</span></a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#laporan-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-bar-graph"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="laporan-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li><a href="#"><i class="bi bi-circle"></i><span>Laporan Anggota</span></a></li>
                <li><a href="#"><i class="bi bi-circle"></i><span>Laporan Keuangan</span></a></li>
                <li><a href="#"><i class="bi bi-circle"></i><span>Laporan Presensi</span></a></li>
                <li><a href="#"><i class="bi bi-circle"></i><span>Laporan Penilaian</span></a></li>
            </ul>
        </li>

        <li class="nav-heading">Akun</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#"><i class="bi bi-person"></i><span>Profile</span></a>
        </li>
        <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            <a class="nav-link collapsed" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i><span>Logout</span>
            </a>
        </li>
    </ul>
</aside>

{{-- SCRIPT PENTING: Supaya menu yang otomatis terbuka BISA DITUTUP KEMBALI --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cari semua dropdown yang punya class 'show' (dibuka otomatis oleh Laravel)
        var bootstrapCollapseMenus = document.querySelectorAll('.nav-content.collapse.show');

        bootstrapCollapseMenus.forEach(function (menuElement) {
            // Kita kasih tahu Bootstrap secara manual: "Woi, ini menu udah kebuka!"
            // Dengan inisialisasi ini, klik berikutnya akan menjalankan fungsi 'hide'
            new bootstrap.Collapse(menuElement, {
                toggle: false
            });
        });
    });
</script>
