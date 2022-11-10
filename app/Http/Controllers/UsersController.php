<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Users;
use App\Players;
use App\Moves;
use App\Opportunity;
use App\Rewards;
use Webpatser\Uuid\Uuid;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Response;
use Validator;
use Hash;
use DB;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json(Users::with('roles')->get(), 200);
    }

    public function getUsersByRol($id)
    {
        return Response::json(Users::whereRaw('rol=?', $id)->with('roles')->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }
    /* Use it for json_encode some corrupt UTF-8 chars
 * useful for = malformed utf-8 characters possibly incorrectly encoded by json_encode
 */
    function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }
        return $mixed;
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'       => 'required',
            'correo'       => 'required',
            'telefono'     => 'required',
            'autorizacion' => 'required',
            'cajero'       => 'required',
        ]);
        if ($validator->fails()) {
            $returnData = array(
                'status' => 400,
                'msg' => 'Invalid Parameters',
                'validator' => $validator->messages()->toJson()
            );
            return Response::json($returnData, 400);
        } else {
            $authCode = $request->get('autorizacion');
            $atmCode = $request->get('cajero');
            $depto = $request->get('departamento');
            $email = $request->get('correo');
            $email_exists  = Players::whereRaw("email = ?", $email)->count();
            if ($email_exists > 0) {
                $currentPlayer  = Players::whereRaw("email = ?", $email)->first();
                $returnData = $this->createMove($currentPlayer, $authCode, $atmCode, $depto, true);
                return Response::json($returnData, $returnData['status']);
            }
            $newObject = new Players();
            $newObject->name =  $request->get('nombre');
            $newObject->dpi =  $request->get('dpi');
            $newObject->email =  $request->get('correo');
            $newObject->phone =  $request->get('telefono');
            $newObject->save();
            $returnData = $this->createMove($newObject, $authCode, $atmCode, $depto, false);
            return Response::json($returnData, $returnData['status']);
        }
    }
    public function createMove($player, $authCode, $atmCode, $depto, $exist)
    {
        $returnData = array(
            'status' => 404,
            'msg' => 'Move Saved Error.',
            'obj' => null
        );
        $move_exists  = Moves::whereRaw("auth = ? or atm = ?", [$authCode, $atmCode])->count();
        if ($move_exists > 0) {
            $moveObj  = Moves::whereRaw("auth = ? or atm = ?", [$authCode, $atmCode])->first();
            if ($moveObj->player === $player->id) {
                $returnObj = array(
                    'id' => $moveObj->id,
                    'name' => $player->name,
                    'dpi' => $player->dpi,
                    'email' => $player->email,
                    'phone' => $player->phone,
                    'auth' => $authCode,
                    'atm' => $atmCode,
                    'date' => $moveObj->created_at,
                    'points' => $moveObj->points,
                    'winner' => $moveObj->winner
                );
                $returnData = array(
                    'status' => 404,
                    'msg' => 'Move Already Exist.',
                    'obj' => $returnObj
                );
            } else {
                $returnObj = array(
                    'id' => $moveObj->id,
                    'name' => $moveObj->name,
                    'dpi' => $moveObj->dpi,
                    'email' => $moveObj->email,
                    'phone' => $moveObj->phone,
                    'auth' => $authCode,
                    'atm' => $atmCode,
                    'date' => $moveObj->created_at,
                    'points' => $moveObj->points,
                    'winner' => $moveObj->winner
                );
                $returnData = array(
                    'status' => 400,
                    'msg' => 'Move Already Exist with other player.',
                    'obj' => $returnObj
                );
            }
        }

        $moveObj = new Moves();
        $moveObj->auth = $authCode;
        $moveObj->atm = $atmCode;
        $moveObj->file = $atmCode;
        $moveObj->points = 0;
        $moveObj->winner = 0;
        $moveObj->player = $player->id;
        $moveObj->department = $depto;
        $opportunities  = Opportunity::all();
        $reward = null;
        foreach ($opportunities as $key => $value) {
            if ($value->department === $depto || $value->department === null) {
                if ($value->avaliable) {
                    $reward  = Rewards::whereRaw("id = ?", $value->reward)->first();
                    if ($reward->avaliable > 0) {
                        $moveObj->points = $value->points;
                        $moveObj->winner = 1;
                        $moveObj->opportunity = $value->id;
                        $opportunity  = Opportunity::whereRaw("id = ?", $value->id)->first();
                        $opportunity->avaliable = 0;
                        $opportunity->status = 0;
                        $opportunity->save();
                        $reward->avaliable -= 1;
                        if ($reward->avaliable === 0) {
                            $reward->status = 0;
                        }
                        $reward->save();
                        break;
                    }
                }
            }
        }
        $moveObj->save();
        $returnObj = array(
            'id' => $player->id,
            'name' => $player->name,
            'dpi' => $player->dpi,
            'email' => $player->email,
            'phone' => $player->phone,
            'auth' => $authCode,
            'atm' => $atmCode,
            'move_id' => $moveObj->id,
            'points' => $moveObj->points,
            'winner' => $moveObj->winner,
            'date' => $moveObj->created_at,
            'winObj' => $reward,
        );
        $returnData = array(
            'status' => 200,
            'msg' => $exist ? 'Mode Added to user success' : 'Move Created success.',
            'obj' => $returnObj
        );
        return $returnData;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *'password'      => 'required|min:3|regex:/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!-,:-@]).*$/',
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario'      => 'required',
        ]);
        if ($validator->fails()) {
            $returnData = array(
                'status' => 400,
                'msg' => 'Invalid Parameters',
                'validator' => $validator->messages()->toJson()
            );
            return Response::json($returnData, 400);
        } else {
            $encript = new Encripter();
            $objectReques = (object)array(
                "usuario" => ($request->get('usuario')) ? json_decode(mb_convert_encoding($encript->desencript($request->get('usuario')), 'UTF-8', 'UTF-8')) : null,
                "cliente" => ($request->get('cliente')) ? json_decode(mb_convert_encoding($encript->desencript($request->get('cliente')), 'UTF-8', 'UTF-8')) : null,
                "proveedor" => ($request->get('proveedor')) ? json_decode(mb_convert_encoding($encript->desencript($request->get('proveedor')), 'UTF-8', 'UTF-8')) : null
            );
            if (!$encript->getValidSalt()) {
                $returnData = array(
                    'status' => 404,
                    'objeto' => null,
                    'msg' => "Error de seguridad"
                );
                return Response::json($returnData, 200);
            }
            $email = $objectReques->usuario->email;
            $email_exists  = Users::whereRaw("email = ?", $email)->count();
            $user = $objectReques->usuario->username;
            $user_exists  = Users::whereRaw("username = ?", $user)->count();
            if ($email_exists == 0 && $user_exists == 0) {
                DB::beginTransaction();
                $newObject = new Users();
                $newObject->username = $objectReques->usuario->username;
                $newObject->password = Hash::make($objectReques->usuario->password);
                $newObject->email = $email;
                $newObject->nombre = isset($objectReques->proveedor) ? ($objectReques->proveedor->nombre . " " . $objectReques->proveedor->apellido) : (isset($objectReques->cliente) ? $objectReques->cliente->nombre . " " . $objectReques->cliente->apellido : null);
                $newObject->picture = $objectReques->usuario->picture;
                $newObject->rol = $objectReques->proveedor ? 4 : 2;
                $newObject->nacimiento = $objectReques->proveedor ? mb_convert_encoding(base64_decode($objectReques->proveedor->nacimiento), 'UTF-8', 'UTF-8') : null;
                $newObject->google_id = $objectReques->usuario->google_id ? $objectReques->usuario->google_id : null;
                $newObject->facebook_id = $objectReques->usuario->facebook_id ? $objectReques->usuario->facebook_id : null;
                $newObject->google_token = $objectReques->usuario->google_token ? $objectReques->usuario->google_token : null;
                $newObject->google_idToken = $objectReques->usuario->google_idToken ? $objectReques->usuario->google_idToken : null;
                $newObject->estado = $objectReques->usuario->estado;
                $userdata = array(
                    'username'  => $user,
                    'password'  => $objectReques->usuario->password
                );
                $newObject->save();
                if ($objectReques->proveedor && $objectReques->cliente) {
                    $returnData = array(
                        'status' => 405,
                        'objeto' => null,
                        'msg' => "Hubo un error creando su cliente"
                    );
                    DB::rollback();
                    return Response::json($returnData, 200);
                }
                $proveedor = new Proveedores();
                if ($objectReques->proveedor) {
                    $proveedorCount = Proveedores::where('nombre', 'like', '%' . $objectReques->proveedor->nombre . '%')->count();
                    if ($proveedorCount <= 0) {
                        $proveedor->nombre = $objectReques->proveedor->nombre;
                        $proveedor->apellido = $objectReques->proveedor->apellido;
                        $proveedor->foto = $objectReques->usuario->picture;
                        $proveedor->nit = $objectReques->proveedor->nit;
                        $proveedor->token = Uuid::generate()->string;
                        $proveedor->nacimiento = mb_convert_encoding(base64_decode($objectReques->proveedor->nacimiento), 'UTF-8', 'UTF-8');
                        $proveedor->dpi = $objectReques->proveedor->dpi;
                        $proveedor->estado = $objectReques->proveedor->estado;
                        $proveedor->usuario = $newObject->id;
                        $proveedor->save();
                    } else {
                        $returnData = array(
                            'status' => 405,
                            'objeto' => null,
                            'msg' => "El nombre de proveedor ya existe"
                        );
                        DB::rollback();
                        return Response::json($returnData, 200);
                    }
                }
                $cliente = new Clientes();
                if ($objectReques->cliente) {
                    $cliente->nombre = $objectReques->cliente->nombre;
                    $cliente->apellido = $objectReques->cliente->apellido;
                    $cliente->nombre_a_facturar = $objectReques->cliente->nombre_a_facturar;
                    $cliente->nit = $objectReques->cliente->nit;
                    $cliente->dpi = $objectReques->cliente->dpi;
                    $cliente->foto = $objectReques->usuario->picture;
                    $cliente->telefono = $objectReques->cliente->telefono;
                    $cliente->usuario = $newObject->id;
                    $cliente->save();
                }
                $objectSee = Users::whereRaw('id=?', $newObject->id)->with('rol', 'imagenes', 'proveedores', 'clientes', 'empleados', 'direcciones', 'formasPago')->first();
                if ($objectSee) {
                    $token = JWTAuth::attempt($userdata);
                    if ($token) {
                        try {
                            EmailsController::enviarConfirm($objectReques, $objectSee);
                        } catch (Exception $e) {
                            DB::rollback();
                        } finally {
                            $objectSee->last_conection = date('Y-m-d H:i:s');
                            $objectSee->token = ($token);
                            $objectSee->save();
                            DB::commit();
                            $returnData = array(
                                'status' => 200,
                                'objeto' => $objectSee
                            );
                        }
                        return Response::json($returnData, 200);
                    } else {
                        DB::rollback();
                        $returnData = array(
                            'status' => 405,
                            'msg' => 'Token error'
                        );
                        return Response::json($returnData, 404);
                    }
                } else {
                    DB::rollback();
                    $returnData = array(
                        'status' => 404,
                        'msg' => 'Error creando el usuario'
                    );
                    return Response::json($returnData, 404);
                }
            } else {
                if (isset($objectReques->usuario->google) && ($objectReques->usuario->google == "google" || $objectReques->usuario->google == "facebook")) {
                    $objectSee = Users::whereRaw('email=? and google_id=?', [$objectReques->usuario->email, $objectReques->usuario->google_id])->with('rol', 'imagenes', 'proveedores', 'clientes', 'empleados', 'direcciones', 'formasPago')->first();
                    if ($objectSee) {
                        $userdata = array(
                            'username'  => $objectReques->usuario->username,
                            'password'  => $objectReques->usuario->password
                        );
                        DB::beginTransaction();
                        $token = JWTAuth::attempt($userdata);
                        $objectSee->token = $token;
                        if ($token) {
                            $objectSee->google_token = $objectReques->usuario->google_token ? $objectReques->usuario->google_token : '';
                            $objectSee->google_idToken = $objectReques->usuario->google_idToken ? $objectReques->usuario->google_idToken : '';
                            $objectSee->google_id = $objectReques->usuario->google_id ? $objectReques->usuario->google_id : '';
                            $objectSee->picture = $objectReques->usuario->picture ? $objectReques->usuario->picture : '';
                            $objectSee->last_conection = date('Y-m-d H:i:s');
                            $objectSee->token = ($token);
                            $objectSee->save();
                            try {
                                EmailsController::enviarConfirmProvs($objectReques, $objectSee);
                            } catch (Exception $e) {
                                DB::rollback();
                            } finally {
                                DB::commit();
                                $returnData = array(
                                    'status' => 200,
                                    'objeto' => $objectSee
                                );
                            }
                            return Response::json($returnData, 200);
                        } else {
                            $returnData = array(
                                'status' => 404,
                                'msg' => 'Token Error'
                            );
                            DB::rollback();
                            return Response::json($returnData, 404);
                        }
                    } else {
                        $objectSee = Users::whereRaw('email=?', [$objectReques->usuario->email])->count();
                        if ($objectSee > 0) {
                            $returnData = array(
                                'status' => 405,
                                'msg' => 'El correo esta siendo usado en otra cuenta'
                            );
                            return Response::json($returnData, 200);
                        } else {
                            $returnData = array(
                                'status' => 405,
                                'msg' => 'Hubo un error encontrando su usuario'
                            );
                            return Response::json($returnData, 200);
                        }
                    }
                }
                if (isset($objectReques->usuario->id) && $objectReques->usuario->id > 0) {
                    DB::beginTransaction();
                    $proveedor = new Proveedores();
                    if ($objectReques->proveedor) {
                        $proveedorCount = Proveedores::where('nombre', 'like', '%' . $objectReques->proveedor->nombre . '%')->count();
                        if ($proveedorCount <= 0) {
                            $proveedor->nombre = $objectReques->proveedor->nombre;
                            $proveedor->apellido = $objectReques->proveedor->apellido;
                            $proveedor->foto = $objectReques->usuario->picture;
                            $proveedor->nit = $objectReques->proveedor->nit;
                            $proveedor->dpi = $objectReques->proveedor->dpi;
                            $proveedor->token = Uuid::generate()->string;
                            $proveedor->usuario = $objectReques->usuario->id;
                            $proveedor->estado = $objectReques->proveedor->estado;
                            $proveedor->save();
                        } else {
                            $returnData = array(
                                'status' => 405,
                                'objeto' => null,
                                'msg' => "El nombre de proveedor ya existe"
                            );
                            DB::rollback();
                            return Response::json($returnData, 200);
                        }
                    }
                    $objectSee = Users::whereRaw('id=?', $objectReques->usuario->id)->first();
                    $objectSee->rol = 4;
                    $objectSee->save();
                    $objectSee = Users::whereRaw('id=?', $objectReques->usuario->id)->with('rol', 'imagenes', 'proveedores', 'clientes', 'empleados', 'direcciones', 'formasPago')->first();
                    try {
                        EmailsController::enviarConfirmProvs($objectReques, $objectSee);
                    } catch (Exception $e) {
                        DB::rollback();
                    } finally {
                        DB::commit();
                        $returnData = array(
                            'status' => 200,
                            'objeto' => $objectSee
                        );
                    }
                    $returnData = array(
                        'status' => 200,
                        'objeto' => $objectSee
                    );
                    return Response::json($returnData, 200);
                } else {
                    $text = "Ya existe ";
                    if ($email_exists > 0) {
                        $text = $text . "el Email que ingreso";
                    }
                    if ($user_exists > 0) {
                        $text = $text . ($email_exists > 0 ? " y " : "") . "el Usuario que ingreso";
                    }
                    $objectReques = (object)array(
                        "usuario" => ($request->get('usuario')) ? json_decode(mb_convert_encoding($encript->desencript($request->get('usuario')), 'UTF-8', 'UTF-8')) : null,
                        "cliente" => ($request->get('cliente')) ? json_decode(mb_convert_encoding($encript->desencript($request->get('cliente')), 'UTF-8', 'UTF-8')) : null,
                        "proveedor" => ($request->get('proveedor')) ? json_decode(mb_convert_encoding($encript->desencript($request->get('proveedor')), 'UTF-8', 'UTF-8')) : null
                    );
                    $returnData = array(
                        'status' => 400,
                        'msg' => $text,
                        // 'objeto' => $objectReques
                    );

                    return Response::json($returnData, 200);
                }
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
        $objectSee = Users::whereRaw('id=?', $id)->with('rol', 'imagenes', 'proveedores', 'clientes', 'empleados', 'direcciones', 'formasPago')->first();
        if ($objectSee) {
            return Response::json($objectSee, 200);
        } else {
            $returnData = array(
                'status' => 404,
                'msg' => 'No record found'
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
        $objectUpdate = Users::whereRaw('id=?', $id)->first();
        if ($objectUpdate) {
            try {
                $objectUpdate->username = $request->get('username', $objectUpdate->username);
                $objectUpdate->email = $request->get('email', $objectUpdate->email);
                $objectUpdate->nombres = $request->get('nombres', $objectUpdate->nombres);
                $objectUpdate->apellidos = $request->get('apellidos', $objectUpdate->apellidos);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->nacimiento = $request->get('nacimiento', $objectUpdate->nacimiento);
                $objectUpdate->estado = $request->get('estado', $objectUpdate->estado);
                $objectUpdate->foto = $request->get('foto', $objectUpdate->foto);
                $objectUpdate->rol = $request->get('rol', $objectUpdate->rol);
                $objectUpdate->codigo = $request->get('codigo', $objectUpdate->codigo);
                $objectUpdate->save();
                $objectUpdate->roles;

                return Response::json($objectUpdate, 200);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[0] == '01000') {
                    $errorMessage = "Error Constraint";
                } else {
                    $errorMessage = $e->getMessage();
                }
                $returnData = array(
                    'status' => 505,
                    'SQLState' => $e->errorInfo[0],
                    'msg' => $errorMessage
                );
                return Response::json($returnData, 500);
            } catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'msg' => $e->getMessage()
                );
            }
        } else {
            $returnData = array(
                'status' => 404,
                'msg' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }

    public function recoveryPassword(Request $request)
    {
        $objectUpdate = Users::whereRaw('email=? or username=?', [base64_decode($request->get('username')), base64_decode($request->get('username'))])->with('proveedores', 'clientes')->first();
        if ($objectUpdate) {
            try {
                $faker = Faker::create();
                // $pass = $faker->password(8,15,true,true);
                $pass = $faker->regexify('[a-zA-Z0-9-_=+*%@!]{8,15}');
                $objectUpdate->password = Hash::make($pass);
                $objectUpdate->estado = 21;
                $objectUpdate->save();
                $objectUpdate->nombreProveedor = count($objectUpdate->proveedores) > 0 ? $objectUpdate->proveedores[0]->nombre : (count($objectUpdate->clientes) > 0 ? $objectUpdate->clientes[0]->nombre : "INGRESAR NOMBRE");
                $objectUpdate->nombreMostrar = $request->get('nombre') ? base64_decode($request->get('nombre')) : 'Ordenes Online';
                $objectUpdate->empresaMostrar = $request->get('empresa') ? base64_decode($request->get('empresa')) : 'Ordenes Online';
                $objectUpdate->correoMostar = $request->get('correo') ? base64_decode($request->get('correo')) : 'send@ordenes.online';
                $objectUpdate->url = $request->get('url') ? base64_decode($request->get('url')) : 'https://www.ordenes.online/inicio';
                EmailsController::enviarRecovery($objectUpdate, $pass);
                $returnData = array(
                    'status' => 200,
                    'objeto' => $objectUpdate
                );
                return Response::json($returnData, 200);
            } catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'msg' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        } else {
            $returnData = array(
                'status' => 404,
                'msg' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }

    public function changePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_pass' => 'required|min:3',
            'old_pass'      => 'required'
        ]);

        if ($validator->fails()) {
            $returnData = array(
                'status' => 400,
                'msg' => 'Invalid Parameters',
                'validator' => $validator->messages()->toJson()
            );
            return Response::json($returnData, 400);
        } else {
            $old_pass = base64_decode($request->get('old_pass'));
            $new_pass_rep = base64_decode($request->get('new_pass_rep'));
            $new_pass = base64_decode($request->get('new_pass'));
            $objectUpdate = Users::find($id);
            if ($objectUpdate) {
                try {
                    if (Hash::check($old_pass, $objectUpdate->password)) {
                        if ($new_pass_rep != $new_pass) {
                            $returnData = array(
                                'status' => 404,
                                'msg' => 'Passwords do not match'
                            );
                            return Response::json($returnData, 404);
                        }

                        if ($old_pass == $new_pass) {
                            $returnData = array(
                                'status' => 404,
                                'msg' => 'New passwords it is same the old password'
                            );
                            return Response::json($returnData, 404);
                        }
                        $objectUpdate->password = Hash::make($new_pass);
                        $objectUpdate->estado = 1;
                        $objectUpdate->save();

                        return Response::json($objectUpdate, 200);
                    } else {
                        $returnData = array(
                            'status' => 404,
                            'msg' => 'Invalid Password'
                        );
                        return Response::json($returnData, 404);
                    }
                } catch (Exception $e) {
                    $returnData = array(
                        'status' => 500,
                        'msg' => $e->getMessage()
                    );
                }
            } else {
                $returnData = array(
                    'status' => 404,
                    'msg' => 'No record found'
                );
                return Response::json($returnData, 404);
            }
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
        $objectDelete = Users::find($id);
        if ($objectDelete) {
            try {
                Users::destroy($id);
                return Response::json($objectDelete, 200);
            } catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'msg' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        } else {
            $returnData = array(
                'status' => 404,
                'msg' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
}
