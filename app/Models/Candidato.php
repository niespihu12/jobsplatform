<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacante_id',
        'cv',
        'cv_texto',
        'experiencia',
        'educacion',
        'habilidades',
        'idiomas',
        'certificaciones',
        'score',
        'evaluacion_ia',
        'preguntas_entrevista',
        'feedback_score',
        'feedback_comentario',
        'contratado'
    ];

    protected $casts = [
        'experiencia' => 'array',
        'educacion' => 'array',
        'habilidades' => 'array',
        'idiomas' => 'array',
        'certificaciones' => 'array',
        'evaluacion_ia' => 'array',
        'preguntas_entrevista' => 'array',
        'contratado' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vacante()
    {
        return $this->belongsTo(Vacante::class);
    }

    public function getClasificacionAttribute()
    {
        if ($this->score >= 80) {
            return 'Altamente Recomendado';
        } elseif ($this->score >= 60) {
            return 'Recomendado';
        } elseif ($this->score >= 40) {
            return 'En Observación';
        } else {
            return 'No Recomendado';
        }
    }

    public function getClasificacionColorAttribute()
    {
        if ($this->score >= 80) {
            return 'bg-green-100 text-green-800 border-green-300';
        } elseif ($this->score >= 60) {
            return 'bg-blue-100 text-blue-800 border-blue-300';
        } elseif ($this->score >= 40) {
            return 'bg-yellow-100 text-yellow-800 border-yellow-300';
        } else {
            return 'bg-red-100 text-red-800 border-red-300';
        }
    }
}
