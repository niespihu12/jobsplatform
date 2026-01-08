<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shortlist - {{ $vacante->titulo }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .candidato { margin-bottom: 30px; page-break-inside: avoid; border: 1px solid #ddd; padding: 15px; }
        .nombre { font-size: 18px; font-weight: bold; color: #333; }
        .score { font-size: 24px; font-weight: bold; }
        .score.alto { color: #059669; }
        .score.medio { color: #2563eb; }
        .score.bajo { color: #dc2626; }
        .clasificacion { display: inline-block; padding: 5px 10px; border-radius: 5px; font-size: 11px; font-weight: bold; }
        .clasificacion.alto { background: #d1fae5; color: #065f46; }
        .clasificacion.medio { background: #dbeafe; color: #1e40af; }
        .clasificacion.observacion { background: #fef3c7; color: #92400e; }
        .clasificacion.bajo { background: #fee2e2; color: #991b1b; }
        .seccion { margin-top: 10px; }
        .seccion-titulo { font-weight: bold; margin-bottom: 5px; }
        ul { margin: 5px 0; padding-left: 20px; }
        li { margin-bottom: 3px; }
        .fortalezas { color: #059669; }
        .brechas { color: #d97706; }
        .riesgos { color: #dc2626; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Shortlist de Candidatos</h1>
        <h2>{{ $vacante->titulo }}</h2>
        <p><strong>Empresa:</strong> {{ $vacante->empresa }}</p>
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        <p><strong>Total candidatos:</strong> {{ $candidatos->count() }}</p>
    </div>

    @foreach($candidatos as $candidato)
    <div class="candidato">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <div>
                <div class="nombre">{{ $candidato->user->name }}</div>
                <div style="color: #666; font-size: 11px;">{{ $candidato->user->email }}</div>
            </div>
            <div style="text-align: right;">
                <div class="score {{ $candidato->score >= 80 ? 'alto' : ($candidato->score >= 60 ? 'medio' : 'bajo') }}">
                    {{ $candidato->score }}/100
                </div>
                <div class="clasificacion {{ $candidato->score >= 80 ? 'alto' : ($candidato->score >= 60 ? 'medio' : ($candidato->score >= 40 ? 'observacion' : 'bajo')) }}">
                    {{ $candidato->clasificacion }}
                </div>
            </div>
        </div>

        <div style="margin-bottom: 10px;">
            <strong>Recomendación IA:</strong> 
            <span style="color: {{ $candidato->evaluacion_ia['recomendacion'] == 'recomendado' ? '#059669' : '#dc2626' }}; font-weight: bold;">
                {{ ucfirst($candidato->evaluacion_ia['recomendacion'] ?? 'N/A') }}
            </span>
        </div>

        @if(!empty($candidato->evaluacion_ia['fortalezas']))
        <div class="seccion">
            <div class="seccion-titulo fortalezas">✓ Fortalezas:</div>
            <ul>
                @foreach($candidato->evaluacion_ia['fortalezas'] as $fortaleza)
                <li>{{ $fortaleza }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(!empty($candidato->evaluacion_ia['brechas']))
        <div class="seccion">
            <div class="seccion-titulo brechas">⚠ Brechas:</div>
            <ul>
                @foreach($candidato->evaluacion_ia['brechas'] as $brecha)
                <li>{{ $brecha }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(!empty($candidato->evaluacion_ia['riesgos']))
        <div class="seccion">
            <div class="seccion-titulo riesgos">✗ Riesgos:</div>
            <ul>
                @foreach($candidato->evaluacion_ia['riesgos'] as $riesgo)
                <li>{{ $riesgo }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div style="margin-top: 10px; font-size: 10px; color: #666;">
            Postulado: {{ $candidato->created_at->format('d/m/Y H:i') }}
        </div>
    </div>
    @endforeach

    <div class="footer">
        <p>Documento generado por DevJobs - Sistema de Reclutamiento con IA</p>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
