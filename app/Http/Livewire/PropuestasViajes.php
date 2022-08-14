<?php

namespace App\Http\Livewire;

use App\Models\Ciudad;
use App\Models\PropuestaViaje;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\Viaje;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Notification;
use App\Notifications\RegistroPropuesta;
use App\Notifications\BorradoPropuesta;
use App\Notifications\RegistroMiPropuesta;
use App\Notifications\BorradoMiPropuesta;
use App\Notifications\solicitarViaje;
use App\Notifications\ComenzarViaje;
use App\Notifications\ComenzarViajeEmpresa;
use App\Notifications\CancelarViaje;
use App\Notifications\RechazarViaje;
use App\Notifications\ReconsiderarViaje;
use App\Notifications\AceptarViaje;

class PropuestasViajes extends Component
{
    use WithPagination;
    public $cantidad = 10, $num_viajes = 1;
    public $buscaOrigen = null, $buscaPesoCarga = 700;
    public $origen, $destino, $fecha_viaje, $carga_total, $tipo_viaje, $id_viaje;
    public $hora_publicacion, $fecha_publicacion, $estado_oferta, $empresa, $observacion, $perfil_id, $propuesta_id;
    public $detalles;
    public $solicitudesViaje = null;
    public $modalCreate = false, $modalUpdate = false, $modalConfirm = false;
    protected $rules = [
        'fecha_viaje' => 'required',
        'tipo_viaje' => 'required',
        'carga_total' => 'required|numeric|max:35',
        'origen' => 'required',
        'destino' => 'required',
        'observacion' => 'max:250',
    ];

    public function render()
    {
        if(Auth::user()->tipo_usuario == 2){
            $propuestas = PropuestaViaje::select(
                    'origen_id AS origen',
                    'destino_id AS destino',
                    'fecha_viaje AS fecha_viaje',
                    'tipo_viaje AS tipo_viaje',
                    'peso_carga_total AS peso_carga',
                    'estado_oferta AS estado_viaje',
                    'id AS id',
                )
                ->where('id_empresa', '=', Auth::user()->id)
                ->orderBy('propuesta_viaje.fecha_viaje', 'desc')
                ->paginate($this->cantidad);
        }
        if((Auth::user()->tipo_usuario == 3) || (Auth::user()->tipo_usuario == 1)){
            if($this->buscaOrigen == null){
                $propuestas = PropuestaViaje::join('ciudades', 'ciudades.id', '=', 'propuesta_viaje.origen_id')
                    ->select(
                        'propuesta_viaje.origen_id AS origen',
                        'propuesta_viaje.destino_id AS destino',
                        'propuesta_viaje.fecha_viaje AS fecha_viaje',
                        'propuesta_viaje.id AS id',
                        'propuesta_viaje.tipo_viaje AS tipo_viaje',
                        'propuesta_viaje.estado_oferta AS estado_viaje',
                        'propuesta_viaje.peso_carga_total AS peso_carga',
                        'ciudades.nombre AS origenN',
                    )
                    ->orderBy('propuesta_viaje.fecha_viaje', 'desc')
                    ->paginate($this->cantidad);
            }else{
                $propuestas = PropuestaViaje::join('ciudades', 'ciudades.id', '=', 'propuesta_viaje.origen_id')
                    ->select(
                        'propuesta_viaje.origen_id AS origen',
                        'propuesta_viaje.destino_id AS destino',
                        'propuesta_viaje.fecha_viaje AS fecha_viaje',
                        'propuesta_viaje.id AS id',
                        'propuesta_viaje.tipo_viaje AS tipo_viaje',
                        'propuesta_viaje.estado_oferta AS estado_viaje',
                        'propuesta_viaje.peso_carga_total AS peso_carga',
                        'ciudades.nombre AS origenN',
                    )
                    ->where('propuesta_viaje.origen_id', '=', $this->buscaOrigen)
                    ->orderBy('propuesta_viaje.fecha_viaje', 'desc')
                    ->paginate($this->cantidad);
            }
        }
        $ciudades = Ciudad::orderBy('nombre', 'asc')->get();
        $solicitudes = Solicitud::where('transportista_id', '=', Auth::user()->id)
            ->get();
        return view('livewire.propuestas-viajes.propuestas-viajes', compact('solicitudes','propuestas','ciudades'));
    }

    public function registrar(){
        $this->limpiarCampos();
        $this->abrirModalCreate();
    }

