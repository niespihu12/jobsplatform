<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comparar Candidatos - {{ $vacante->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-3xl font-bold mb-2">Selecciona candidatos para comparar</h1>
                    <p class="text-gray-600 mb-6">Selecciona entre 2 y 5 candidatos</p>

                    <form action="{{ route('candidatos.comparar.post', $vacante) }}" method="POST">
                        @csrf
                        
                        <div class="space-y-3 mb-6">
                            @foreach($candidatos as $candidato)
                            <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="candidatos[]" value="{{ $candidato->id }}" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-4">
                                
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="font-bold text-lg">{{ $candidato->user->name }}</h3>
                                        <span class="px-2 py-1 rounded text-xs font-bold {{ $candidato->clasificacion_color }}">
                                            {{ $candidato->clasificacion }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $candidato->user->email }}</p>
                                    <div class="mt-2">
                                        <span class="text-2xl font-bold {{ $candidato->score >= 80 ? 'text-green-600' : ($candidato->score >= 60 ? 'text-blue-600' : 'text-red-600') }}">
                                            {{ $candidato->score }}
                                        </span>
                                        <span class="text-gray-500">/100</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        @error('candidatos')
                            <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
                        @enderror

                        <div class="flex gap-3">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded">
                                Comparar Seleccionados
                            </button>
                            <a href="{{ route('candidatos.index', $vacante) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded">
                                Cancelar
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
