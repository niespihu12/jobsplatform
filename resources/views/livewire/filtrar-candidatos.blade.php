<div>
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
            <button wire:click="limpiar" type="button"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Limpiar filtros
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" wire:model.live.debounce.500ms="busqueda" 
                    placeholder="Nombre o email..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Clasificación</label>
                <select wire:model.live="clasificacion" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">Todas</option>
                    <option value="Altamente Recomendado">Altamente Recomendado</option>
                    <option value="Recomendado">Recomendado</option>
                    <option value="En Observación">En Observación</option>
                    <option value="No Recomendado">No Recomendado</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recomendación IA</label>
                <select wire:model.live="recomendacion" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">Todas</option>
                    <option value="fuertemente recomendado">Fuertemente Recomendado</option>
                    <option value="recomendado">Recomendado</option>
                    <option value="no recomendado">No Recomendado</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Score: {{ $scoreMin }} - {{ $scoreMax }}
                </label>
                <div class="flex items-center gap-3">
                    <input type="range" wire:model.live.debounce.300ms="scoreMin" min="0" max="100" 
                        class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    <span class="text-xs text-gray-500">a</span>
                    <input type="range" wire:model.live.debounce.300ms="scoreMax" min="0" max="100" 
                        class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                </div>
            </div>
        </div>

        <div class="mt-3 text-xs text-gray-500">
            Mostrando {{ $candidatos->count() }} candidatos
        </div>
    </div>

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
            <p class="text-gray-500">No hay candidatos</p>
        </div>
        @endforelse
    </div>
</div>
