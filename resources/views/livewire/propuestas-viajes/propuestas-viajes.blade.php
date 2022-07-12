<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Propuestas de Viajes') }}
        </h2>
    </x-slot>
    <div class="bg-white min-h-screen min-w-screen border-t border-gray-400 pt-2">
        <div class="mt-1 mx-auto">
            @if (Auth::user()->tipo_usuario == 2)
                <div class="w-11/12 mx-auto">
                    <button class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-green-400 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                        wire:click.prevent = "registrar()">
                        Nueva Propuesta
                    </button>
                </div>
            @endif
            @if (Auth::user()->tipo_usuario != 2)
                <div class="w-11/12 sm:mx-auto md:flex md:justify-between mt-2 mx-auto">
                    <div class="sm:w-full md:w-1/5 flex justify-around">
                        <div class="mt-3 mr-2 md:w-1/5">Ver</div>
                        <div class="w-2/5">
                            <select wire:model="cantidad" 
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value=10>10</option>
                                <option value=15>15</option>
                                <option value=20>20</option>
                                <option value=25>25</option>
                                <option value=50>50</option>
                            </select>
                        </div>
                        <div class="mt-3 ml-1 md:w-2/5">registros</div>
                    </div>
                    <div class="sm:w-full md:w-2/5">
                        <select wire:model="buscaOrigen" 
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option value="">Todas las ciudades</option>
                            @foreach ($ciudades as $ciudad)
                                <option value={{$ciudad->id}}>{{$ciudad->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:w-full md:w-2/5 ml-2 flex justify-around text-center">
                        <div class="w-full flex juestify-around">
                            <label for="small-range" class="mt-3 block mb-1 w-1/3 text-sm font-medium text-gray-900 dark:text-gray-300">Peso de Carga</label>
                            <input type="number" min="700" max="2000" step="10" 
                                class="block mt-1 w-2/3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                wire:model="buscaPesoCarga">
                        </div>
                    </div>
                </div>
            @endif
                @if($modalCreate)
                    @include('livewire.propuestas-viajes.modalCreate')
                @endif
                @if($modalUpdate)
                    @include('livewire.propuestas-viajes.modalUpdate')
                @endif
                @if($modalConfirm)
                    @include('livewire.propuestas-viajes.modalConfirm')
                @endif
            <div class="mt-4 shadow w-11/12 mx-auto">
                <table class="px-2 w-full border-1 border-gray-500">
                    <thead>
                        <tr class="bg-green-400">
                            <th class="w-1/6 text-white font-bold py-2 text-md border border-gray-700">
                                Origen / Destino
                            </th>
                            <th class="w-1/6 text-white font-bold py-2 text-md border border-gray-700">
                                Fecha de Viaje
                            </th>
                            <th class="w-1/6 text-white font-bold py-2 text-md border border-gray-700">
                                Tipo de Viaje
                            </th>
                            <th class="w-1/6 text-white font-bold py-2 text-md border border-gray-700">
                                Peso de Carga
                            </th>
                            <th class="w-1/6 text-white font-bold py-2 text-md border border-gray-700">
                                Estado
                            </th>
                            <th class="w-1/6 text-white font-bold py-2 text-md border border-gray-700">
                                Opciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($propuestas as $propuesta)
                        @if (Auth::user()->tipo_usuario == 3) 
                            @if ($propuesta->estado_viaje == 'ACTIVA')
                            <tr class="hover:bg-gray-200 text-sm text-center">
                                <td>
                                    {{$propuesta->origenN}}
                                     / 
                                     @foreach ($ciudades as $ciudad)
                                         @if ($ciudad->id == $propuesta->destino)
                                             {{$ciudad->nombre}}
                                         @endif
                                     @endforeach
                                </td>
                                <td>{{date("d-m-Y", strtotime($propuesta->fecha_viaje))}}</td>
                                <td>{{$propuesta->tipo_viaje}}</td>
                                <td>{{$propuesta->peso_carga}} Kilogramos</td>
                                <td>{{$propuesta->estado_viaje}}</td>
                                <td>
                                    <div class="md:flex md:justify-center my-2">
                                        <div class="sm:w-full md:w-auto">
                                            <button class="md:mr-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                wire:click.prevent = "verDetalles({{$propuesta->id}})">
                                                Ver Detalles
                                            </button>  
                                        </div>
                                        <div class="sm:mt-4 md:mt-0 sm:w-full md:w-auto">
                                            @if ($solicitudes->isEmpty())
                                                <button class="md:ml-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                   wire:click.prevent = "solicitarViaje({{$propuesta->id}})">
                                                    Solicitar Viaje
                                                </button>
                                            @else
                                                @php
                                                    $police = 0;
                                                @endphp
                                                @foreach($solicitudes as $solic)
                                                    @if ($propuesta->id != $solic->propuesta_id)
                                                        @php
                                                            $police = 5;
                                                        @endphp
                                                    @else
                                                        @if ($solic->estado == 'EN ESPERA')
                                                            @php
                                                                $police = 1;
                                                                break 1;
                                                            @endphp
                                                        @endif
                                                        @if ($solic->estado == 'APROBADO')
                                                            @php
                                                                $police = 2;
                                                                break 1;
                                                            @endphp
                                                        @endif
                                                        @if ($solic->estado == 'RECHAZADO')
                                                            @php
                                                                $police = 3;
                                                                break 1;
                                                            @endphp
                                                        @endif
                                                        @if ($solic->estado == 'VIAJE COMENZADO')
                                                            @php
                                                                $police = 4;
                                                                break 1;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @if ($police == 5)
                                                    <button class="md:ml-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                        wire:click.prevent = "solicitarViaje({{$propuesta->id}})">
                                                        Solicitar Viaje
                                                    </button>
                                                @endif
                                                @if ($police == 1)
                                                    <button class="md:ml-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                        wire:click.prevent = "cancelarSolicitudViaje({{$solic->id}})">
                                                        Cancelar Solicitud
                                                    </button>
                                                @endif
                                                @if ($police == 2)
                                                    <button class="md:ml-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                        wire:click.prevent = "comenzarViaje({{$solic->id}})"
                                                        wire:loading.attr = "disabled">
                                                        Comenzar Viaje
                                                    </button>
                                                @endif
                                                @if ($police == 3)
                                                    <button class="md:ml-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                        disabled>
                                                        Solicitud Rechazada
                                                    </button>
                                                @endif
                                                @if ($police == 4)
                                                    <button class="md:ml-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                        disabled>
                                                        Viaje Comenzado
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @else
                            <tr class="hover:bg-gray-200 text-sm text-center">
                                <td>
                                    @foreach ($ciudades as $ciudad)
                                        @if ($ciudad->id == $propuesta->origen)
                                            {{$ciudad->nombre}}
                                        @endif
                                    @endforeach
                                    / 
                                    @foreach ($ciudades as $ciudad)
                                        @if ($ciudad->id == $propuesta->destino)
                                            {{$ciudad->nombre}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{date("d-m-Y", strtotime($propuesta->fecha_viaje))}}</td>
                                <td>{{$propuesta->tipo_viaje}}</td>
                                <td>{{$propuesta->peso_carga}} Kilogramos</td>
                                <td>{{$propuesta->estado_viaje}}</td>
                                <td>
                                    <div class="md:flex md:justify-center my-2">
                                        <div class="sm:w-full md:w-auto">
                                            <button class="md:mr-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                wire:click.prevent = "verDetalles({{$propuesta->id}})">
                                                Ver Detalles
                                            </button>  
                                        </div>
                                        <div class="sm:mt-4 md:mt-0 sm:w-full md:w-auto">
                                            @if((Auth::user()->tipo_usuario == 2) && ($propuesta->estado_viaje == 'ACTIVA'))
                                                <button class="md:ml-2 sm:w-full md:w-auto inline-flex items-center sm:px-3 md:px-4 sm:py-1 md:py-2 bg-gray-700 hover:bg-green-500 font-bold text-sm text-white uppercase active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                                    wire:click.prevent = "cancelarViaje({{$propuesta->id}})">
                                                    Cancelar Viaje
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="w-11/12 mx-auto shadow">
                {{$propuestas->links()}}
            </div>
        </div>
    </div>
    <div class="w-full text-center bg-gray-700 font-bold text-green-400 text-md py-8">
            OnFlex. Conetando al país. 2022. - Todos los derechos reservados.
    </div>
</div>