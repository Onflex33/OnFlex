<div>
    <x-guest-layout>
        <x-jet-authentication-card>
            <x-slot name="logo">
                 <x-jet-authentication-card-logo />
            </x-slot>
            <div class="mb-4 text-sm text-gray-600">
                {{ __('¡Excelente! Ya conocemos tus datos y cómo te ves. Te queda 1 paso: Necesitas regitrar un camión para poder ver todas las propuestas de viaje cerca de tu ubicación.') }}
            </div>
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            <div class="mt-4">
                <x-jet-label for="placa" value="{{ __('Placa') }}" />
                <input id="placa" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                    type="text" min="6" name="placa" wire:model.defer="placa" required />
                @error('placa')
                    <div id="text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="peso_soporte" value="{{ __('Peso de Soporte (En Toneladas)') }}" />
                <input id="peso_soporte" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                    type="number" max="30" name="peso_soporte" wire:model.defer="peso_soporte" required />
                @error('peso_soporte')
                    <div id="text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="anno" value="{{ __('Año') }}" />
                <input id="anno" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                    type="number" min="2000" max="{{date('Y')}}" step="1.0" name="anno" wire:model.defer="anno" required />
                @error('anno')
                    <div id="text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="marca" value="{{ __('Marca') }}" />
                <select id="marca_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                    name="marca_id" wire:model="selectedMarca" required>
                    <option class="bg-gray-200" value=null>Seleccione una Marca</option>
                    @foreach ($marcas as $marca)
                        <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                    @endforeach
                </select>
                @error('selectedMarca')
                    <div id="text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="modelo" value="{{ __('Modelo') }}" />
                <select id="selectedModelo" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                    name="selectedModelo" wire:model="selectedModelo" required>
                    <option class="bg-gray-200" value=null>Seleccione un Modelo</option>
                    @if (!is_null($modelos))
                        @foreach ($modelos as $modelo)
                            <option value="{{$modelo->id}}">{{$modelo->nombre}}</option>
                        @endforeach
                    @endif
                </select>
                @error('selectedModelo')
                    <div id="text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="tipo_camion" value="{{ __('Tipo de Camion') }}" />
                <select id="tipo_camion_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                    name="tipo_camion_id" wire:model.defer="tipo_camion_id" required>
                    <option class="bg-gray-200" value=null>Seleccione un Tipo de Camión</option>
                    @foreach ($tipoCamion as $tc)
                        <option value="{{$tc->id}}">{{$tc->nombre}}</option>
                    @endforeach
                </select>
                @error('tipo_camion_id')
                    <div id="text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mx-auto flex justify-between w-11/12 mt-4">
                <div class="md:w-1/2 sm:w-full sm:text-center">
                    <button class="inline-flex items-center px-4 py-2 rounded-md bg-gray-700 font-bold text-sm text-white hover:text-green-400 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                        wire:click.prevent = "guardar()"
                        wire:loading.attr="disabled">
                        Guardar
                    </button>
                </div>
            </div>
        </x-jet-authentication-card>
    </x-guest-layout>
</div>