    public function guardar(){
        date_default_timezone_set('America/Caracas'); 
        $this->validate();
        $prop = PropuestaViaje::create([
            'hora_publicacion' => date("H:i:s", time()),
            'fecha_publicacion' => date("Y-m-d"),
            'fecha_viaje' => $this->fecha_viaje, 
            'peso_carga_total' => $this->carga_total, 
            'tipo_viaje' => $this->tipo_viaje, 
            'estado_oferta' => 'ACTIVA', 
            'origen_id' => $this->origen,
            'destino_id' => $this->destino,
            'observacion' => $this->observacion,
            'id_empresa' => Auth::user()->id, 
        ]);
        Notification::send(Auth::user(), new RegistroMiPropuesta($prop));
        $camioneros = User::where('tipo_usuario',2)->get();
        //Notification::send($camioneros, new RegistroPropuesta($prop));
        $this->cerrarModalCreate();
    }

    public function verDetalles($id_prop){
        $propuesta = PropuestaViaje::find($id_prop);
        $this->propuesta_id = $propuesta->id;
        $this->hora_publicacion = $propuesta->hora_publicacion;
        $this->fecha_publicacion = $propuesta->fecha_publicacion;
        $this->fecha_viaje = $propuesta->fecha_viaje;
        $this->carga_total = $propuesta->peso_carga_total;
        $this->tipo_viaje = $propuesta->tipo_viaje;
        $this->estado_oferta = $propuesta->estado_oferta;
        $this->origen = $propuesta->origen_id;
        $this->destino = $propuesta->destino_id;
        $this->observacion = $propuesta->observacion;
        if((Auth::user()->tipo_usuario == 3) OR (Auth::user()->tipo_usuario == 1)){
            $datosEmpresa = User::find($propuesta->id_empresa);
            $this->empresa = $datosEmpresa->nombre;
            $this->perfil_id = $datosEmpresa->id;
            $this->solicitudesViaje = Solicitud::join('users', 'solicitud.transportista_id', '=', 'users.id')
                ->join('transportista', 'users.id', '=', 'transportista.usuario_id')
                ->select(
                    'solicitud.estado AS estado',
                    'solicitud.id AS idSolic',
                )
                ->where('solicitud.propuesta_id', '=', $id_prop)
                ->get();
        }
        if((Auth::user()->tipo_usuario == 2) OR (Auth::user()->tipo_usuario == 1)){
            $this->solicitudesViaje = Solicitud::join('users', 'solicitud.transportista_id', '=', 'users.id')
                ->join('transportista', 'users.id', '=', 'transportista.usuario_id')
                ->select(
                    'users.id AS idT',
                    'users.nombre AS nombreT',
                    'transportista.apellido AS apellidoT',
                    'solicitud.id AS idSolic',
                    'solicitud.estado AS estado',
                )
                ->where('solicitud.propuesta_id', '=', $id_prop)
                ->get();
        }
        $this->abrirModalUpdate();
    }

    public function cancelarViaje($id_prop){
        $propuesta = PropuestaViaje::find($id_prop);
        $this->id_viaje = $propuesta->id;
        $this->abrirModalConfirm();
    }

    public function cancelar($id_prop){
        $viaje = PropuestaViaje::join('solicitud', 'propuesta_viaje.id', '=', 'solicitud.propuesta_id')
            ->join('viaje', 'viaje.solicitud_id', '=', 'solicitud.id')
            ->where('propuesta_viaje.id', '=', $id_prop)
            ->get();
        if($viaje->isEmpty()){
            PropuestaViaje::updateOrCreate(['id' => $id_prop],[
                'estado_oferta' => 'CANCELADO'
            ]);
            Solicitud::where('propuesta_id', '=', $id_prop)
                ->delete();
        }else{
            session()->flash('mensaje', 'Ya este viaje fue comenzado, no se puede cancelar.');
        }
        $this->cerrarModalConfirm();
    }

    public function solicitarViaje($id_prop){
        $solicitud = Solicitud::create([
            'propuesta_id' => $id_prop,
            'transportista_id' => Auth::user()->id,
            'estado' => 'EN ESPERA',
        ]);

        //Notification::send(Auth::user(), new SolicitarViaje($solicitud));

    }

    public function resolicitarViaje($id_solic){
        Solicitud::updateOrCreate(['id' => $id_solic], [
            'estado' => 'EN ESPERA',
        ]);
        $this->cerrarModalUpdate();
    }

    public function comenzarViaje($id_solic){
        Viaje::create([
            'estado' => 'LISTA DE CARGA',
            'solicitud_id' => $id_solic
        ]);
        $solicitud = Solicitud::updateOrCreate(['id' => $id_solic],[
            'estado' => 'VIAJE COMENZADO',
        ]);
        $empresa = User::find($solicitud->id_empresa);
        $transportista = User::find($solicitud->transportista_id);
//        Notification::send($empresa, new ComenzarViajeEmpresa($solicitud));
//        Notification::send($transportista, new ComenzarViaje($solicitud));
    }

