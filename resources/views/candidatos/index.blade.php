<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Candidatos - {{ $vacante->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Candidatos: {{$vacante->titulo}}</h1>
                        </div>
                        <div class="flex gap-2">
                            @if($vacante->candidatos->count() > 0)
                            <!-- Exportar PDF -->
                            <form action="{{ route('candidatos.exportar.pdf', $vacante) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    PDF
                                </button>
                            </form>
                            <!-- Exportar Excel -->
                            <form action="{{ route('candidatos.exportar.excel', $vacante) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Excel
                                </button>
                            </form>
                            @endif
                            @if($vacante->candidatos->count() >= 2)
                            <a href="{{ route('candidatos.comparar', $vacante) }}" 
                               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Comparar
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <form method="GET" action="{{ route('candidatos.index', $vacante) }}" id="filtrosForm">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
                                <a href="{{ route('candidatos.index', $vacante) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Limpiar filtros
                                </a>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                                    <input type="text" name="busqueda" value="{{ $filtros['busqueda'] }}" 
                                        placeholder="Nombre o email..."
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Clasificación</label>
                                    <select name="clasificacion" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        onchange="aplicarFiltros()">
                                        <option value="">Todas</option>
                                        <option value="Altamente Recomendado" {{ $filtros['clasificacion'] === 'Altamente Recomendado' ? 'selected' : '' }}>Altamente Recomendado</option>
                                        <option value="Recomendado" {{ $filtros['clasificacion'] === 'Recomendado' ? 'selected' : '' }}>Recomendado</option>
                                        <option value="En Observación" {{ $filtros['clasificacion'] === 'En Observación' ? 'selected' : '' }}>En Observación</option>
                                        <option value="No Recomendado" {{ $filtros['clasificacion'] === 'No Recomendado' ? 'selected' : '' }}>No Recomendado</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Recomendación IA</label>
                                    <select name="recomendacion" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        onchange="aplicarFiltros()">
                                        <option value="">Todas</option>
                                        <option value="fuertemente recomendado" {{ $filtros['recomendacion'] === 'fuertemente recomendado' ? 'selected' : '' }}>Fuertemente Recomendado</option>
                                        <option value="recomendado" {{ $filtros['recomendacion'] === 'recomendado' ? 'selected' : '' }}>Recomendado</option>
                                        <option value="no recomendado" {{ $filtros['recomendacion'] === 'no recomendado' ? 'selected' : '' }}>No Recomendado</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Score: <span id="scoreMinVal">{{ $filtros['scoreMin'] }}</span> - <span id="scoreMaxVal">{{ $filtros['scoreMax'] }}</span>
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <input type="range" name="scoreMin" value="{{ $filtros['scoreMin'] }}" min="0" max="100" 
                                            class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                            oninput="document.getElementById('scoreMinVal').textContent = this.value">
                                        <span class="text-xs text-gray-500">a</span>
                                        <input type="range" name="scoreMax" value="{{ $filtros['scoreMax'] }}" min="0" max="100" 
                                            class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                            oninput="document.getElementById('scoreMaxVal').textContent = this.value">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-xs text-gray-500">
                                    Mostrando {{ $candidatos->count() }} candidatos
                                </div>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded text-sm">
                                    Aplicar Filtros
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                    function aplicarFiltros() {
                        document.getElementById('filtrosForm').submit();
                    }
                    </script>

                    <!-- Lista de candidatos -->
                    <div class="space-y-4">
                        @forelse($candidatos as $candidato)
                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                            <div class="lg:flex lg:justify-between lg:items-start">
                                <div class="lg:flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $candidato->user->name }}</h3>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $candidato->clasificacion_color }}">
                                            {{ $candidato->clasificacion }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-600 mb-3">{{ $candidato->user->email }}</p>

                                    @if($candidato->score)
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex items-center">
                                            <span class="text-2xl font-bold {{ $candidato->score >= 80 ? 'text-green-600' : ($candidato->score >= 60 ? 'text-blue-600' : 'text-red-600') }}">
                                                {{ $candidato->score }}
                                            </span>
                                            <span class="text-gray-500 text-sm ml-1">/100</span>
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-1 rounded {{ $candidato->evaluacion_ia['recomendacion'] == 'recomendado' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($candidato->evaluacion_ia['recomendacion'] ?? '') }}
                                        </span>
                                    </div>

                                    @if(!empty($candidato->evaluacion_ia['fortalezas']))
                                    <div class="mb-2">
                                        <span class="text-xs font-semibold text-green-700">Fortalezas: </span>
                                        <span class="text-xs text-gray-700">
                                            {{ implode(', ', array_slice($candidato->evaluacion_ia['fortalezas'], 0, 2)) }}
                                        </span>
                                    </div>
                                    @endif

                                    @if(!empty($candidato->preguntas_entrevista))
                                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded">
                                        <h5 class="text-xs font-semibold text-blue-800 mb-2">❓ Preguntas Sugeridas:</h5>
                                        <ol class="list-decimal list-inside text-xs text-gray-800 space-y-1">
                                            @foreach(array_slice($candidato->preguntas_entrevista, 0, 3) as $pregunta)
                                            <li>{{ $pregunta }}</li>
                                            @endforeach
                                        </ol>
                                        @if(count($candidato->preguntas_entrevista) > 3)
                                        <div class="mt-2">
                                            <x-preguntas-entrevista :candidato="$candidato" />
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    @endif

                                    <p class="text-xs text-gray-500 mt-3">
                                        Postulado {{ $candidato->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="mt-4 lg:mt-0 lg:ml-4">
                                    <div class="flex flex-col gap-2">
                                        <a class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition"
                                            href="{{ asset('storage/cv/' . $candidato->cv) }}" target="_blank">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver CV
                                        </a>
                                        <livewire:feedback-candidato :candidato="$candidato" :key="'feedback-'.$candidato->id" />
                                        <livewire:notificar-candidato :candidato="$candidato" :key="'notificar-'.$candidato->id" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-16 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">No se encontraron candidatos</p>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>