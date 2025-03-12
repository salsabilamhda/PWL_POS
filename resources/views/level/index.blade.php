@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="mb-3">
                <label for="filter_level" class="form-label">Filter Level</label>
                <select id="filter_level" class="form-control">
                    <option value="">Semua Level</option>
                    @foreach($level as $l)
                        <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
                    @endforeach
                </select>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Level</th>
                        <th>Kode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            var dataLevel = $('#table_level').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('level/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function (d) {
                        d.level_id = $('#filter_level').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "level_nama", className: "", orderable: true, searchable: true },
                    { data: "level_kode", className: "", orderable: true, searchable: true },
                    { data: "aksi", className: "", orderable: false, searchable: false }
                ]
            });
            
            $('#filter_level').change(function () {
                dataLevel.ajax.reload();
            });
        });
    </script>
@endpush
