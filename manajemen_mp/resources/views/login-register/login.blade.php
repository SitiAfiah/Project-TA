<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login - TapakMP</title>

  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root {
      --accent-color: #feb900;
      --dark-blue: #06163a;
    }
    body {
      background: linear-gradient(rgba(6, 22, 58, 0.8), rgba(6, 22, 58, 0.8)), url('{{ asset("assets/img/construction/mpfoto.jpeg") }}') center/cover no-repeat;
      min-height: 100vh;
      display: flex;
      align-items: center;
      font-family: 'Roboto', sans-serif;
    }
    .auth-card {
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
      border-top: 5px solid var(--accent-color);
    }
    .auth-header {
      background: var(--dark-blue);
      padding: 30px;
      text-align: center;
      color: #fff;
    }
    .auth-header h2 {
      font-family: 'Ubuntu', sans-serif;
      font-weight: 700;
      margin: 0;
      letter-spacing: 1px;
    }
    .auth-body { padding: 40px; }
    .form-control:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 0 0.25rem rgba(254, 185, 0, 0.1);
    }
    .btn-auth {
      background-color: var(--accent-color);
      border: none;
      color: var(--dark-blue);
      font-weight: 700;
      padding: 12px;
      transition: 0.3s;
    }
    .btn-auth:hover {
      background-color: #e5a700;
      transform: translateY(-2px);
    }
    .back-home {
      color: #fff;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 20px;
    }
    .back-home:hover { color: var(--accent-color); }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <a href="/" class="back-home"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>

        <div class="auth-card">
          <div class="auth-header">
            <h1 class="h3 mb-1" style="color: var(--accent-color)">TapakMP</h1>
            <p class="small mb-0 text-white-50 text-uppercase">Portal Anggota Cabang Jember</p>
          </div>

          <div class="auth-body">
            <form action="{{ route('login') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="form-label fw-bold">Alamat Email</label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                  <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
              </div>

              <div class="mb-4">
                <div class="d-flex justify-content-between">
                  <label class="form-label fw-bold">Kata Sandi</label>
                  <a href="#" class="small text-muted text-decoration-none">Lupa Password?</a>
                </div>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-auth mb-3">MASUK SEKARANG</button>
              </div>

              <div class="text-center">
                <p class="small mb-0">Belum punya akun? <a href="/register" class="text-primary fw-bold text-decoration-none">Daftar di sini</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Popup jika berhasil register atau pesan sukses lainnya
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#06163a'
      });
    @endif

    // Popup jika gagal login atau akun non-aktif
    @if(session('error'))
      Swal.fire({
        icon: 'warning',
        title: 'Perhatian',
        text: "{{ session('error') }}",
        confirmButtonColor: '#06163a'
      });
    @endif
  </script>

</body>
</html>
