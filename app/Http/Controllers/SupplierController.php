<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar Supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier';

        return view('supplier.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_nama', 'supplier_kode', 'supplier_alamat');

        //Filter berdasarkan supplier
        if ($request->supplier_id) {
            $suppliers->where('supplier_id', $request->supplier_id);
        }

        return DataTables::of($suppliers)
            ->addIndexColumn()->addColumn('aksi', function ($supplier) {
                // $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' .
                //     url('/supplier/' . $supplier->supplier_id) . '">' . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" 
                //     onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                // return $btn;
    

                $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah supplier',
            'list' => ['Home', 'supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:100',
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode'
        ]);

        SupplierModel::create([
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
            'supplier_kode' => $request->supplier_kode,
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }

    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required|string|max:100',
                'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = 'Validasi Gagal';
                if ($validator->errors()->has('supplier_kode')) {
                    $errorMessage = 'Validasi Gagal (Kode Sudah Digunakan)';
                }

                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                    'msgField' => $validator->errors(),
                ]);
            }

            SupplierModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data supplier berhasil disimpan'
            ]);
        }

        return redirect('/');
    }


    // Menampilkan detail supplier
    public function show(string $id)
    {
        $supplier = SupplierModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail supplier',
            'list' => ['Home', 'supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail supplier'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }

    public function show_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }


    // Menampilkan halaman form edit supplier
    public function edit(string $id)
    {
        $supplier = SupplierModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit supplier',
            'list' => ['Home', 'supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit supplier'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data supplier
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:100',
            'supplier_kode' => 'required|string|max:10'
        ]);

        try {
            $supplier = SupplierModel::find($id);

            if (!$supplier) {
                return redirect('/supplier')->with('error', 'Data Supplier tidak ditemukan');
            }
            SupplierModel::find($id)->update([
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
                'supplier_kode' => $request->supplier_kode,
            ]);

            return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
        } catch (\Exception $e) {
            return redirect('/supplier')->with('error', 'Gagal Update (kode sudah terpakai)');
        }
    }




    public function edit_ajax(string $id)
    {
        // return "Berhasil";
        $supplier = SupplierModel::find($id);

        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required|string|max:100',
                'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id'
            ];

            $messages = [
                'supplier_kode.unique' => 'Kode Sudah Digunakan'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errorMessage = 'Validasi Gagal';
                if ($validator->errors()->has('supplier_kode')) {
                    $errorMessage = 'Validasi Gagal (Kode Sudah Digunakan)';
                }

                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                    'msgField' => $validator->errors()
                ]);
            }

            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->update($request->all());
                return response()->json(['status' => true, 'message' => 'Data supplier berhasil diperbarui']);
            } else {
                return response()->json(['status' => false, 'message' => 'Data supplier tidak ditemukan']);
            }
        }
        return redirect('/');
    }


    public function confirm_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $supplier = SupplierModel::find($id);

        if (!$supplier) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        try {
            $supplier->delete(); // Coba menghapus data supplier
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada foreign key yang terkait
            return response()->json([
                'status' => false,
                'message' => 'Data supplier gagal dihapus karena masih terdapat data lain yang terkait'
            ]);
        }
    }

    return redirect('/');
}


    // Menghapus data supplier
    public function destroy(string $id)
    {
        $check = SupplierModel::find($id);
        if (!$check) {
            // untuk mengecek apakah data supplier dengan id yang dimaksud ada atau tidak
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        try {
            SupplierModel::destroy($id); // Hapus data supplier
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/supplier')->with(
                'error',
                'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
            );
        }
    }

    public function import()
    {
        return view('supplier.import'); // Ubah view sesuai folder & file supplier
    }
    
    public function import_ajax(Request $request)
    {
        try {
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_supplier');
    
            if (!$file->isValid()) {
                return response()->json(['status' => false, 'message' => 'File tidak valid'], 400);
            }
    
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = storage_path('app/public/file_supplier');
    
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }
    
            $file->move($destinationPath, $filename);
            $filePathRelative = "file_supplier/$filename";
            $filePath = storage_path("app/public/file_supplier/$filename");
    
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
    
            // Hapus file setelah dibaca
            if (Storage::disk('public')->exists($filePathRelative)) {
                Storage::disk('public')->delete($filePathRelative);
            }
    
            $insert = [];
    
            if (count($data) > 1) {
                $existingCodes = SupplierModel::pluck('supplier_kode')->toArray();
    
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        if (!in_array($value['B'], $existingCodes)) {
                            $insert[] = [
                                'supplier_id'     => $value['A'],
                                'supplier_kode'   => $value['B'],
                                'supplier_nama'   => $value['C'],
                                'supplier_alamat' => $value['D'],
                                'created_at'      => now(),
                            ];
                        }
                    }
                }
    
                if (count($insert) > 0) {
                    SupplierModel::insert($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data supplier berhasil diimport'
                    ]);
                }
            }
    
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
    
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ]);
        }
    }

    public function export_excel()
    {
        $supplier = SupplierModel::select('supplier_nama', 'supplier_kode', 'supplier_alamat')
            ->orderBy('supplier_id')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Supplier');
        $sheet->setCellValue('C1', 'Kode');
        $sheet->setCellValue('D1', 'Alamat Supplier');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;

        foreach ($supplier as $key => $data) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $data->supplier_nama);
            $sheet->setCellValue('C' . $baris, $data->supplier_kode);
            $sheet->setCellValue('D' . $baris, $data->supplier_alamat);
            $no++;
            $baris++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Supplier');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Supplier_' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $supplier = SupplierModel::select('supplier_nama', 'supplier_kode', 'supplier_alamat')
            ->orderBy('supplier_nama')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $supplier]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Supplier ' . date('Y-m-d H:i:s') . '.pdf');
    }

}