    public function aceptarSolicitudViaje($id_solV){
        $solicitud = Solicitud::findOrFail($id_solV);
        $id_transp = $solicitud->transportista_id;
        $id_propuesta = $solicitud->propuesta_id;
        Solicitud::where('propuesta_id', $id_propuesta)
            ->where('transportista_id', '<>', $id_transp)
            ->update(['estado' => 'RECHAZADO']);
        Solicitud::where('propuesta_id', $id_propuesta)
            ->where('transportista_id', $id_transp)
            ->where('id', $id_solV)
            ->update(['estado' => 'APROBADO']);
            $this->solicitudesViaje = Solicitud::join('users', 'solicitud.transportista_id', '=', 'users.id')
                ->join('transportista', 'users.id', '=', 'transportista.usuario_id')
                ->select(
                    'users.id AS idT',
                    'users.nombre AS nombreT',
                    'transportista.apellido AS apellidoT',
                    'solicitud.id AS idSolic',
                    'solicitud.estado AS estado',
                )
                ->where('solicitud.propuesta_id', '=', $id_propuesta)
                ->get();
        Notification::send(User::find($id_transp), new AceptarViaje($solicitud));
    }

    public function rechazarSolicitudViaje($id_solV){
        $solicitud = Solicitud::findOrFail($id_solV);
        $id_transp = $solicitud->transportista_id;
        $id_propuesta = $solicitud->propuesta_id;
        Solicitud::where('propuesta_id', $id_propuesta)
            ->where('transportista_id', $id_transp)
            ->where('id', $id_solV)
            ->update(['estado' => 'RECHAZADO']);
            $this->solicitudesViaje = Solicitud::join('users', 'solicitud.transportista_id', '=', 'users.id')
                ->join('transportista', 'users.id', '=', 'transportista.usuario_id')
                ->select(
                    'users.id AS idT',
                    'users.nombre AS nombreT',
                    'transportista.apellido AS apellidoT',
                    'solicitud.id AS idSolic',
                    'solicitud.estado AS estado',
                )
                ->where('solicitud.propuesta_id', '=', $id_propuesta)
                ->get();
    }

    public function reconsiderarSolicitudViaje($id_solV){
        $solicitud = Solicitud::findOrFail($id_solV);
        $id_propuesta = $solicitud->propuesta_id;
        Solicitud::where('propuesta_id', $id_propuesta)
            ->update(['estado' => 'EN ESPERA']);
            $this->solicitudesViaje = Solicitud::join('users', 'solicitud.transportista_id', '=', 'users.id')
                ->join('transportista', 'users.id', '=', 'transportista.usuario_id')
                ->select(
                    'users.id AS idT',
                    'users.nombre AS nombreT',
                    'transportista.apellido AS apellidoT',
                    'solicitud.id AS idSolic',
                    'solicitud.estado AS estado',
                )
                ->where('solicitud.propuesta_id', '=', $id_propuesta)
                ->get();
        Notification::send(User::find($solicitud->transportista_id), new ReconsiderarViaje($solicitud));
    }

    public function cancelarSolicitudViaje($id_solicitud){
        $solicitud = Solicitud::find($id_solicitud);
        Notification::send(Auth::user(), new CancelarViaje($solicitud));
        $solicitud->delete();
    }

    public function aceptar($id_prop){
        PropuestaViaje::updateOrCreate(['id' => $id_prop],[
            'estado_oferta' => 'ACEPTADA'
        ]);
        $this->cerrarModalConfirm();
    }

    public function rechazar($id_prop){
        $propuesta = PropuestaViaje::updateOrCreate(['id' => $id_prop],[
            'estado_oferta' => 'ACTIVA'
        ]);
        Notification::send($usuario, new RechazarViaje($propuesta));
        $this->cerrarModalConfirm();
    }

    public function abrirModalCreate(){
        $this->modalCreate = true;
    }

    public function cerrarModalCreate(){
        $this->modalCreate = false;
    }

    public function abrirModalUpdate(){
        $this->modalUpdate = true;
    }

    public function cerrarModalUpdate(){
        $this->modalUpdate = false;
    }

    public function abrirModalConfirm(){
        $this->modalConfirm = true;
    }

    public function cerrarModalConfirm(){
        $this->modalConfirm = false;
    }
    
    public function limpiarCampos(){
        $this->origen = '';
        $this->destino = '';
        $this->fecha_viaje = '';
        $this->carga_total = '';
        $this->tipo_viaje = '';
        $this->observacion = '';
    }
}
