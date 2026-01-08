<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Vacante;
use Illuminate\Http\Request;

class CompararCandidatosController extends Controller
{
    public function index(Vacante $vacante)
    {
        $candidatos = $vacante->candidatos()->with('user')->orderByDesc('score')->get();
        
        return view('candidatos.comparar', [
            'vacante' => $vacante,
            'candidatos' => $candidatos
        ]);
    }

    public function comparar(Request $request, Vacante $vacante)
    {
        $request->validate([
            'candidatos' => 'required|array|min:2|max:5',
            'candidatos.*' => 'exists:candidatos,id'
        ]);

        $candidatos = Candidato::with('user')
            ->whereIn('id', $request->candidatos)
            ->where('vacante_id', $vacante->id)
            ->orderByDesc('score')
            ->get();

        return view('candidatos.comparacion', [
            'vacante' => $vacante,
            'candidatos' => $candidatos
        ]);
    }
}
