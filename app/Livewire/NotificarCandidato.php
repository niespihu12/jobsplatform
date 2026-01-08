<?php

namespace App\Livewire;

use App\Models\Candidato;
use App\Notifications\EstadoCandidatura;
use Livewire\Component;

class NotificarCandidato extends Component
{
    public $candidato;
    public $estado = 'en_revision';
    public $mensaje = '';
    public $mostrarModal = false;

    public function mount(Candidato $candidato)
    {
        $this->candidato = $candidato;
    }

    public function abrirModal()
    {
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->reset(['estado', 'mensaje']);
    }

    public function enviarNotificacion()
    {
        $this->validate([
            'estado' => 'required|in:recibida,en_revision,preseleccionado,rechazado',
            'mensaje' => 'nullable|string|max:500'
        ]);

        $this->candidato->user->notify(
            new EstadoCandidatura($this->candidato->vacante, $this->estado, $this->mensaje)
        );

        session()->flash('mensaje', 'Notificación enviada correctamente');
        $this->cerrarModal();
    }

    public function render()
    {
        return view('livewire.notificar-candidato');
    }
}
