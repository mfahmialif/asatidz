<?php

namespace App\Exports;

use App\Models\KegiatanAsatidz;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $asatidzId;
    protected $startDate;
    protected $endDate;

    public function __construct($asatidzId = null, $startDate = null, $endDate = null)
    {
        $this->asatidzId = $asatidzId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = KegiatanAsatidz::with(['asatidz.user', 'details.masterKegiatan']);

        if ($this->asatidzId) {
            $query->where('asatidz_id', $this->asatidzId);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_kegiatan', [$this->startDate, $this->endDate]);
        }

        $query->orderBy('tanggal_kegiatan', 'desc');

        return $query;
    }

    public function headings(): array
    {
        return [
            'Tanggal Laporan',
            'Nama Asatidz',
            'Rincian Kegiatan',
            'Total Poin',
        ];
    }

    public function map($kegiatan): array
    {
        $details = $kegiatan->details->map(function($d) {
            $nama = $d->masterKegiatan ? $d->masterKegiatan->nama : 'Unknown';
            $keterangan = $d->keterangan ? " ({$d->keterangan})" : "";
            return "- " . $nama . $keterangan;
        })->implode("\n");

        return [
            $kegiatan->tanggal_kegiatan,
            $kegiatan->asatidz->nama,
            $details,
            $kegiatan->total_poin,
        ];
    }
}
