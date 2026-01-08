<div>
    <button wire:click="abrirModal" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-xs">
        Notificar
    </button>

    @if($mostrarModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="cerrarModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Notificar Candidato</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado de la Candidatura
                    </label>
                    <select wire:model="estado" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="recibida">Candidatura Recibida</option>
                        <option value="en_revision">En Revisión</option>
                        <option value="preseleccionado">Preseleccionado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                    @error('estado') 
                        <span class="text-red-600 text-xs">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mensaje Adicional (Opcional)
                    </label>
                    <textarea wire:model="mensaje" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Mensaje personalizado para el candidato..."></textarea>
                    @error('mensaje') 
                        <span class="text-red-600 text-xs">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-4 text-sm">
                    <p class="font-semibold text-blue-800 mb-1">Vista previa:</p>
                    <p class="text-gray-700">
                        Se enviará un email a <strong>{{ $candidato->user->email }}</strong> 
                        notificando el estado: <strong>{{ ucfirst(str_replace('_', ' ', $estado)) }}</strong>
                    </p>
                </div>

                <div class="flex gap-2">
                    <button wire:click="enviarNotificacion" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Enviar Notificación
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
