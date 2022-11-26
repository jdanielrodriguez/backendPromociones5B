<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Moves;
use Response;

class MovesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrayMoves = Moves::with(['players', 'winObj', 'departamento']);
        $returnData = array(
            'status' => 200,
            'message' => 'Todos los registros.',
            'count' => $arrayMoves->count(),
            'moves' => $arrayMoves->get()
        );
        return Response::json($returnData, 200);
    }

    public function winners()
    {
        $arrayMoves = Moves::whereRaw('winner=1')->with(['players', 'winObj', 'departamento']);
        $returnData = array(
            'status' => 200,
            'message' => 'Todos los registros Ganadores.',
            'count' => $arrayMoves->count(),
            'moves' => $arrayMoves->get()
        );
        return Response::json($returnData, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
