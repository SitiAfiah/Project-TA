<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>TapakMP</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/logo1.jpg') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Constructo
  * Template URL: https://bootstrapmade.com/constructo-bootstrap-construction-template/
  * Updated: Aug 30 2025 with Bootstrap v5.3.8
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center dark-background">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">mpcabangjember@gmail.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>081776198245</span></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <a href="https://www.facebook.com/share/1ATYmi4SUw/" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/mpcabangjember?igsh=cnA1andsNno4NXUx" class="instagram"><i class="bi bi-instagram"></i></a>
          {{-- <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a> --}}
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-cente">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="{{ asset('assets/img/logo.webp') }}" alt=""> -->
          <h1 class="sitename">TapakMP</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero" class="active">Home</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#services">Fitur Utama</a></li>
            <li><a href="#call-to-action">Kontak</a></li>

          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>

    </div>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-right" data-aos-delay="200">
              <span class="subtitle">PPS Betako Merpati Putih Cabang Jember</span>
              <h1>Langkah Baru Tata Kelola PPS Betako Merpati Putih Cabang Jember.</h1>
              <p>Membangun organisasi yang lebih tertib, transparan, dan terintegrasi melalui TapakMP.</p>

              <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn-primary">Masuk Sistem</a>
              </div>

              <div class="trust-badges">
                <div class="badge-item">
                  <i class="bi bi-building-check"></i>
                  <div class="badge-text">
                    <span class="count">25+</span>
                    <span class="label">Years Experience</span>
                  </div>
                </div>
                <div class="badge-item">
                  <i class="bi bi-trophy"></i>
                  <div class="badge-text">
                    <span class="count">300+</span>
                    <span class="label">Anggota Aktif</span>
                  </div>
                </div>
                <div class="badge-item">
                  <i class="bi bi-people"></i>
                  <div class="badge-text">
                    <span class="count">12+</span>
                    <span class="label">Kolat Aktif</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="hero-image">
              <img src="{{ asset('assets/img/construction/mpfoto.jpeg') }}" alt="fotoMP" class="img-fluid">
              <div class="image-badge">
                <span></span>
                <p>Pewaris Tradisi Bangsa</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center g-5">
          <div class="col-lg-6">
            <div class="about-content" data-aos="fade-right" data-aos-delay="200">
              <h2>Melestarikan Tradisi, Membentuk Karakter Bangsa</h2>
              <p class="lead">PPS Betako Merpati Putih Cabang Jember terus berkomitmen untuk mencetak pesilat yang tangguh secara fisik dan mental.
                Seiring dengan perkembangan zaman, kami menghadirkan TapakMP sebagai langkah nyata digitalisasi organisasi untuk mempermudah manajemen
                anggota dan transparansi keuangan di seluruh wilayah Jember</p>
              <p>Melalui sistem ini, setiap anggota dan pengurus dapat saling terintegrasi dalam satu platform yang akurat. Kami percaya bahwa tata kelola
                yang tertib adalah fondasi utama untuk mencapai prestasi yang lebih tinggi, baik di tingkat daerah maupun nasional.</p>
              <div class="achievement-boxes row g-4 mt-4">
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
                  <div class="achievement-box">
                    <h3>300+</h3>
                    <p>Anggota Aktif</p>
                  </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="500">
                  <div class="achievement-box">
                    <h3>100%</h3>
                    <p>Warisan Asli</p>
                  </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="600">
                  <div class="achievement-box">
                    <h3>12</h3>
                    <p>Kelompok Latihan</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="about-image position-relative" data-aos="fade-left" data-aos-delay="200">
              <img src="{{ asset('assets/img/construction/gambar3.jpeg') }}" alt="Construction Team" class="img-fluid main-image rounded">
              <div class="image-overlay">
                <img src="{{ asset('assets/img/construction/gambar2.jpeg') }}" alt="Construction Project" class="img-fluid rounded">
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Fitur Utama</h2>
        <p>Fitur utama dari website ni merupakan sebagai berikut : </p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-building"></i>
              </div>
              <h3>Manajemen Kenaggotaan</h3>
              <p>Kelola data profil seluruh pesilat Merpati Putih Cabang Jember secara digital untuk memudahkan koordinasi antar kelompok latihan (Kolat).</p>
              <div class="service-features">
                <span><i class="bi bi-check-circle"></i> Profil Digital Anggota</span>
                <span><i class="bi bi-check-circle"></i> Update Status Keanggotaan</span>
                <span><i class="bi bi-check-circle"></i> Informasi Kolat Asal</span>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="service-card featured">
              <div class="service-badge">Transparasi Keuangan</div>
              <div class="service-icon">
                <i class="bi bi-house"></i>
              </div>
              <h3>Laporan Keuangan</h3>
              <p>Pantau pergerakan iuran dan kas organisasi secara transparan. Memastikan pengelolaan dana cabang terdokumentasi dengan baik.</p>
              <div class="service-features">
                <span><i class="bi bi-check-circle"></i> Catatan Iuran SPP</span>
                <span><i class="bi bi-check-circle"></i> Rekapitulasi Kas Cabang</span>
                <span><i class="bi bi-check-circle"></i> Laporan Dana Masuk dan Keluar</span>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-gear"></i>
              </div>
              <h3>Presensi Latihan Rutin</h3>
              <p>Sistem absensi digital untuk mencatat kehadiran anggota di setiap sesi latihan guna memantau keaktifan pesilat di tiap Kolat.</p>
              <div class="service-features">
                <span><i class="bi bi-check-circle"></i> Input Kehadiran Cepat</span>
                <span><i class="bi bi-check-circle"></i> Rekap Absensi Bulanan</span>
                <span><i class="bi bi-check-circle"></i> Pantau Keaktifan Anggota</span>
              </div>
            </div>
          </div><!-- End Service Item -->
        </div>

    </section><!-- /Services Section -->

    <!-- Projects Section -->
    <section id="projects" class="projects section">

      <!-- Section Title -->
      <!-- End Section Title -->

    <!-- /Projects Section -->

    <!-- Testimonials Section -->
    <!-- /Testimonials Section -->

    <!-- Certifications Section -->
    <section id="certifications" class="certifications section">

      <!-- Section Title -->
      <!-- End Section Title -->

      <!-- /Certifications Section -->

    <!-- Team Section -->
    <!-- /Team Section -->

    <!-- Call To Action Section -->
   <section id="call-to-action" class="call-to-action section light-background">
  <div class="container" data-aos="fade-up">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">

        <!-- Badge Status -->
        <div class="badge-wrapper mb-3">
          <span class="cta-badge" style="background: rgba(var(--accent-color-rgb), 0.1); padding: 8px 20px; border-radius: 50px; font-size: 14px; font-weight: 600; color: var(--accent-color);">
            <i class="bi bi-shield-check"></i> Terintegrasi & Aman Sejak 2026
          </span>
        </div>

        <!-- Heading & Deskripsi -->
        <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 20px;">Siap Melangkah Bersama TapakMP?</h2>
        <p class="lead mb-4">
          Pastikan data Anda atau Kelompok Latihan (Kolat) terdata secara resmi di Cabang Jember.
          Hubungi admin kami untuk bantuan pendaftaran akun baru, kendala akses, atau panduan sistem.
        </p>

        <!-- Feature Highlights (Inline) -->
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
          <div class="highlight-item"><i class="bi bi-check-circle-fill text-success"></i> Akun Anggota</div>
          <div class="highlight-item"><i class="bi bi-check-circle-fill text-success"></i> Dukungan Teknis</div>
          <div class="highlight-item"><i class="bi bi-check-circle-fill text-success"></i> Panduan Admin</div>
        </div>

        <!-- Tombol Kontak Utama -->
        <div class="cta-actions">
          <a href="https://wa.me/6281617376290" class="btn btn-primary btn-lg px-5 py-3 shadow-sm" target="_blank" rel="noopener noreferrer" style="border-radius: 50px; font-weight: 600;">
            <i class="bi bi-whatsapp me-2"></i> Hubungi Admin Melalui WhatsApp
          </a>
          <p class="mt-3 text-muted small">
            <i class="bi bi-clock me-1"></i> Jam Operasional Admin: <strong>08.00 - 17.00 WIB</strong>
          </p>
        </div>

      </div>
    </div>
  </div>
