<?php

namespace App\Http\Controllers;

use App\Models\Vacante;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CandidatosExport;
use Illuminate\Http\Request;

class ExportarCandidatosController extends Controller
{
    public function pdf(Request $request, Vacante $vacante)
    {
        $candidatosIds = $request->input('candidatos', []);
        
        $candidatos = $vacante->candidatos()
            ->with('user')
            ->when(!empty($candidatosIds), function($query) use ($candidatosIds) {
                $query->whereIn('id', $candidatosIds);
            })
            ->orderByDesc('score')
            ->get();

        $pdf = Pdf::loadView('candidatos.pdf', [
            'vacante' => $vacante,
            'candidatos' => $candidatos
        ]);

        $filename = 'shortlist-' . str_replace(['/', '\\'], '-', $vacante->titulo) . '.pdf';
        return $pdf->download($filename);
    }

    public function excel(Request $request, Vacante $vacante)
    {
        $candidatosIds = $request->input('candidatos', []);
        
        $filename = 'shortlist-' . str_replace(['/', '\\'], '-', $vacante->titulo) . '.xlsx';
        return Excel::download(
            new CandidatosExport($vacante, $candidatosIds), 
            $filename
        );
    }
}
