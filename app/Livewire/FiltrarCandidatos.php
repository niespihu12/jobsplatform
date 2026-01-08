<?php

namespace App\Livewire;

use App\Models\Vacante;
use Livewire\Component;
use Livewire\Attributes\Url;

class FiltrarCandidatos extends Component
{
    public $vacante;
    
    #[Url(except: '')]
    public $busqueda = '';
    
    #[Url(except: '')]
    public $clasificacion = '';
    
    #[Url(except: '')]
    public $recomendacion = '';
    
    #[Url(except: 0)]
    public $scoreMin = 0;
    
    #[Url(except: 100)]
    public $scoreMax = 100;

    public function mount(Vacante $vacante)
    {
        $this->vacante = $vacante;
    }

    public function limpiar()
    {
        $this->busqueda = '';
        $this->clasificacion = '';
        $this->recomendacion = '';
        $this->scoreMin = 0;
        $this->scoreMax = 100;
    }

    public function render()
    {
        $candidatos = $this->vacante->candidatos()
            ->with('user')
            ->when($this->busqueda, function($q) {
                $q->whereHas('user', function($query) {
                    $query->where('name', 'like', '%' . $this->busqueda . '%')
                          ->orWhere('email', 'like', '%' . $this->busqueda . '%');
                });
            })
            ->where('score', '>=', $this->scoreMin)
            ->where('score', '<=', $this->scoreMax)
            ->orderByDesc('score')
            ->get();

        if ($this->clasificacion) {
            $candidatos = $candidatos->filter(fn($c) => $c->clasificacion === $this->clasificacion);
        }

        if ($this->recomendacion) {
            $candidatos = $candidatos->filter(fn($c) => 
                strtolower($c->evaluacion_ia['recomendacion'] ?? '') === strtolower($this->recomendacion)
            );
        }

        return view('livewire.filtrar-candidatos', [
            'candidatos' => $candidatos->values()
        ]);
    }
}
