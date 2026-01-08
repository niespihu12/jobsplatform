<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comparación de Candidatos - {{ $vacante->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Comparación de Candidatos</h1>
                        <a href="{{ route('candidatos.index', $vacante) }}" class="text-indigo-600 hover:text-indigo-800">
                            ← Volver a candidatos
                        </a>
                    </div>

                    <!-- Tabla comparativa -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Criterio
                                    </th>
                                    @foreach($candidatos as $candidato)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $candidato->user->name }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Score -->
                                <tr class="bg-blue-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">Score IA</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-2xl font-bold {{ $candidato->score >= 80 ? 'text-green-600' : ($candidato->score >= 60 ? 'text-blue-600' : 'text-red-600') }}">
                                            {{ $candidato->score }}
                                        </span>
                                        <span class="text-gray-500">/100</span>
                                    </td>
                                    @endforeach
                                </tr>

                                <!-- Clasificación -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">Clasificación</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $candidato->clasificacion_color }}">
                                            {{ $candidato->clasificacion }}
                                        </span>
                                    </td>
                                    @endforeach
                                </tr>

                                <!-- Recomendación -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">Recomendación</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold {{ $candidato->evaluacion_ia['recomendacion'] == 'recomendado' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ ucfirst($candidato->evaluacion_ia['recomendacion'] ?? 'N/A') }}
                                        </span>
                                    </td>
                                    @endforeach
                                </tr>

                                <!-- Fortalezas -->
                                <tr>
                                    <td class="px-6 py-4 font-bold align-top">Fortalezas</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4">
                                        <ul class="list-disc list-inside text-sm space-y-1">
                                            @foreach($candidato->evaluacion_ia['fortalezas'] ?? [] as $fortaleza)
                                            <li class="text-green-700">{{ $fortaleza }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    @endforeach
                                </tr>

                                <!-- Brechas -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 font-bold align-top">Brechas</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4">
                                        <ul class="list-disc list-inside text-sm space-y-1">
                                            @foreach($candidato->evaluacion_ia['brechas'] ?? [] as $brecha)
                                            <li class="text-yellow-700">{{ $brecha }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    @endforeach
                                </tr>

                                <!-- Riesgos -->
                                <tr>
                                    <td class="px-6 py-4 font-bold align-top">Riesgos</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4">
                                        <ul class="list-disc list-inside text-sm space-y-1">
                                            @foreach($candidato->evaluacion_ia['riesgos'] ?? [] as $riesgo)
                                            <li class="text-red-700">{{ $riesgo }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    @endforeach
                                </tr>

                                <!-- Experiencia -->
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 font-bold align-top">Experiencia</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4">
                                        @if(!empty($candidato->experiencia))
                                            <div class="text-sm space-y-2">
                                                @foreach(array_slice($candidato->experiencia, 0, 3) as $exp)
                                                <div>
                                                    <p class="font-semibold">{{ $exp['cargo'] ?? 'N/A' }}</p>
                                                    <p class="text-gray-600">{{ $exp['empresa'] ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $exp['periodo'] ?? 'N/A' }}</p>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">Sin datos</span>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>

                                <!-- Habilidades -->
                                <tr>
                                    <td class="px-6 py-4 font-bold align-top">Habilidades</td>
                                    @foreach($candidatos as $candidato)
                                    <td class="px-6 py-4">
                                        @if(!empty($candidato->habilidades))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice($candidato->habilidades, 0, 8) as $habilidad)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ is_string($habilidad) ? $habilidad : (is_array($habilidad) ? ($habilidad['nombre'] ?? 'N/A') : 'N/A') }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">Sin datos</span>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Recomendación final -->
                    <div class="mt-8 p-6 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <h3 class="text-xl font-bold text-indigo-900 mb-3">Candidato Recomendado</h3>
                        @php
                            $mejor = $candidatos->sortByDesc('score')->first();
                        @endphp
                        <p class="text-lg">
                            <span class="font-bold text-indigo-700">{{ $mejor->user->name }}</span> 
                            con un score de 
                            <span class="font-bold text-indigo-700">{{ $mejor->score }}/100</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
