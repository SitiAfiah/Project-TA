@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Kas Cabang</li>
            </ol>
        </nav>
        <div class="text-start">
            <h3 class="fw-bold text-dark mb-1">Buku Kas Digital</h3>
            <p class="text-muted small">Pantau arus kas masuk dan keluar secara real-time.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
            <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Row Statistik Utama -->
    <div class="row mb-4 g-3 text-start">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white h-100 overflow-hidden"
                 style="border-radius: 20px; background: linear-gradient(45deg, #0d6efd, #00c6ff);">
                <div class="card-body p-4 position-relative">
                    <p class="mb-1 small opacity-75">Saldo Kas Saat Ini</p>
                    <h2 class="fw-bold mb-0 text-nowrap">Rp {{ number_format((float)$saldo_sekarang, 0, ',', '.') }}</h2>
                    <i class="icon-wallet position-absolute opacity-25" style="font-size: 80px; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success-soft rounded-3 p-3 me-3">
                            <i class="icon-login text-success fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small fw-bold uppercase">Pemasukan (Bulan Ini)</p>
                            <h4 class="fw-bold text-success mb-0">Rp {{ number_format((float)$pemasukan_bulan_ini, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-danger border-4" style="border-radius: 15px;">
                <div class="card-body p-4 text-start">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger-soft rounded-3 p-3 me-3 text-start">
                            <i class="icon-logout text-danger fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small fw-bold uppercase text-start">Pengeluaran (Bulan Ini)</p>
                            <h4 class="fw-bold text-danger mb-0 text-start">Rp {{ number_format((float)$pengeluaran_bulan_ini, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Tabel -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-2" style="border-radius: 20px;">
                <div class="card-body p-4">

                    <!-- Header Tabel -->
                    <div class="d-flex justify-content-between align-items-center mb-4 text-start">
                        <div class="text-start">
                            <h5 class="card-title mb-0 fw-bold text-dark">Buku Kas Utama</h5>
                            <p class="text-muted small mb-0">Cabang Jember - Histori seluruh arus kas.</p>
                        </div>

                        <div class="d-flex gap-2">
                            <!-- DROPDOWN FILTER -->
                            <div class="dropdown">
                                <button class="btn btn-light border px-4 py-2 dropdown-toggle shadow-sm"
                                        type="button" id="filterDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false" style="border-radius: 12px;">
                                    <i class="icon-filter me-1"></i> Filter Data
                                </button>
                                <div class="dropdown-menu dropdown-menu-end p-4 shadow-lg border-0"
                                     aria-labelledby="filterDropdown" style="width: 300px; border-radius: 20px;">

                                    <form action="{{ route('kas.index') }}" method="GET">
                                        <h6 class="fw-bold mb-3">Saring Transaksi</h6>
                                        <div class="mb-3 text-start">
                                            <label class="form-label small fw-bold">Jenis Kas</label>
                                            <select name="jenis" class="form-select border-0 bg-light" style="border-radius: 10px;">
                                                <option value="">Semua Jenis</option>
                                                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                                                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label small fw-bold">Dari Tanggal</label>
                                            <input type="date" name="dari" value="{{ request('dari') }}" class="form-control border-0 bg-light" style="border-radius: 10px;">
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label small fw-bold">Sampai Tanggal</label>
                                            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control border-0 bg-light" style="border-radius: 10px;">
                                        </div>
                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 10px;">Terapkan</button>
                                            <a href="{{ route('kas.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted">Hapus Filter</a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- TOMBOL CATAT TRANSAKSI -->
                            <button type="button" class="btn btn-primary px-4 py-2 shadow-sm pulse-button"
                                    style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#modalTambahKas">
                                <i class="icon-plus me-1"></i> Catat Transaksi
                            </button>
                        </div>
                    </div>

                    <!-- Label Filter Aktif -->
                    @if(request('dari') || request('sampai') || request('jenis'))
                    <div class="mb-3 text-start">
                        <span class="small text-muted">Filter Aktif: </span>
                        @if(request('jenis')) <span class="badge bg-primary rounded-pill px-3">{{ strtoupper(request('jenis')) }}</span> @endif
                        @if(request('dari')) <span class="badge bg-light text-dark border rounded-pill px-3">Sejak: {{ request('dari') }}</span> @endif
                        <a href="{{ route('kas.index') }}" class="text-danger small ms-2 text-decoration-none"><i class="icon-close"></i> Bersihkan</a>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle main-table border-0">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th class="text-start">Keterangan Transaksi</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-end">Sisa Saldo</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data_kas as $no => $item)
                                    <tr>
                                        <td class="text-center text-muted small">{{ $no + 1 }}</td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 bg-{{ $item->jenis == 'masuk' ? 'success' : 'danger' }} rounded-circle p-2" style="width: 10px; height: 10px;"></div>
                                                <div>
                                                    <div class="fw-bold text-dark text-start">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                                    <div class="text-muted small text-start">{{ $item->keterangan ?? 'Tanpa keterangan' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $item->jenis == 'masuk' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }} border border-{{ $item->jenis == 'masuk' ? 'success' : 'danger' }} px-3 py-2" style="border-radius: 10px; font-size: 10px;">
                                                {{ strtoupper($item->kategori) }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold {{ $item->jenis == 'masuk' ? 'text-success' : 'text-danger' }} text-nowrap">
                                            {{ $item->jenis == 'masuk' ? '+' : '-' }} {{ number_format((float)$item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end fw-bold text-dark text-nowrap">
                                            Rp {{ number_format((float)$item->saldo_akhir, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Button Edit -->
                                                <button type="button" class="btn-action-custom btn-edit" data-bs-toggle="modal" data-bs-target="#modalEditKas{{ $item->id }}">
                                                    <i class="icon-note"></i>
                                                </button>
                                                <!-- Form Hapus -->
                                                <form action="{{ route('kas.destroy', $item->id) }}" method="POST" id="form-hapus-{{ $item->id }}">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-action-custom btn-delete" onclick="hapusKas('{{ $item->id }}')">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL EDIT DI DALAM LOOP -->
                                    <div class="modal fade" id="modalEditKas{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
                                                <div class="modal-header bg-dark text-white border-0 py-3 px-4" style="border-radius: 25px 25px 0 0;">
                                                    <h5 class="modal-title fw-bold"><i class="icon-pencil me-2"></i>Edit Transaksi</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('kas.update', $item->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body p-4 text-start">
                                                        <div class="row g-3">
                                                            <div class="col-md-6 text-start">
                                                                <label class="form-label small fw-bold">Tanggal</label>
                                                                <input type="date" name="tanggal" value="{{ $item->tanggal }}" class="form-control custom-input" required>
                                                            </div>
                                                            <div class="col-md-6 text-start">
                                                                <label class="form-label small fw-bold">Jenis Arus</label>
                                                                <select name="jenis" class="form-select custom-input" required>
                                                                    <option value="masuk" {{ $item->jenis == 'masuk' ? 'selected' : '' }}>Pemasukan (+)</option>
                                                                    <option value="keluar" {{ $item->jenis == 'keluar' ? 'selected' : '' }}>Pengeluaran (-)</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-12 text-start">
                                                                <label class="form-label small fw-bold">Kategori</label>
                                                                <input type="text" name="kategori" value="{{ $item->kategori }}" class="form-control custom-input" required>
                                                            </div>
                                                            <div class="col-12 text-start">
                                                                <label class="form-label small fw-bold">Nominal (Rp)</label>
                                                                <input type="number" name="nominal" value="{{ (float)$item->nominal }}" class="form-control custom-input" required>
                                                            </div>
                                                            <div class="col-12 text-start">
                                                                <label class="form-label small fw-bold">Keterangan (Opsional)</label>
                                                                <textarea name="keterangan" class="form-control custom-input" rows="2">{{ $item->keterangan }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                                                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 12px;">Update Data</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <img src="https://illustrations.popsy.co/blue/no-data-found.svg" width="120" alt="no-data">
                                            <p class="text-muted small mt-3">Belum ada transaksi terekam.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kas -->
<div class="modal fade" id="modalTambahKas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-header bg-primary text-white border-0 py-3 px-4" style="border-radius: 25px 25px 0 0;">
                <h5 class="modal-title fw-bold"><i class="icon-plus me-2"></i>Catat Transaksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="row g-3">
                        <div class="col-md-6 text-start">
                            <label class="form-label small fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control custom-input" required>
                        </div>
                        <div class="col-md-6 text-start">
                            <label class="form-label small fw-bold">Jenis Arus</label>
                            <select name="jenis" class="form-select custom-input" required>
                                <option value="masuk">Pemasukan (+)</option>
                                <option value="keluar">Pengeluaran (-)</option>
                            </select>
                        </div>
                        <div class="col-12 text-start">
                            <label class="form-label small fw-bold">Kategori</label>
                            <input type="text" name="kategori" list="list-kategori-add" class="form-control custom-input" placeholder="Misal: SPP, Listrik" required>
                            <datalist id="list-kategori-add">
                                <option value="SPP"><option value="Operasional"><option value="Sumbangan">
                            </datalist>
                        </div>
                        <div class="col-12 text-start">
                            <label class="form-label small fw-bold">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control custom-input" placeholder="0" required>
                        </div>
                        <div class="col-12 text-start">
                            <label class="form-label small fw-bold">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control custom-input" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 12px;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: #dcfce7; }
    .bg-danger-soft { background-color: #fee2e2; }
    .main-table thead th { background-color: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; padding: 15px; border-bottom: 2px solid #f1f5f9; }
    .main-table tbody td { padding: 15px; border-bottom: 1px solid #f1f5f9; }
    .custom-input { border-radius: 12px; border: 1px solid #e2e8f0; padding: 10px 15px; }

    /* Button Action Styling */
    .btn-action-custom { width: 32px; height: 32px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
    .btn-edit { background-color: #e6f7ff; color: #1890ff; }
    .btn-edit:hover { background-color: #1890ff; color: white; }
    .btn-delete { background-color: #fff1f0; color: #ff4d4f; }
    .btn-delete:hover { background-color: #ff4d4f; color: white; }

    .pulse-button:hover { animation: pulse 1.5s infinite; }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }
</style>

<script>
function hapusKas(id) {
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: "Data akan dihapus permanen dan riwayat saldo akan terpengaruh.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus!',
        borderRadius: '20px'
    }).then((result) => { if (result.isConfirmed) { document.getElementById('form-hapus-' + id).submit(); } })
}
</script>
@endsection
