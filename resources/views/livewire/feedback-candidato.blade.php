<div>
    <button wire:click="abrirModal" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-xs">
        {{ $candidato->feedback_score ? 'Editar Feedback' : 'Dar Feedback' }}
    </button>

    @if($mostrarModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="cerrarModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Feedback del Candidato</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Score Real (0-100)
                    </label>
                    <input type="number" wire:model="feedback_score" min="0" max="100"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('feedback_score') 
                        <span class="text-red-600 text-xs">{{ $message }}</span> 
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Score IA: {{ $candidato->score }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Comentarios
                    </label>
                    <textarea wire:model="feedback_comentario" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Observaciones sobre la evaluación..."></textarea>
                    @error('feedback_comentario') 
                        <span class="text-red-600 text-xs">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="contratado" class="rounded border-gray-300 text-indigo-600">
                        <span class="ml-2 text-sm text-gray-700">Candidato contratado</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button wire:click="guardarFeedback" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Guardar
                    </button>
                    <button wire:click="cerrarModal" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session()->has('mensaje'))
        <div class="mt-2 text-green-600 text-xs">{{ session('mensaje') }}</div>
    @endif
</div>
