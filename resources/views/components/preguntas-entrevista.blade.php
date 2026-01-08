@props(['candidato'])

@if(!empty($candidato->preguntas_entrevista))
<div x-data="{ open: false }">
    <button @click="open = true" type="button" class="text-xs text-blue-600 hover:text-blue-800 underline mt-1">
        Ver todas ({{ count($candidato->preguntas_entrevista) }} preguntas)
    </button>

    <template x-teleport="body">
        <div x-show="open" 
             x-cloak
             @click="open = false"
             class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div @click.stop class="relative mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Preguntas de Entrevista - {{ $candidato->user->name }}</h3>
                    <button @click="open = false" type="button" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-3">Preguntas generadas por IA basadas en las brechas y riesgos detectados:</p>
                    <ol class="list-decimal list-inside space-y-3">
                        @foreach($candidato->preguntas_entrevista as $pregunta)
                        <li class="text-gray-800 leading-relaxed">{{ $pregunta }}</li>
                        @endforeach
                    </ol>
                </div>

                <div class="mt-4 flex justify-end">
                    <button @click="open = false" type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endif
