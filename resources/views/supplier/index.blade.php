@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-sm btn-info mt-1">Import
                    Supplier</button>
                    <a href="{{ url('/supplier/export_excel') }}" class="btn btn-primary"><i class="fa fa-file
                        excel"></i> Export Supplier</a>
                    <a href="{{ url('/supplier/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file
                        pdf"></i> Export Supplier</a> 
                <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah Ajax
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Kode</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal Container --}}
    <div id="modal-crud" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url) {
            // Kosongkan modal sebelum memuat konten baru
            $("#modal-crud .modal-content").html("");

            // Panggil modal melalui AJAX
            $.get(url, function (response) {
                $("#modal-crud .modal-content").html(response);
                $("#modal-crud").modal("show");
            });
        }

        // Bersihkan isi modal setelah ditutup
        $('#modal-crud').on('hidden.bs.modal', function () {
            $("#modal-crud .modal-content").html("");
        });


        var dataSupplier
        $(document).ready(function () {
            dataSupplier = $('#table_supplier').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('supplier/list') }}",
                    dataType: "json",
                    type: "POST",
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "supplier_nama", className: "", orderable: true, searchable: true },
                    { data: "supplier_kode", className: "", orderable: true, searchable: true },
                    { data: "supplier_alamat", className: "", orderable: true, searchable: true },
                    { data: "aksi", className: "", orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush