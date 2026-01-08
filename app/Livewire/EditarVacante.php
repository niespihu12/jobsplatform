<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Salario;
use App\Models\Vacante;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditarVacante extends Component
{
    public $vacante_id;
    public $titulo;
    public $salario;
    public $categoria;
    public $empresa;
    public $ultimo_dia;
    public $descripcion;
    public $imagen;
    public $imagen_nueva;
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
        'imagen_nueva' => 'nullable|image|max:1024',
        'limite_candidatos' => 'nullable|integer|min:1',
        'criterios.*.nombre' => 'required|string',
        'criterios.*.tipo' => 'required',
        'criterios.*.peso' => 'required|integer|min:1|max:10',
    ];

    public function mount(Vacante $vacante)
    {
        $this->vacante_id = $vacante->id;
        $this->titulo = $vacante->titulo;
        $this->salario = $vacante->salario_id;
        $this->categoria = $vacante->categoria_id;
        $this->empresa = $vacante->empresa;
        $this->ultimo_dia = Carbon::parse($vacante->ultimo_dia)->format('Y-m-d');
        $this->descripcion = $vacante->descripcion;
        $this->imagen = $vacante->imagen;
        $this->limite_candidatos = $vacante->limite_candidatos;

        // Cargar criterios existentes
        $this->criterios = $vacante->criterios->map(function($criterio) {
            return [
                'id' => $criterio->id,
                'nombre' => $criterio->nombre,
                'descripcion' => $criterio->descripcion,
                'tipo' => $criterio->tipo,
                'peso' => $criterio->peso,
                'obligatorio' => $criterio->obligatorio
            ];
        })->toArray();

        // Si no hay criterios, agregar uno por defecto
        if (empty($this->criterios)) {
            $this->criterios = [
                ['nombre' => '', 'descripcion' => '', 'tipo' => 'habilidad', 'peso' => 5, 'obligatorio' => false]
            ];
        }
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

    public function editarVacante()
    {
        $datos = $this->validate();

        // Revisar si hay una nueva imagen
        if ($this->imagen_nueva) {
            $imagen = $this->imagen_nueva->store('vacante', 'public');
            $datos['imagen'] = str_replace('vacante/', '', $imagen);
        }

        // Encontrar la vacante a editar
        $vacante = Vacante::find($this->vacante_id);

        // Asignar los valores
        $vacante->titulo = $datos['titulo'];
        $vacante->salario_id = $datos['salario'];
        $vacante->categoria_id = $datos['categoria'];
        $vacante->empresa = $datos['empresa'];
        $vacante->ultimo_dia = $datos['ultimo_dia'];
        $vacante->descripcion = $datos['descripcion'];
        $vacante->imagen = $datos['imagen'] ?? $vacante->imagen;
        $vacante->limite_candidatos = $this->limite_candidatos;

        // Guardar la vacante
        $vacante->save();

        // Actualizar criterios
        // Eliminar criterios existentes
        $vacante->criterios()->delete();
        
        // Crear nuevos criterios
        foreach ($this->criterios as $criterio) {
            $vacante->criterios()->create([
                'nombre' => $criterio['nombre'],
                'descripcion' => $criterio['descripcion'] ?? null,
                'tipo' => $criterio['tipo'],
                'peso' => $criterio['peso'],
                'obligatorio' => $criterio['obligatorio'] ?? false
            ]);
        }

        session()->flash('mensaje', 'La Vacante se actualizó correctamente');
        return redirect()->route('vacantes.index');
    }

    public function render()
    {
        $salarios = Salario::all();
        $categorias = Categoria::all();
        return view('livewire.editar-vacante', [
            'salarios' => $salarios,
            'categorias' => $categorias
        ]);
    }
}
