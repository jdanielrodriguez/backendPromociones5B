<?php

namespace Ordenes\Http\Controllers;

use Illuminate\Http\Request;
use Ordenes\Http\Requests;
use Ordenes\Imagenes;
use Ordenes\Configuracion;
use Response;
use Validator;
use DB;

class ConfiguracionController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(Configuracion::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$estado)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'estado':{
                    $objectSee = Configuracion::whereRaw('state=?',[$estado])->with('usuario')->get();
                    break;
                }
                case 'tipo':{
                    $objectSee = Configuracion::whereRaw('tipo=? and proveedor=?',[$estado,$id])->with('imagenes')->get();
                    break;
                }
                default:{
                    $objectSee = Configuracion::whereRaw('state=?',[$estado])->with('usuario')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = Configuracion::with('usuario')->get();
        }
    
        if ($objectSee) {
            return Response::json($objectSee, 200);
    
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
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
        $validator = Validator::make($request->all(), [
            'configuracion'          => 'required',
            'proveedor'          => 'required',
        ]);
        if ( $validator->fails() ) {
            $returnData = array (
                'status' => 400,
                'message' => 'Invalid Parameters',
                'validator' => $validator
            );
            return Response::json($returnData, 400);
        }
        else {
            try {
                DB::beginTransaction();
                $encript = new Encripter();
                $objectReques = (object)array(
                    "configuracion"=>($request->get('configuracion'))?json_decode(mb_convert_encoding($encript->desencript($request->get('configuracion')), 'UTF-8', 'UTF-8')):null,
                    "imagenes"=>($request->get('imagenes'))?json_decode(mb_convert_encoding($encript->desencript($request->get('imagenes')), 'UTF-8', 'UTF-8')):[],
                    "proveedor"=>($request->get('proveedor'))?json_decode(mb_convert_encoding($encript->desencript($request->get('proveedor')), 'UTF-8', 'UTF-8')):null
                );
                if(!$encript->getValidSalt()){
                    DB::rollback();
                    $returnData = array(
                        'status' => 404,
                        'objeto' => null,
                        'msg' => "Error de seguridad"
                    );
                    return Response::json($returnData, 200);
                }
                if(isset($objectReques->configuracion)){   
                    $newObject = new Configuracion();
                    if(isset($objectReques->configuracion->id) && $objectReques->configuracion->id>0){
                        $newObject = Configuracion::find($objectReques->configuracion->id);
                    }
                    $newObject->color_nav      = $objectReques->configuracion->color_nav;
                    $newObject->sujeto      = $objectReques->configuracion->sujeto;
                    $newObject->default      = $objectReques->configuracion->default;
                    $newObject->carrousel      = $objectReques->configuracion->carrousel;
                    $newObject->css      = $objectReques->configuracion->css;
                    $newObject->footer      = $objectReques->configuracion->footer;
                    $newObject->opciones      = $objectReques->configuracion->opciones;
                    $newObject->mensaje      = $objectReques->configuracion->mensaje;
                    $newObject->estado      = $objectReques->configuracion->estado;
                    $newObject->proveedor      = $objectReques->configuracion->proveedor;
                    $newObject->tipo      = $objectReques->configuracion->tipo;
                    $newObject->save();
                    if(count($objectReques->imagenes)>0){
                        foreach ($objectReques->imagenes as $key => $value) {
                            $imagen = Imagenes::find($value->id);
                            if($imagen){
                                $imagen->configuracion = $newObject->id;
                                $imagen->save();
                            }
                        }
                    }
                }  
                DB::commit();
                $returnData = array(
                    'status' => 200,
                    'objeto' => $newObject,
                );
                return Response::json($returnData, 200);
    
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'msg' => $e->getMessage()
                );
                DB::rollback();
                return Response::json($returnData, 500);
            }
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $objectSee = Configuracion::find($id);
        if ($objectSee) {
            return Response::json($objectSee, 200);
    
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
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
        $objectUpdate = Configuracion::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->columns = $request->get('columns', $objectUpdate->columns);
                $objectUpdate->save();
                return Response::json($objectUpdate, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
    
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $objectDelete = Configuracion::find($id);
        if ($objectDelete) {
            try {
                Configuracion::destroy($id);
                return Response::json($objectDelete, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
}

