<?php

namespace App\Livewire;

use App\Models\Salario;
use App\Models\Categoria;
use App\Models\Vacante;
use Livewire\Component;
use Livewire\WithFileUploads;

class CrearVacante extends Component
{
    public $titulo;
    public $salario;
    public $categoria;
    public $empresa;
    public $ultimo_dia;
    public $descripcion;
    public $imagen;
    public $limite_candidatos;
    public $criterios = [];

    use WithFileUploads;

    protected $rules = [
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen' => 'required|image|max:1024',
        'limite_candidatos' => 'nullable|integer|min:1',
        'criterios.*.nombre' => 'required|string',
        'criterios.*.tipo' => 'required',
        'criterios.*.peso' => 'required|integer|min:1|max:10',
    ];

    public function mount()
    {
        $this->criterios = [['nombre' => '', 'descripcion' => '', 'tipo' => 'habilidad', 'peso' => 5, 'obligatorio' => false]];
    }

    public function agregarCriterio()
    {
        $this->criterios[] = ['nombre' => '', 'descripcion' => '', 'tipo' => 'habilidad', 'peso' => 5, 'obligatorio' => false];
    }

    public function eliminarCriterio($index)
    {
        unset($this->criterios[$index]);
        $this->criterios = array_values($this->criterios);
    }

    public function crearVacante()
    {
        $datos = $this->validate();

        $imagen = $this->imagen->store('vacante', 'public');
        $datos['imagen'] = str_replace('vacante/', '', $imagen);

        $vacante = Vacante::create([
            'titulo' => $datos['titulo'],
            'salario_id' => $datos['salario'],
            'categoria_id' => $datos['categoria'],
            'empresa' => $datos['empresa'],
            'ultimo_dia' => $datos['ultimo_dia'],
            'descripcion' => $datos['descripcion'],
            'imagen' => $datos['imagen'],
            'user_id' => auth()->user()->id,
            'limite_candidatos' => $this->limite_candidatos,
        ]);

        foreach ($this->criterios as $criterio) {
            $vacante->criterios()->create($criterio);
        }

        session()->flash('mensaje', 'Vacante publicada con criterios de evaluación IA');
        return redirect()->route('vacantes.index');
    }

    public function render()
    {
        return view('livewire.crear-vacante', [
            'salarios' => Salario::all(),
            'categorias' => Categoria::all()
        ]);
    }
}
