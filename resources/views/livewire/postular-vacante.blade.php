<div class="bg-gray-100 p-5 mt-10 flex flex-col justify-center items-center">
    <h3 class="text-center text-2xl font-bold my-4">Postularme a esta vacante</h3>

    @if(session()->has('mensaje'))
        <div class="uppercase border rounded-lg p-4 my-5 text-sm w-96 text-center
            {{ str_contains(session('mensaje'), 'Score') ? 'border-green-600 bg-green-100 text-green-600' : 'border-yellow-600 bg-yellow-100 text-yellow-600' }} font-bold">
            {{session('mensaje')}}
        </div>
    @else
        <form class="w-96 mt-5">
            <div class="mb-4">
                <x-input-label for="cv" :value="__('Curriculum o Hoja de Vida (PDF)')" />
                <x-text-input id="cv" wire:model.live="cv" type="file" accept=".pdf" class="block mt-1 w-full" />
                
                @if($cv)
                    <p class="text-sm text-green-600 mt-2">✓ Procesando archivo...</p>
                @else
                    <p class="text-xs text-gray-500 mt-1">Solo archivos PDF. Máximo 5MB. Se procesará automáticamente.</p>
                @endif
            </div>

            @error('cv')
            <livewire:mostrar-alerta :message="$message" />
            @enderror
        </form>
    @endif
</div>
