<form class="md:w-1/2 space-y-5" wire:submit.prevent="editarVacante">
    <div>
        <x-input-label for="titulo" :value="__('Titulo Vacante')"/>
        <x-text-input id="titulo" class="block mt-1 w-full" type="text" wire:model="titulo" :value="old('titulo')"
                      placeholder="Titulo Vacante"/>
        @error('titulo')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror
    </div>

    <div>
        <x-input-label for="salario" :value="__('Salario Mensual')"/>
        <select id="salario" wire:model="salario"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
            <option value="">-- Seleccione --</option>
            @foreach($salarios as $salario)
                <option value="{{$salario->id}}">{{$salario->salario}}</option>
            @endforeach

        </select>
        @error('salario')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror

    </div>

    <div>
        <x-input-label for="categoria" :value="__('Categoria')"/>
        <select id="categoria" wire:model="categoria"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
            <option value="">-- Seleccione --</option>
            @foreach($categorias as $categoria)
                <option value="{{$categoria->id}}">{{$categoria->categoria}}</option>
            @endforeach

        </select>
        @error('categoria')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror
    </div>

    <div>
        <x-input-label for="empresa" :value="__('Empresa')"/>
        <x-text-input id="empresa" class="block mt-1 w-full" type="text" wire:model="empresa" :value="old('empresa')"
                      placeholder="Empresa: ej. Netflix, Uber, Shopify"/>
        @error('empresa')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror
    </div>

    <div>
        <x-input-label for="ultimo_dia" :value="__('Ultimo Dia para postularse')"/>
        <x-text-input id="ultimo_dia" class="block mt-1 w-full" type="date" wire:model="ultimo_dia"
                      :value="old('ultimo_dia')"/>
        @error('ultimo_dia')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror
    </div>

    <div>
        <x-input-label for="limite_candidatos" :value="__('Límite de Candidatos (Opcional)')"/>
        <x-text-input id="limite_candidatos" class="block mt-1 w-full" type="number" wire:model="limite_candidatos" 
            placeholder="Ej: 50" min="1" />
        <p class="text-xs text-gray-500 mt-1">Deja vacío para no establecer límite</p>
        @error('limite_candidatos')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror
    </div>

    <div>
        <x-input-label for="descripcion" :value="__('Descripcion Puesto')"/>
        <textarea wire:model="descripcion" placeholder="Descripcion general del puesto, experiencia"
                  class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full h-72">
       </textarea>
        @error('descripcion')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror
    </div>

    {{-- Criterios IA --}}
    <div class="mb-5">
        <h3 class="text-xl font-bold mb-3">Criterios de Evaluación IA</h3>

        @foreach($criterios as $i => $criterio)
        <div class="border rounded p-4 mb-3 bg-gray-50">
            <div class="flex justify-between mb-3">
                <h4 class="font-semibold">Criterio {{ $i + 1 }}</h4>
                @if($i > 0)
                <button type="button" wire:click="eliminarCriterio({{ $i }})" class="text-red-600">✕</button>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label :value="'Nombre'" />
                    <x-text-input wire:model="criterios.{{ $i }}.nombre" type="text" class="w-full" placeholder="Ej: Laravel 5+ años" />
                    <x-input-error :messages="$errors->get('criterios.'.$i.'.nombre')" class="mt-2" />
                </div>

                <div>
                    <x-input-label :value="'Tipo'" />
                    <select wire:model="criterios.{{ $i }}.tipo" class="border-gray-300 rounded-md w-full">
                        <option value="experiencia">Experiencia</option>
                        <option value="educacion">Educación</option>
                        <option value="habilidad">Habilidad</option>
                        <option value="idioma">Idioma</option>
                        <option value="certificacion">Certificación</option>
                    </select>
                </div>

                <div>
                    <x-input-label :value="'Peso (1-10)'" />
                    <x-text-input wire:model="criterios.{{ $i }}.peso" type="number" min="1" max="10" class="w-full" />
                </div>

                <div class="flex items-center pt-6">
                    <input wire:model="criterios.{{ $i }}.obligatorio" type="checkbox" class="rounded">
                    <span class="ml-2 text-sm">Obligatorio</span>
                </div>
            </div>

            <div class="mt-3">
                <x-input-label :value="'Descripción'" />
                <textarea wire:model="criterios.{{ $i }}.descripcion" class="border-gray-300 rounded-md w-full" rows="2"></textarea>
            </div>
        </div>
        @endforeach

        <button type="button" wire:click="agregarCriterio" class="bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 rounded">
            + Agregar Criterio
        </button>
    </div>

    <div>
        <x-input-label for="imagen" :value="__('Imagen')"/>
        <x-text-input id="imagen" class="block mt-1 w-full" type="file" wire:model="imagen_nueva" accept="image/*"/>
        <div class="my-5 w-80">
            <x-input-label :value="__('Imagen Actual')"/>

            <img src="{{ asset('storage/vacante/' . $imagen) }}" alt="{{ $titulo }}">
        </div>
        <div class="my-5 w-80">
            @if($imagen_nueva)
                Imagen Nueva:
                <img src="{{ $imagen_nueva->temporaryUrl() }}">

            @endif
        </div>
        @error('imagen_nueva')
        <livewire:mostrar-alerta :message="$message"/>
        @enderror

    </div>

    <x-primary-button class="w-full justify-center">
        {{ __('Guardar Cambios') }}
    </x-primary-button>


</form>
