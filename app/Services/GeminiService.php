<?php

namespace App\Services;

use Gemini;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;

class GeminiService
{
    protected $client;

    public function __construct()
    {
        $this->client = Gemini::client(config('services.gemini.api_key'));
    }

    public function extraerTextoPDF($rutaArchivo, $mimeType)
    {
        $contenido = base64_encode(file_get_contents($rutaArchivo));
        
        $prompt = "Extrae TODO el texto de este documento CV/Hoja de Vida. " .
                  "Devuelve SOLO el texto extraído sin formato adicional, sin JSON, solo el contenido del documento.";

        $result = $this->client->generativeModel(model: 'gemini-2.0-flash')
            ->generateContent([
                $prompt,
                new Blob(mimeType: $mimeType, data: $contenido)
            ]);

        return $result->text();
    }

    public function analizarYExtraerDatos($textoCV)
    {
        $prompt = "Analiza este CV y extrae la información en formato JSON:\n\n" .
                  "CV:\n{$textoCV}\n\n" .
                  "Devuelve SOLO JSON válido sin markdown:\n" .
                  "{\"experiencia\": [{\"cargo\": \"\", \"empresa\": \"\", \"periodo\": \"\", \"descripcion\": \"\"}], " .
                  "\"educacion\": [{\"titulo\": \"\", \"institucion\": \"\", \"año\": \"\"}], " .
                  "\"habilidades\": [], \"idiomas\": [], \"certificaciones\": []}";

        $result = $this->client->generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);
        
        $texto = $result->text();
        $texto = preg_replace('/```json\n?|\n?```/', '', $texto);
        
        return json_decode(trim($texto), true) ?? [];
    }

    public function evaluarCandidato($vacante, $textoCV, $datosExtraidos)
    {
        $criterios = $vacante->criterios->map(fn($c) => 
            "- {$c->nombre} (Tipo: {$c->tipo}, Peso: {$c->peso}/10, " . 
            ($c->obligatorio ? 'OBLIGATORIO' : 'Opcional') . 
            ($c->descripcion ? ": {$c->descripcion}" : "") . ")"
        )->implode("\n");

        // Construir texto de experiencia de forma segura
        $experienciaTexto = '';
        if (isset($datosExtraidos['experiencia']) && is_array($datosExtraidos['experiencia'])) {
            $experiencias = [];
            foreach ($datosExtraidos['experiencia'] as $e) {
                if (is_array($e)) {
                    $cargo = $e['cargo'] ?? 'Sin cargo';
                    $empresa = $e['empresa'] ?? 'Sin empresa';
                    $periodo = $e['periodo'] ?? 'Sin periodo';
                    $experiencias[] = "{$cargo} en {$empresa} ({$periodo})";
                }
            }
            $experienciaTexto = implode(", ", $experiencias);
        }

        // Construir texto de habilidades de forma segura
        $habilidadesTexto = '';
        if (isset($datosExtraidos['habilidades']) && is_array($datosExtraidos['habilidades'])) {
            $habilidades = [];
            foreach ($datosExtraidos['habilidades'] as $h) {
                if (is_string($h)) {
                    $habilidades[] = $h;
                }
            }
            $habilidadesTexto = implode(", ", $habilidades);
        }

        $prompt = "Eres un experto reclutador. Evalúa este candidato:\n\n" .
                  "=== VACANTE ===\n" .
                  "Título: {$vacante->titulo}\n" .
                  "Empresa: {$vacante->empresa}\n" .
                  "Descripción: {$vacante->descripcion}\n\n" .
                  "=== CRITERIOS DE EVALUACIÓN ===\n{$criterios}\n\n" .
                  "=== CANDIDATO ===\n" .
                  "Experiencia: {$experienciaTexto}\n" .
                  "Habilidades: {$habilidadesTexto}\n\n" .
                  "CV Completo:\n{$textoCV}\n\n" .
                  "Evalúa y responde SOLO en JSON válido (sin markdown):\n" .
                  "{\"score\": 85, " .
                  "\"fortalezas\": [\"fortaleza 1\", \"fortaleza 2\", \"fortaleza 3\"], " .
                  "\"brechas\": [\"brecha 1\", \"brecha 2\", \"brecha 3\"], " .
                  "\"riesgos\": [\"riesgo 1\", \"riesgo 2\"], " .
                  "\"recomendacion\": \"recomendado\"}\n\n" .
                  "IMPORTANTE: El campo 'recomendacion' debe ser EXACTAMENTE uno de estos valores: \"fuertemente recomendado\", \"recomendado\", o \"no recomendado\" (todo en minúsculas).";

        $result = $this->client->generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);
        
        $texto = $result->text();
        $texto = preg_replace('/```json\n?|\n?```/', '', $texto);
        
        return json_decode(trim($texto), true) ?? [
            'score' => 0,
            'fortalezas' => [],
            'brechas' => [],
            'riesgos' => [],
            'recomendacion' => 'no recomendado'
        ];
    }

    public function generarPreguntasEntrevista($vacante, $candidato, $evaluacion)
    {
        $brechas = implode(", ", $evaluacion['brechas'] ?? []);
        $riesgos = implode(", ", $evaluacion['riesgos'] ?? []);
        
        $experienciaTexto = '';
        if (!empty($candidato->experiencia)) {
            $experienciaTexto = collect($candidato->experiencia)->map(function($e) {
                return ($e['cargo'] ?? '') . ' en ' . ($e['empresa'] ?? '');
            })->implode(', ');
        }

        $prompt = "Eres un experto en recursos humanos. Genera preguntas de entrevista personalizadas:\n\n" .
                  "VACANTE: {$vacante->titulo}\n" .
                  "CANDIDATO: {$candidato->user->name}\n" .
                  "EXPERIENCIA: {$experienciaTexto}\n\n" .
                  "BRECHAS DETECTADAS: {$brechas}\n" .
                  "RIESGOS: {$riesgos}\n\n" .
                  "Genera 5 preguntas específicas para validar las brechas y riesgos detectados.\n" .
                  "Responde SOLO en JSON válido:\n" .
                  "{\"preguntas\": [\"pregunta 1\", \"pregunta 2\", \"pregunta 3\", \"pregunta 4\", \"pregunta 5\"]}";

        $result = $this->client->generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);
        
        $texto = $result->text();
        $texto = preg_replace('/```json\n?|\n?```/', '', $texto);
        
        $data = json_decode(trim($texto), true);
        
        return $data['preguntas'] ?? [];
    }
}
