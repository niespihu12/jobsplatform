<?php

namespace App\Livewire;

use App\Notifications\NuevoCandidato;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vacante;

class PostularVacante extends Component
{
    use WithFileUploads;
    public $cv;
    public $vacante;

    protected $rules = [
        'cv' => 'required|mimes:pdf'
    ];

    public function mount(Vacante $vacante)
    {
        $this->vacante = $vacante;
    }

    public function postularme(){
        $datos = $this->validate();

        // Almacenar CV en el disco duro
        $cv = $this->cv->store('cv', 'public');
        $datos['cv'] = str_replace('cv/', '', $cv);

        // Crear el candidato a la vacante
        $this->vacante->candidatos()->create([
           'user_id' => auth()->user()->id,
           'cv' => $datos['cv'],
        ]);



        // Crear notificacion y enviar email
        $this->vacante->reclutador->notify(new NuevoCandidato($this->vacante->id, $this->vacante->titulo, auth()->user()->name));

        // Mostrar el usuario de ok
        session()->flash('mensaje', 'Se envio correctamente tu informacion, mucha suerte');

        return redirect()->back();
    }
    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
