<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Daftar Anggota - TapakMP</title>

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
      background: linear-gradient(rgba(6, 22, 58, 0.8), rgba(6, 22, 58, 0.8)),
      url('{{ asset("assets/img/construction/mpfoto.jpeg") }}') center/cover no-repeat;
      min-height: 100vh;
      display: flex;
      align-items: center;
      font-family: 'Roboto', sans-serif;
      padding: 20px 0;
    }

    .auth-card {
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      border-top: 5px solid var(--accent-color);
      display: flex;
      flex-direction: column;
      max-height: 90vh;
    }

    .auth-header {
      background: var(--dark-blue);
      padding: 20px;
      text-align: center;
      color: #fff;
      flex-shrink: 0;
    }

    .auth-body-scroll {
      padding: 25px 30px;
      overflow-y: auto;
      flex-grow: 1;
    }

    .auth-footer {
      padding: 15px 30px;
      background: #f8f9fa;
      border-top: 1px solid #eee;
      flex-shrink: 0;
    }

    .section-title {
      color: var(--dark-blue);
      border-left: 4px solid var(--accent-color);
      padding-left: 12px;
      margin: 20px 0 15px 0;
      font-size: 0.85rem;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 1px;
      display: table;
      line-height: 1;
      padding-top: 2px;
      padding-bottom: 2px;
    }

    .form-control:focus, .form-select:focus {
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
      width: 100%;
    }

    .btn-auth:hover {
      background-color: #e5a700;
      transform: translateY(-2px);
    }

    .back-home {
      color: #fff;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 15px;
      font-size: 0.9rem;
    }

    .back-home:hover { color: var(--accent-color); }

    .auth-body-scroll::-webkit-scrollbar { width: 5px; }
    .auth-body-scroll::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <a href="/" class="back-home"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>

        <div class="auth-card">
          <div class="auth-header">
            <h1 class="h4 mb-1" style="color: var(--accent-color); font-family: 'Ubuntu', sans-serif; font-weight: 700;">TapakMP</h1>
            <p class="small mb-0 text-white-50 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Pendaftaran Anggota Baru</p>
          </div>

          <form action="{{ route('register') }}" method="POST" class="d-flex flex-column" style="overflow: hidden;">
            @csrf

            <div class="auth-body-scroll">
              <div class="section-title" style="margin-top: 0;">1. Informasi Akun</div>

              <div class="mb-3">
                <label class="form-label fw-bold small">Alamat Email</label>
                <div class="input-group">
                  <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                  <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>
                </div>
              </div>

              <div class="row g-2">
                <div class="col-6 mb-3">
                  <label class="form-label fw-bold small">Kata Sandi</label>
                  <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label fw-bold small">Konfirmasi</label>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
              </div>

              <div class="section-title">2. Data Pribadi</div>

              <div class="mb-3">
                <label class="form-label fw-bold small">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" placeholder="Sesuai KTP/KTA" required>
              </div>

              <div class="row g-2">
                <div class="col-6 mb-3">
                  <label class="form-label fw-bold small">No. WhatsApp</label>
                  <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="0812..." required>
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label fw-bold small">Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-select" required>
                    <option value="" selected disabled>Pilih...</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                </div>
              </div>

              <div class="row g-2">
                <div class="col-7 mb-3">
                  <label class="form-label fw-bold small">Tempat Lahir</label>
                  <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" required>
                </div>
                <div class="col-5 mb-3">
                  <label class="form-label fw-bold small">Tgl Lahir</label>
                  <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat domisili saat ini..." required>{{ old('alamat') }}</textarea>
              </div>

              <div class="section-title">3. Organisasi & Medis</div>

              <div class="row g-2">
                <div class="col-6 mb-3">
                  <label class="form-label fw-bold small">Asal Kolat</label>
                  <select name="kolat_id" class="form-select" required>
                    @foreach($data_kolat as $kolat)
                      <option value="{{ $kolat->id }}" {{ old('kolat_id') == $kolat->id ? 'selected' : '' }}>{{ $kolat->nama_kolat }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label fw-bold small">Tingkatan Sabuk</label>
                  <input type="text" name="tingkatan" class="form-control" value="{{ old('tingkatan') }}" placeholder="Contoh: Dasar I" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small">Tanggal Gabung</label>
                <input type="date" name="tgl_gabung" class="form-control" value="{{ old('tgl_gabung') }}" required>
              </div>

              <div class="mb-0">
                <label class="form-label fw-bold small">Catatan Medis (Opsional)</label>
                <textarea name="catatan_medis" class="form-control" rows="2" placeholder="Riwayat cedera atau penyakit...">{{ old('catatan_medis') }}</textarea>
              </div>
            </div>

            <div class="auth-footer">
              <button type="submit" class="btn btn-auth mb-3 shadow-sm">DAFTAR SEKARANG</button>
              <div class="text-center">
                <p class="small mb-0">Sudah punya akun? <a href="/login" class="text-primary fw-bold text-decoration-none">Masuk di sini</a></p>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Popup jika ada error validasi dari Laravel
    @if($errors->any())
      Swal.fire({
        icon: 'error',
        title: 'Ups...',
        html: '{!! implode("<br>", $errors->all()) !!}',
        confirmButtonColor: '#06163a'
      });
    @endif
  </script>

</body>
</html>