</section><!-- /Call To Action Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">

        <!-- Bagian Tentang (About) -->
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">TapakMP</span>
          </a>
          <p>Sistem informasi manajemen terpadu PPS Betako Merpati Putih Cabang Jember. Mewujudkan transparansi data anggota, administrasi keuangan, dan absensi dalam satu genggaman.</p>
          <div class="social-links d-flex mt-4">
            <a href="https://www.instagram.com/mpcabangjember?igsh=cnA1andsNno4NXUx"><i class="bi bi-instagram"></i></a>
            <a href="https://youtube.com/@ppsbetakomerpatiputihcaban8671?si=7332dwKZXmF5_auT"><i class="bi bi-youtube"></i></a>
            <a href="https://www.facebook.com/share/1ATYmi4SUw/"><i class="bi bi-facebook"></i></a>
          </div>
        </div>

        <!-- Tautan Penting -->
        {{-- <div class="col-lg-2 col-6 footer-links">
          <h4>Navigasi</h4>
          <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Tentang Kami</a></li>
            <li><a href="#">Data Kolat</a></li>
            <li><a href="#">Jadwal Latihan</a></li>
            <li><a href="#">Kebijakan Privasi</a></li>
          </ul>
        </div> --}}

        <!-- Fitur Sistem -->
        <div class="col-lg-3 col-3 footer-links">
          <h4>Fitur Utama</h4>
          <ul>
            <li><a href="#">Manajemen Anggota</a></li>
            <li><a href="#">Iuran SPP & Keuangan</a></li>
            <li><a href="#">Presensi Digital</a></li>
            <li><a href="#">Laporan Cabang</a></li>
            <li><a href="#">Database Pelatih</a></li>
          </ul>
        </div>

        <!-- Informasi Kontak Cabang -->
        <div class="col-lg-4 col-md-12 footer-contact text-center text-md-start">
          <h4>Kontak Cabang</h4>
          <p>Sekretariat Merpati Putih Jember</p>
          <p>Jember, Jawa Timur</p>
          <p>Indonesia</p>
          <p class="mt-4"><strong>WhatsApp:</strong> <span>+62 816-1737-6290</span></p>
          <p><strong>Email:</strong> <span>admin@tapakmp.com</span></p>
        </div>

      </div>
    </div>

    <!-- Copyright -->
    <div class="container copyright text-center mt-4">
      <p>© 2026 <span>Copyright</span> <strong class="px-1 sitename">TapakMP</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Dikembangkan oleh <a href="#">Siti</a> | Cabang Jember
      </div>
    </div>

</footer>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>
