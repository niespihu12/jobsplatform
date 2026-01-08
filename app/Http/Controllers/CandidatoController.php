<?php

namespace App\Http\Controllers;

use App\Models\Vacante;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    public function index(Request $request, Vacante $vacante)
    {
        $query = $vacante->candidatos()->with('user');

        // Filtro de búsqueda
        if ($request->filled('busqueda')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->busqueda . '%')
                  ->orWhere('email', 'like', '%' . $request->busqueda . '%');
            });
        }

        // Filtro de score
        $scoreMin = $request->input('scoreMin', 0);
        $scoreMax = $request->input('scoreMax', 100);
        $query->whereBetween('score', [$scoreMin, $scoreMax]);

        $candidatos = $query->orderByDesc('score')->get();

        // Filtros en colección
        if ($request->filled('clasificacion')) {
            $candidatos = $candidatos->filter(fn($c) => $c->clasificacion === $request->clasificacion);
        }

        if ($request->filled('recomendacion')) {
            $candidatos = $candidatos->filter(fn($c) => 
                strtolower($c->evaluacion_ia['recomendacion'] ?? '') === strtolower($request->recomendacion)
            );
        }

        return view('candidatos.index', [
            'vacante' => $vacante,
            'candidatos' => $candidatos->values(),
            'filtros' => [
                'busqueda' => $request->input('busqueda', ''),
                'scoreMin' => $scoreMin,
                'scoreMax' => $scoreMax,
                'clasificacion' => $request->input('clasificacion', ''),
                'recomendacion' => $request->input('recomendacion', '')
            ]
        ]);
    }
}
