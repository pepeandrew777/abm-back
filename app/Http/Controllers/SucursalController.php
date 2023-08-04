<?php

namespace App\Http\Controllers;
use App\Models\AbmSucursal;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;

class SucursalController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sucursal = AbmSucursal::orderBy('v_descripcion')
                                 ->get();        
        return $this->showAll($sucursal);
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
        $sucursal = new AbmSucursal();
        $sucursal->v_descripcion = $request->descripcion;        
        $sucursal->save();
        return $this->showMessage("Se guardó el registro de la sucursal correctamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AbmSucursal  $abmSucursal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sucursal = AbmSucursal::findOrFail($id);
        return  $this->showOne($sucursal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbmSucursal  $abmSucursal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sucursal = AbmSucursal::findOrFail($id);
        $sucursal->v_descripcion = $request->descripcion;        
        $sucursal->save();
        return $this->showMessage("Se actualizó el registro de la sucursal correctamente");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AbmSucursal  $abmSucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sucursal = AbmSucursal::findOrFail($id);
        $sucursal->delete();
        return $this->showMessage("Se elimino el registro de la sucursal correctamente");
    }
}
