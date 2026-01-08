<?php

namespace App\Livewire;

use App\Models\Vacante;
use App\Notifications\NuevoCandidato;
use App\Services\GeminiService;
use Gemini\Enums\MimeType;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostularVacante extends Component
{
    use WithFileUploads;

    public $cv;
    public $vacante;

    public function mount(Vacante $vacante)
    {
        $this->vacante = $vacante;
    }

    public function updatedCv()
    {
        $this->validate([
            'cv' => 'required|file|mimes:pdf|max:5120'
        ], [
            'cv.required' => 'Debe seleccionar un archivo PDF',
            'cv.file' => 'Debe seleccionar un archivo válido',
            'cv.mimes' => 'El archivo debe ser un PDF',
            'cv.max' => 'El archivo no debe pesar más de 5MB'
        ]);
        
        $this->postularme();
    }

    public function postularme()
    {
        // Verificar si ya se postuló
        if ($this->vacante->candidatos()->where('user_id', auth()->user()->id)->exists()) {
            session()->flash('mensaje', 'Ya te has postulado a esta vacante anteriormente');
            return;
        }

        // Verificar si se alcanzó el límite de candidatos
        if ($this->vacante->limiteAlcanzado()) {
            session()->flash('mensaje', 'Esta vacante ya alcanzó el límite de candidatos');
            return;
        }

        try {
            // 1. Guardar archivo
            $cv = $this->cv->store('cv', 'public');
            $rutaCompleta = storage_path('app/public/' . $cv);
            
            // 2. Tipo MIME para PDF
            $mimeType = MimeType::APPLICATION_PDF;

            $gemini = new GeminiService();
            
            // 3. Extraer texto del PDF/imagen con OCR
            $textoCV = $gemini->extraerTextoPDF($rutaCompleta, $mimeType);
            
            // 4. Analizar y extraer datos estructurados
            $datosExtraidos = $gemini->analizarYExtraerDatos($textoCV);
            
            // 5. Evaluar candidato contra criterios de la vacante
            $evaluacion = $gemini->evaluarCandidato($this->vacante, $textoCV, $datosExtraidos);

            // 6. Guardar candidato con toda la información
            $candidato = $this->vacante->candidatos()->create([
                'user_id' => auth()->user()->id,
                'cv' => str_replace('cv/', '', $cv),
                'cv_texto' => $textoCV,
                'experiencia' => $datosExtraidos['experiencia'] ?? [],
                'educacion' => $datosExtraidos['educacion'] ?? [],
                'habilidades' => $datosExtraidos['habilidades'] ?? [],
                'idiomas' => $datosExtraidos['idiomas'] ?? [],
                'certificaciones' => $datosExtraidos['certificaciones'] ?? [],
                'score' => $evaluacion['score'] ?? 0,
                'evaluacion_ia' => $evaluacion
            ]);

            // 7. Generar preguntas de entrevista personalizadas
            $preguntas = $gemini->generarPreguntasEntrevista($this->vacante, $candidato, $evaluacion);
            $candidato->update(['preguntas_entrevista' => $preguntas]);

            // 8. Notificar al reclutador
            $this->vacante->reclutador->notify(new NuevoCandidato(
                $this->vacante->id, 
                $this->vacante->titulo, 
                auth()->user()->id
            ));

            session()->flash('mensaje', 'Tu postulación fue enviada y evaluada correctamente. Score: ' . ($evaluacion['score'] ?? 0) . '/100');
            
            $this->cv = null;
            
        } catch (\Exception $e) {
            $this->addError('cv', 'Error al procesar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
