<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar Kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori';

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $kategoris = KategoriModel::select('kategori_id', 'kategori_nama', 'kategori_kode');

        //Filter berdasarkan kategori
        if ($request->kategori_id) {
            $kategoris->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($kategoris)
            ->addIndexColumn()->addColumn('aksi', function ($kategori) {
                // $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' .
                //     url('/kategori/' . $kategori->kategori_id) . '">' . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" 
                //     onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                // return $btn;

                $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;

            })->rawColumns(['aksi'])
            ->make(true);
    }


    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah kategori',
            'list' => ['Home', 'kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'kategori_nama' => 'required|string|max:100',
            'kategori_kode' => 'required|string|max:5|unique:m_kategori,kategori_kode'
        ]);

        KategoriModel::create([
            'kategori_nama' => $request->kategori_nama,
            'kategori_kode' => $request->kategori_kode,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }


    public function create_ajax()
    {
        // return 'create_ajax dipanggil'; //Debug
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_nama' => 'required|string|max:100',
                'kategori_kode' => 'required|string|max:5|unique:m_kategori,kategori_kode'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = 'Validasi Gagal';
                if ($validator->errors()->has('kategori_kode')) {
                    $errorMessage = 'Validasi Gagal (Kode Sudah Digunakan)';
                }

                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                    'msgField' => $validator->errors(),
                ]);
            }

            KategoriModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Menampilkan detail kategori
    public function show(string $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail kategori',
            'list' => ['Home', 'kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    public function show_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.show_ajax', ['kategori' => $kategori]);
    }

    // Menampilkan halaman form edit kategori
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit kategori',
            'list' => ['Home', 'kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit kategori'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data kategori
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_nama' => 'required|string|max:100',
            'kategori_kode' => 'required|string|max:5'
        ]);

        try {
            $kategori = KategoriModel::find($id);

            if (!$kategori) {
                return redirect('/kategori')->with('error', 'Data Kategori tidak ditemukan');
            }
            KategoriModel::find($id)->update([
                'kategori_nama' => $request->kategori_nama,
                'kategori_kode' => $request->kategori_kode,
            ]);
            return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
        } catch (\Exception $e) {
            return redirect('/kategori')->with('error', 'Gagal Update (kode sudah terpakai)');
        }
    }


    public function edit_ajax(string $id)
    {
        // return "Berhasil";
        $kategori = KategoriModel::find($id);

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_nama' => 'required|string|max:100',
                'kategori_kode' => 'required|string|max:5|unique:m_kategori,kategori_kode,' . $id . ',kategori_id'
            ];
    
            $messages = [
                'kategori_kode.unique' => 'Kode Sudah Digunakan'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                $errorMessage = 'Validasi Gagal';
                if ($validator->errors()->has('kategori_kode')) {
                    $errorMessage = 'Validasi Gagal (Kode Sudah Digunakan)';
                }
    
                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                    'msgField' => $validator->errors()
                ]);
            }
    
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->update($request->all());
                return response()->json(['status' => true, 'message' => 'Data kategori berhasil diperbarui']);
            } else {
                return response()->json(['status' => false, 'message' => 'Data kategori tidak ditemukan']);
            }
        }
        return redirect('/');
    }


    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $kategori = KategoriModel::find($id);

        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        try {
            $kategori->delete(); // Mencoba menghapus data
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error ketika masih ada foreign key yang terkait
            return response()->json([
                'status' => false,
                'message' => 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
            ]);
        }
    }

    return redirect('/');
}


    // Menghapus data kategori
    public function destroy(string $id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {
            // untuk mengecek apakah data kategori dengan id yang dimaksud ada atau tidak
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        try {
            KategoriModel::destroy($id); // Hapus data kategori
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/kategori')->with(
                'error',
                'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
            );
        }
    }

    public function import()
{
    return view('kategori.import'); // Ubah view sesuai folder dan nama file
}

public function import_ajax(Request $request)
{
    try {
        $rules = [
            'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_kategori');

        if (!$file->isValid()) {
            return response()->json(['status' => false, 'message' => 'File tidak valid'], 400);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = storage_path('app/public/file_kategori');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }

        $file->move($destinationPath, $filename);
        $filePathRelative = "file_kategori/$filename";
        $filePath = storage_path("app/public/file_kategori/$filename");

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
            $existingCodes = KategoriModel::pluck('kategori_kode')->toArray();

            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    if (!in_array($value['B'], $existingCodes)) {
                        $insert[] = [
                            'kategori_id'   => $value['A'],
                            'kategori_kode' => $value['B'],
                            'kategori_nama' => $value['C'],
                            'created_at'    => now(),
                        ];
                    }
                }
            }

            if (count($insert) > 0) {
                KategoriModel::insert($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori berhasil diimport'
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
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')
            ->orderBy('kategori_id')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kategori');
        $sheet->setCellValue('C1', 'Kode');
       

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;

        foreach ($kategori as $key => $data) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $data->kategori_nama);
            $sheet->setCellValue('C' . $baris, $data->kategori_kode);
            $no++;
            $baris++;
        }

        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Kategori');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori_' . date('Y-m-d H:i:s') . '.xlsx';

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
        $kategori = KategoriModel::select('kategori_nama', 'kategori_kode')
            ->orderBy('kategori_nama')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Kategori ' . date('Y-m-d H:i:s') . '.pdf');
    }
}