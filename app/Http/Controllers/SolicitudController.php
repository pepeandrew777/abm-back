<?php

namespace App\Http\Controllers;
use App\Models\AbmSolicitud;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class SolicitudController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $solicitud = AbmSolicitud::orderBy('v_descripcion')
                                 ->get();        
        return $this->showAll($solicitud);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AbmSolicitud  $abmSolicitud
     * @return \Illuminate\Http\Response
     */
    public function show(AbmSolicitud $abmSolicitud)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbmSolicitud  $abmSolicitud
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AbmSolicitud $abmSolicitud)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AbmSolicitud  $abmSolicitud
     * @return \Illuminate\Http\Response
     */
    public function destroy(AbmSolicitud $abmSolicitud)
    {
        //
    }
}
