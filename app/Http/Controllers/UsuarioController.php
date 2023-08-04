<?php

namespace App\Http\Controllers;

use App\Models\AbmAplicacionUsuario;
use App\Models\AbmEjecucion;
use App\Models\AbmUsuario;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;


class UsuarioController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $usuarios = AbmUsuario::with('solicitud','area','sucursal')
                               ->orderBy('v_nombres')
                               ->get();        
        return $this->showAll($usuarios);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'paterno' => 'required',
            'nombres' => 'required',            
            'ci' => 'required|numeric',
            'cargo'=>'required',
            'departamento'=>'required',
            'division'=>'required',
            'n_id_solicitud'=>'required|integer',
            'firma_solicitante'=>'required',
            'gerente'=>'required',
            'firma_gerente'=>'required|integer',
            'eventual'=>'required|integer',       
            'n_id_area'=>'required|integer',
            'n_id_sucursal'=>'required|integer',
            'aplicaciones'=>'array|min:1',
        ]);
        if ($validator->fails())
        {
            return $this->showMessage($validator->messages(), 400);
        }        
        $usuario = new AbmUsuario();
        $usuario->v_paterno = $request->paterno;
        $usuario->v_materno = $request->materno ? $request->materno:null;
        $usuario->v_nombres = $request->nombres;
        $usuario->n_ci = $request->ci;
        $usuario->n_item = $request->item ? $request->item:null;
        $usuario->v_cargo = $request->cargo;
        $usuario->v_departamento = $request->departamento;
        $usuario->v_division = $request->division;
        $usuario->n_eventual = $request->eventual;
        $usuario->d_inicio_contrato = $request->fecha_inicio ? $request->fecha_inicio:null;
        $usuario->d_fin_contrato = $request->fecha_final ? $request->fecha_final:null;
        $usuario->n_id_sucursal = $request->n_id_sucursal;
        $usuario->v_telefonos = $request->telefonos ? $request->telefonos:null;
        $usuario->n_interno = $request->interno ? $request->interno:null;
        $usuario->n_id_solicitud = $request->n_id_solicitud;        
                
        $usuario->v_perfil_papel_rol = $request->rol ? $request->rol:null;
        $usuario->v_modulo_programa_carpeta = $request->modulo ? $request->modulo:null;
        $usuario->v_funcionalidad_permisos = $request->funcionalidad ? $request->funcionalidad:null;
        $usuario->v_observacion = $request->obs ? $request->obs:null; 

        $usuario->n_firma_solicitante = $request->firma_solicitante;
        $usuario->n_firma_gerente = $request->firma_gerente;
        $usuario->v_nombre_gerente = $request->gerente;
        $usuario->d_fecha = date("Y-m-d");
        $usuario->d_fecha_hora_recepcion = null; 
        $usuario->n_numero_mesa_ayuda = null; 
        $usuario->v_nombre_recibio = null; 
        $usuario->n_firma_recibio = null; 
        $usuario->v_observacion_recibio = null;
        $usuario->v_observacion_ejecucion = $request->obs_ejecucion ? $request->obs_ejecucion:null;                      
        $usuario->save();
        //guardando datos de aplicaciones                                            
        foreach($request->aplicaciones as $valor) {
            $aplicacion = new AbmAplicacionUsuario();
            $aplicacion->n_id_aplicacion = $valor['n_id'];                
            $aplicacion->n_id_usuario = $usuario->n_id;   
            $aplicacion->save();               
        }
        return $this->showMessage("Se guardó el registro del usuario correctamente");         
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AbmUsuario  $abmUsuario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = AbmUsuario::with('solicitud','area','sucursal','aplicacionesUsuario.aplicacion','fechasEjecucion')
                            ->findOrFail($id);
        return  $this->showOne($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbmUsuario  $abmUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)    
    {
        $validator = Validator::make($request->all(),[
            'paterno' => 'required',
            'nombres' => 'required',            
            'ci' => 'required|numeric',
            'cargo'=>'required',
            'departamento'=>'required',
            'division'=>'required',
            'n_id_solicitud'=>'required|integer',
            'firma_solicitante'=>'required',
            'gerente'=>'required',
            'firma_gerente'=>'required|integer',
            'eventual'=>'required|integer',       
            'n_id_area'=>'required|integer',
            'n_id_sucursal'=>'required|integer',
            'aplicaciones'=>'array|min:1',
            'ejecuciones'=>'array'
        ]);
        if ($validator->fails())
        {
            return $this->showMessage($validator->messages(), 400);
        }                            
        $usuario = AbmUsuario::findOrFail($id);

        $usuario->v_paterno = $request->paterno;
        $usuario->v_materno = $request->materno ? $request->materno:null;
        $usuario->v_nombres = $request->nombres;
        $usuario->n_ci = $request->ci;
        $usuario->n_item = $request->item ? $request->item:null;
        $usuario->v_cargo = $request->cargo;
        $usuario->v_departamento = $request->departamento;
        $usuario->v_division = $request->division;
        $usuario->n_eventual = $request->eventual;
        $usuario->d_inicio_contrato = $request->fecha_inicio ? $request->fecha_inicio:null;
        $usuario->d_fin_contrato = $request->fecha_final ? $request->fecha_final:null;
        $usuario->n_id_sucursal = $request->n_id_sucursal;
        $usuario->v_telefonos = $request->telefonos ? $request->telefonos:null;
        $usuario->n_interno = $request->interno ? $request->interno:null;
        $usuario->n_id_solicitud = $request->n_id_solicitud;        
                
        $usuario->v_perfil_papel_rol = $request->rol ? $request->rol:null;
        $usuario->v_modulo_programa_carpeta = $request->modulo ? $request->modulo:null;
        $usuario->v_funcionalidad_permisos = $request->funcionalidad ? $request->funcionalidad:null;
        $usuario->v_observacion = $request->obs ? $request->obs:null; 

        $usuario->n_firma_solicitante = $request->firma_solicitante;
        $usuario->n_firma_gerente = $request->firma_gerente;
        $usuario->v_nombre_gerente = $request->gerente;

        //Ultima parte a modificar        
     
         $usuario->d_fecha_hora_recepcion = $request->fecha_hora_recepcion ? $request->fecha_hora_recepcion:null; 
         $usuario->n_numero_mesa_ayuda = $request->nro_mesa_ayuda ? $request->nro_mesa_ayuda:null; 
         $usuario->v_nombre_recibio = $request->recibio ? $request->recibio:null; 
         $usuario->n_firma_recibio = $request->firma_recibio ? $request->firma_recibio:null; 
         $usuario->v_observacion_recibio = $request->obs_recibio ? $request->obs_recibio:null;
 
         $usuario->v_observacion_ejecucion = $request->obs_ejecucion ? $request->obs_ejecucion:null;

         $usuario->d_fecha = $request->fecha;
                       
         $usuario->save();
 
         //guardando datos de aplicaciones                                            
          foreach($request->aplicaciones as $valor) {
                 $aplicacion = new AbmAplicacionUsuario();
                 $aplicacion->n_id_aplicacion = $valor['n_id'];                
                 $aplicacion->n_id_usuario = $usuario->n_id;   
                 $aplicacion->save();               
          }
          //guardando datos de ejecuciones
          if(count($request->ejecuciones) > 0)
          {                          
            //guardando los datos que se seleccionaron de las aplicaciones 
              foreach($request->ejecuciones as $valor) {
                  $ejecucion = new AbmEjecucion();
                  $ejecucion->d_fecha_ejecucion = $valor['fecha_ejecucion'];                
                  $ejecucion->v_nombre = $valor['nombre'];
                  $ejecucion->n_nro_asignacion = $valor['numero'];
                  $ejecucion->n_id_usuario = $usuario->n_id;   
                  $ejecucion->save();                                                   
            }
          }          
         return $this->showMessage("Se guardó el registro el abm del usuario correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AbmUsuario  $abmUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = AbmUsuario::findOrFail($id);
        $usuario->delete();
        return $this->showMessage("Se elimino el registro del abm del usuario correctamente");
    }
}
