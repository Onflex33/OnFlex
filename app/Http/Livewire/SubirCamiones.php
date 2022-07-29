<?php

namespace App\Http\Livewire;

use App\Models\Camion;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\TipoCamion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubirCamiones extends Component
{
    public $placa, $anno, $peso_soporte, $tipo_camion_id, $camion_id;
    public $modelos = null;
    public $selectedModelo = null, $selectedMarca = null;
    protected $rules = [
        'placa' => 'required',
        'peso_soporte' => 'required|max:30',
        'anno' => 'required|numeric|min:2000',
//        'marca_id' => 'required',
        'selectedModelo' => 'required',
        'tipo_camion_id' => 'required',
    ];

    public function render()
    {
        $marcas = Marca::where('status', '=', true)->get();
        $tipoCamion = TipoCamion::where('status', '=', true)->get();
        return view('livewire.subir-camiones', compact('marcas', 'tipoCamion'));
    }

    public function updatedselectedMarca($marca_id){
        $this->modelos = Modelo::where('marca_id', '=', $marca_id)->get();
    }


    public function guardar(){
        $this->validate();
        Camion::create([
            'placa' => $this->placa,
            'anno' => $this->anno,
            'peso_soporte' => $this->peso_soporte,
            'modelo_id' => $this->selectedModelo,
            'tipo_camion_id' => $this->tipo_camion_id,
            'transportista_id' => Auth::user()->id,
        ]);
        return redirect()->route('mensaje-final');
    }
}