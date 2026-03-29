@extends('layout.app')

@section('content')
<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Data Kriteria</li>
    </ol>
</div>

<div class="row gutters">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Kriteria</h5>
                    <a href="#" class="btn btn-success">
                        <i class="icon-plus"></i> Tambah Data
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">No</th>
                                <th width="100">No Induk</th>
                                <th>Nama lengkap</th>
                                <th>Tempat & Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Asal Kolat</th>
                                <th>Tingkatan</th>
                                <th>Tanggal Gabung</th>
                                <th>Status Anggota</th>
                                
                                
                                
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
