<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function statistik(Request $request)
{
    $chartType = $request->input('chart_type', 'bar');
    $filterBy = $request->input('filter_by', 'status');
    
    // Data untuk chart
    $chartData = $this->getChartData($filterBy);
    
    // Data tambahan untuk statistik
    $totalKarya = Karya::count();
    $karyaPerStatus = Karya::select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get();
        
    $karyaPerKategori = Kategori::withCount('karyas')->get();
    
    // Add this line to get recent karya for the table
    $karyas = Karya::with(['kategori', 'user'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    return view('pages.admin.reports.statistik', compact(
        'chartType',
        'filterBy',
        'chartData',
        'totalKarya',
        'karyaPerStatus',
        'karyaPerKategori',
        'karyas' // Add this to the compact function
    ));

    }

    public function data(Request $request)
    {
        $status = $request->input('status');
        $kategori = $request->input('kategori_id');
        $tahun = $request->input('tahun');
        
        $karyas = Karya::with(['kategori', 'user'])
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($kategori, function($query) use ($kategori) {
                return $query->where('kategori_id', $kategori);
            })
            ->when($tahun, function($query) use ($tahun) {
                return $query->whereYear('tahun', $tahun);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $kategories = Kategori::all();
        
        return view('pages.admin.reports.data', compact('karyas', 'kategories'));
    }

    public function exportPDF(Request $request)
    {
        $data = $this->prepareExportData($request);
        
        $pdf = Pdf::loadView('pages.admin.reports.pdf', $data);
        return $pdf->download('laporan-karya-'.now()->format('YmdHis').'.pdf');
    }

    public function exportCSV(Request $request)
    {
        $data = $this->prepareExportData($request);
        $fileName = 'laporan-karya-'.now()->format('YmdHis').'.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"             => "no-cache",
            "Cache-Control"      => "must-revalidate, post-check=0, pre-check=0",
            "Expires"            => "0"
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Judul', 'Kategori', 'Pembuat', 'Status', 
                'Tahun', 'Tanggal Pengajuan', 'Views', 'Rating Rata-rata'
            ]);
            
            // Data CSV
            foreach ($data['karyas'] as $karya) {
                fputcsv($file, [
                    $karya->judul,
                    $karya->kategori->nama_kategori,
                    $karya->user->name,
                    $this->getStatusText($karya->status),
                    $karya->tahun,
                    $karya->tanggal_pengajuan->format('d/m/Y'),
                    $karya->views,
                    $karya->avg_rating
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getChartData($filterBy)
    {
        if ($filterBy === 'kategori') {
            return Kategori::withCount('karyas')->get()
                ->map(function($item) {
                    return [
                        'label' => $item->nama_kategori,
                        'value' => $item->karyas_count
                    ];
                });
        } else {
            return Karya::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->map(function($item) {
                    return [
                        'label' => $this->getStatusText($item->status),
                        'value' => $item->total
                    ];
                });
        }
    }

    private function prepareExportData($request)
    {
        $status = $request->input('status');
        $kategori = $request->input('kategori_id');
        $tahun = $request->input('tahun');
        
        $karyas = Karya::with(['kategori', 'user'])
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($kategori, function($query) use ($kategori) {
                return $query->where('kategori_id', $kategori);
            })
            ->when($tahun, function($query) use ($tahun) {
                return $query->whereYear('tahun', $tahun);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return [
            'karyas' => $karyas,
            'filter' => [
                'status' => $status ? $this->getStatusText($status) : 'Semua',
                'kategori' => $kategori ? Kategori::find($kategori)->nama_kategori : 'Semua',
                'tahun' => $tahun ?: 'Semua'
            ]
        ];
    }

    private function getStatusText($status)
    {
        $statuses = [
            'MENUNGGU' => 'Menunggu',
            'PERBAIKAN' => 'Perbaikan',
            'DISETUJUI' => 'Disetujui',
            'DITOLAK' => 'Ditolak'
        ];
        
        return $statuses[$status] ?? $status;
    }
}