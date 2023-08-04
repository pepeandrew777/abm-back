<?php
namespace App\Http\Controllers;
use App\Models\AbmAplicacion;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
class AplicacionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aplicacion = AbmAplicacion::orderBy('n_id')
                                   ->get();        
        return $this->showAll($aplicacion);
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
            'descripcion' => 'required',            
        ]);
        if ($validator->fails())
        {
            return $this->showMessage($validator->messages(), 400);
        }        
        $aplicacion = new AbmAplicacion();
        $aplicacion->v_descripcion = $request->descripcion;        
        $aplicacion->save();
        return $this->showMessage("Se guardó el registro de la aplicacion correctamente");
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AbmAplicacion  $abmAplicacion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aplicacion = AbmAplicacion::findOrFail($id);
        return  $this->showOne($aplicacion);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbmAplicacion  $abmAplicacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $aplicacion = AbmAplicacion::findOrFail($id);
        $aplicacion->v_descripcion = $request->descripcion;        
        $aplicacion->save();
        return $this->showMessage("Se actualizó el registro de la aplicacion correctamente");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AbmAplicacion  $abmAplicacion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aplicacion = AbmAplicacion::findOrFail($id);
        $aplicacion->delete();
        return $this->showMessage("Se elimino el registro de la aplicacion correctamente");
    }
}
