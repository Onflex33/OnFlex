<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#303c4e] leading-tight">
            {{ __('Mi Beneficiario') }}
        </h2>
    </x-slot>
    @if ($modalCreate)
        @include('livewire.beneficiarios.modalCreate')
    @endif
    @if ($modalUpdate)
        @include('livewire.beneficiarios.modalUpdate')
    @endif
    @if ($modalConfirm)
        @include('livewire.beneficiarios.modalConfirm')
    @endif
    <div class="bg-white min-h-screen min-w-screen pt-2">
        <div class="mt-1 mx-auto">
            @if ($beneficiario->isEmpty())
                <div class = "my-2 w-4/5 p-4 text-center mx-auto font-bold text-sm rounded-md shadow bg-[#303c4e] text-white">
                    No tienes un beneficiario registrado, agrégalo haciendo clic en el botón de abajo  
                </div>
                <div class="w-11/12 mx-auto text-center mt-2">
                    <button class="inline-flex items-center px-4 py-2 rounded-md bg-[#303c4e] font-bold text-sm text-white hover:text-[#00f2a1] active:bg-[#303c4e] focus:outline-none focus:border-[#303c4e] focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                        wire:click.prevent = "registrarBeneficiario()">
                        Agregar Beneficiario
                    </button>
                </div>
            @else
                <div class="mt-4 sm:w-5/6 md:w-1/2 shadow w-11/12 mx-auto">
                    @foreach ($beneficiario as $b)
                        <div class="mt-4">
                            <x-jet-label for="nombre" value="{{ __('Cédula') }}" />
                            <div class="block w-full text-lg font-bold rounded-md shadow-sm">
                                {{$b->cedula}}
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-jet-label for="nombre" value="{{ __('Nombre y Apellido') }}" />
                            <div class="block w-full text-lg font-bold rounded-md shadow-sm">
                                {{$b->nombre}} {{$b->apellido}}
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-jet-label for="nombre" value="{{ __('Correo Electrónico') }}" />
                            <div class="block w-full text-lg font-bold rounded-md shadow-sm">
                                {{$b->email}}
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-jet-label for="nombre" value="{{ __('Teléfono') }}" />
                            <div class="block w-full text-lg font-bold rounded-md shadow-sm">
                                {{$b->telefono}}
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-jet-label for="nombre" value="{{ __('Sexo') }}" />
                            <div class="block w-full text-lg font-bold rounded-md shadow-sm">
                                {{$b->sexo}}
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-jet-label for="nombre" value="{{ __('Ciudad') }}" />
                            <div class="block w-full text-lg font-bold rounded-md shadow-sm">
                                {{$b->ciudad}}
                            </div>
                        </div>
                        @php
                            $hoy = date('Y-m-d');
                            $hoy = date('Y-m-d', strtotime($hoy));
                            $cp = date('Y-m-d', strtotime($b->updated_at.'+ 90 days')); //cambios permitidos
                        @endphp
                        <div class="flex justify-center text-center my-2">
                            <div class="w-1/2 md:w-auto">
                                <button class="ml-0 mt-1 md:mt-0 md:ml-1 inline-flex items-center px-4 py-2 rounded-md bg-[#303c4e] font-bold text-sm text-white hover:text-[#00f2a1] active:bg-[#303c4e] focus:outline-none focus:border-[#303c4e] focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                    wire:click.prevent = "editar({{$b->id}})" @if ($hoy<$cp) disabled="disabled" @endif>
                                    Editar
                                </button>  
                            </div>
                            <div class="w-1/2 md:ml-1 md:w-auto">
                                <button class="ml-0 mt-1 md:mt-0 md:ml-1 inline-flex items-center px-4 py-2 rounded-md bg-[#303c4e] font-bold text-sm text-white hover:text-[#00f2a1] active:bg-[#303c4e] focus:outline-none focus:border-[#303c4e] focus:ring focus:ring-gray-600 disabled:opacity-50 transition"
                                    wire:click.prevent = "eliminar({{$b->id}})" @if ($hoy<$cp) disabled="disabled" @endif>
                                    Eliminar
                                </button>  
                            </div>
                        </div>
                        <div class="w-full p-4 border boder-[#303c4e] rounded-sm">
                            Podrá hacer cambios o eliminar al beneficiario después de 90 días, luego del registro o última modificación.<br>
                            Fecha de últimos cambios: {{date('d-m-Y', strtotime($b->updated_at))}}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="w-full text-center bg-[#303c4e] font-bold text-[#00f2a1] text-md py-8">
            OnFlex. Conetando al país. 2022. - Todos los derechos reservados.
    </div>
</div>