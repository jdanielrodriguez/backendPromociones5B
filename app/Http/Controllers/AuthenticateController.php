<?php

namespace Ordenes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Ordenes\Http\Requests;
use Illuminate\Support\Facades\Auth;	
use Tymon\JWTAuth\Facades\JWTAuth;
use Ordenes\Users;
use Ordenes\Imagenes;
use Response;
use Validator;
use Storage;

class AuthenticateController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'password'  => 'required'
        ]);
        if ( $validator->fails() ) {
            $returnData = array (
                'status' => 400,
                'msg' => 'Invalid Parameters',
                'validator' => $validator
            );
            return Response::json($returnData, 400);
        }
        else {
            try {
                $validator = Validator::make($request->all(), [
                    'username'  => 'email',
                ]);
                $userdata = array();
                $encript = new Encripter();
                if ( $validator->fails() ) {
                    $userdata = array(
                        'username'  => $request->get('username'),
                        'password'  => $encript->desencript($request->get('password'))
                    );
                }else{
                    // $field = (preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $request->get('username'), null)) ? 'email' : 'username';
                    $userdata = array(
                        'email'  => $request->get('username'),
                        'password'  => $encript->desencript($request->get('password'))
                    );
                }
                if(!$encript->getValidSalt()){
                    $returnData = array(
                        'status' => 404,
                        'objeto' => null,
                        'msg' => "Error de seguridad"
                    );
                    return Response::json($returnData, 200);
                }
                $token = JWTAuth::attempt($userdata);
                if($token) {
                    $user = Users::find(Auth::user()->id);
                    $user->last_conection = date('Y-m-d H:i:s');
                    $user->token = ($token);
                    $user->google_token=$request->get('google_token');
                    $user->google_idToken=$request->get('google_idToken');
                    $user->google_id=$request->get('google_id');
                    $user->picture=$request->get('picture');
                    $user->save();
                    $user = Users::with('rol','imagenes','proveedores','clientes','empleados','direcciones','formasPago')->find($user->id);
                    return Response::json($user, 200);
                }
                else {
                    if($request->get('google_id') !== null){
                        $user = Users::whereRaw('email=? and (google_id=? OR facebook_id=?)',[$request->get('email'),$request->get('google_id'),$request->get('google_id')])->first();
                        if($user){
                            $user->password = Hash::make($request->get('google_id'));
                            $user->save();
                            $userdata = array(
                                'username'  => $request->get('username'),
                                'password'  => $request->get('google_id')
                            );
                            $token = JWTAuth::attempt($userdata);
                            if($token){
                                $user = Users::find(Auth::user()->id);
                                $user->last_conection = date('Y-m-d H:i:s');
                                $user->token = ($token);
                                $user->google_token=$request->get('google_token');
                                $user->google_idToken=$request->get('google_idToken');
                                $user->google_id=$request->get('google_id');
                                $user->picture=$request->get('picture');
                                $user->save();
                                $user = Users::with('rol','imagenes','proveedores','clientes','empleados','direcciones','formasPago')->find($user->id);
                                return Response::json($user, 200);
                            }else{
                                $returnData = array (
                                    'status' => 401,
                                    'msg' => 'Token error.'
                                );
                                return Response::json($returnData, 401);
                            }
                        }else{
                            $returnData = array (
                                'status' => 401,
                                'msg' => 'Usuario no encontrado.'
                            );
                            return Response::json($returnData, 401);
                        }
                    }else{
                        $returnData = array (
                            'status' => 404,
                            'msg' => 'Debe iniciar sesion con su red social.'
                        );
                        return Response::json($returnData, 404);
                    }
                    $returnData = array (
                        'status' => 401,
                        'msg' => 'No valid Username or Password'
                    );
                    return Response::json($returnData, 401);
                }
                return Response::json($newObject, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'msg' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        
    }

    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar'      => 'required|image|mimes:jpeg,png,jpg',
            'carpeta'      => 'required'
        ]);
    
        if ($validator->fails()) {
            $returnData = array(
                'status' => 400,
                'msg' => 'Invalid Parameters',
                'validator' => $validator->messages()->toJson()
            );
            return Response::json($returnData, 400);
        }
        else {
            try {
                $path = Storage::disk('s3')->put($request->carpeta, $request->avatar);
                $url = Storage::disk('s3')->url($path);
                $newObject = new Imagenes();
                $newObject->url            = $url;
                $newObject->path           = $path;
                $newObject->orden          = 1;
                $newObject->save();
                $returnData = array(
                    'status' => 200,
                    'msg' => 'Picture Upload Success',
                    'picture' => $url,
                    'objeto' => $newObject
                );
                return Response::json($returnData, 200);
            }
            catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'msg' => 'Invalid Parameters',
                    'error' => $e
                );
                return Response::json($returnData, 500);
            }
    
        }
    }

    public function borrarAvatar($id) {
        $objectSee = Imagenes::find($id);
        if ($objectSee) {
            $path = Storage::disk('s3')->delete($objectSee->url);
            Imagenes::destroy($id);
            $returnData = array(
                'status' => 200,
                'objeto' => $objectSee,
                'msg' => 'Imagen eliminada',
            );
            return Response::json($returnData, 200);
        
        }
    }

    public function updateAvatar($id, Request $request) {
        $objectUpdate = Imagenes::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->cliente = $request->get('cliente', $objectUpdate->cliente);
                $objectUpdate->proveedor = $request->get('proveedor', $objectUpdate->proveedor);
                $objectUpdate->usuario = $request->get('usuario', $objectUpdate->usuario);
                $objectUpdate->producto = $request->get('producto', $objectUpdate->producto);
                $objectUpdate->inventario = $request->get('inventario', $objectUpdate->inventario);
                $objectUpdate->item = $request->get('item', $objectUpdate->item);
                $objectUpdate->tipo_direccion = $request->get('tipo_direccion', $objectUpdate->tipo_direccion);
                $objectUpdate->tipo_item = $request->get('tipo_item', $objectUpdate->tipo_item);
                $objectUpdate->save();
                return Response::json($objectUpdate, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'msg' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array (
                'status' => 404,
                'msg' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
            return response([
            'status' => 'success',
            'msg' => 'You have successfully logged out.'
        ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response([
                'status' => 'error',
                'msg' => 'Failed to logout, please try again.'
            ]);
        }
    }
    public function validarCaptcha(Request $request){
        $data = http_build_query(array(
            'secret' => "",
            'response' => base64_decode($request->get('token'))
          ));
          $curl = curl_init();
          $captcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
          curl_setopt($curl, CURLOPT_URL,$captcha_verify_url);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $captcha_output = curl_exec ($curl);
          curl_close ($curl);
          $decoded_captcha = json_decode($captcha_output);
          $returnData = array (
            'status' => 200,
            'objeto' => $decoded_captcha
        );
        return Response::json($returnData, 200);
    }
    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }
}
