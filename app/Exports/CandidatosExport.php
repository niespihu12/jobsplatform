<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidatosExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $vacante;
    protected $candidatosIds;

    public function __construct($vacante, $candidatosIds = [])
    {
        $this->vacante = $vacante;
        $this->candidatosIds = $candidatosIds;
    }

    public function collection()
    {
        return $this->vacante->candidatos()
            ->with('user')
            ->when(!empty($this->candidatosIds), function($query) {
                $query->whereIn('id', $this->candidatosIds);
            })
            ->orderByDesc('score')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Email',
            'Score',
            'Clasificación',
            'Recomendación',
            'Fortalezas',
            'Brechas',
            'Riesgos',
            'Fecha Postulación'
        ];
    }

    public function map($candidato): array
    {
        return [
            $candidato->user->name,
            $candidato->user->email,
            $candidato->score,
            $candidato->clasificacion,
            ucfirst($candidato->evaluacion_ia['recomendacion'] ?? 'N/A'),
            implode(', ', $candidato->evaluacion_ia['fortalezas'] ?? []),
            implode(', ', $candidato->evaluacion_ia['brechas'] ?? []),
            implode(', ', $candidato->evaluacion_ia['riesgos'] ?? []),
            $candidato->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
