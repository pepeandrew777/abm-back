<?php

namespace App\Http\Controllers;

use App\Models\AbmArea;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $area = AbmArea::orderBy('v_descripcion')
                                   ->get();        
        return $this->showAll($area);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AbmArea  $abmArea
     * @return \Illuminate\Http\Response
     */
    public function show(AbmArea $abmArea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbmArea  $abmArea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AbmArea $abmArea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AbmArea  $abmArea
     * @return \Illuminate\Http\Response
     */
    public function destroy(AbmArea $abmArea)
    {
        //
    }
}
