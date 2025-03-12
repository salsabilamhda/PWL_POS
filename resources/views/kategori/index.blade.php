@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="filter_kategori">Filter Kategori:</label>
                    <select id="filter_kategori" class="form-control">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->kategori_id }}">{{ $kat->kategori_nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Kode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            var dataKategori = $('#table_kategori').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('kategori/list') }}",
                    type: "POST",
                    data: function(d) {
                        d.kategori_id = $('#filter_kategori').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "kategori_nama", className: "", orderable: true, searchable: true },
                    { data: "kategori_kode", className: "", orderable: true, searchable: true },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ]
            });

            // Event listener untuk filter
            $('#filter_kategori').change(function () {
                dataKategori.ajax.reload();
            });
        });
    </script>
@endpush
