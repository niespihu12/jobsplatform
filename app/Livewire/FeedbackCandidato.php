<?php

namespace App\Livewire;

use App\Models\Candidato;
use Livewire\Component;

class FeedbackCandidato extends Component
{
    public $candidato;
    public $feedback_score;
    public $feedback_comentario;
    public $contratado;
    public $mostrarModal = false;

    public function mount(Candidato $candidato)
    {
        $this->candidato = $candidato;
        $this->feedback_score = $candidato->feedback_score;
        $this->feedback_comentario = $candidato->feedback_comentario;
        $this->contratado = $candidato->contratado;
    }

    public function abrirModal()
    {
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
    }

    public function guardarFeedback()
    {
        $this->validate([
            'feedback_score' => 'required|integer|min:0|max:100',
            'feedback_comentario' => 'nullable|string|max:500'
        ]);

        $this->candidato->update([
            'feedback_score' => $this->feedback_score,
            'feedback_comentario' => $this->feedback_comentario,
            'contratado' => $this->contratado
        ]);

        session()->flash('mensaje', 'Feedback guardado correctamente');
        $this->cerrarModal();
    }

    public function render()
    {
        return view('livewire.feedback-candidato');
    }
}
