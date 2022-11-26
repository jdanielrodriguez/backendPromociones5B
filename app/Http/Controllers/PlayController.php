<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Players;
use App\ATM;
use App\Moves;
use App\Departaments;
use App\Opportunity;
use App\Rewards;
use Response;
use Validator;

class PlayController extends Controller
{
    public function getDepartments(Request $request)
    {
        $objectSee = Departaments::all();
        if ($objectSee) {
            return Response::json($objectSee, 200);
        } else {
            $returnData = array(
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function play($move_id)
    {
        $moveObj  = Moves::find($move_id);
        if ($moveObj === null) {
            $returnData = array(
                'status' => 404,
                'msg' => 'Move Not Exist',
                'obj' => null
            );
            return Response::json($returnData, 404);
        }
        $winner = 0;
        $reward = null;
        $opportunity = null;
        $player  = Players::whereRaw("id = ?", $moveObj->player)->first();
        if ($moveObj->opportunity !== null) {
            $winner = 1;
            $opportunity  = Opportunity::whereRaw("id = ?", $moveObj->opportunity)->first();
            $reward  = Rewards::whereRaw("id = ?", $opportunity->reward)->first();
        }
        $returnObj = array(
            'id' => $moveObj->id,
            'name' => $player->name,
            'dpi' => $player->dpi,
            'email' => $player->email,
            'move_id' => $moveObj->id,
            'phone' => $player->phone,
            'auth' => $moveObj->auth,
            'atm' => $moveObj->atm,
            'file' => $moveObj->file,
            'date' => $moveObj->created_at,
            'points' => $moveObj->points,
            'winner' => $winner,
            'winObj' => $reward,
            'winOpt' => $opportunity,
        );
        // send Email
        if ($reward->id === 5) {
            $this->createTacoBellReward($opportunity, $reward);
        }
        $returnData = array(
            'status' => 200,
            'msg' => 'Success',
            'obj' => $returnObj
        );
        return Response::json($returnData, 200);
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
            $file = $request->get('file');
            $dpi = $request->get('dpi');
            $telefono = $request->get('telefono');
            $atms  = ATM::whereRaw("department = ? and (atm = ? or modelo_atm = ?)", [$depto, $atmCode, $atmCode])->first();
            if (!$atms) {
                $returnData = array(
                    'status' => 401,
                    'msg' => 'El numero de Cajero (ATM) no Existe.',
                    'obj' => null
                );
                return Response::json($returnData, 401);
            }
            $email_exists  = Players::whereRaw("email = ? or phone = ? or dpi = ?", [$email, $telefono, $dpi]);
            if ($email_exists->count() > 0) {
                $currentPlayer  = $email_exists->first();
                $returnData = $this->createMove($currentPlayer, $authCode, $atms, $depto, true, $file);
                return Response::json($returnData, $returnData['status']);
            }
            $newObject = new Players();
            $newObject->name =  $request->get('nombre');
            $newObject->dpi =  $dpi;
            $newObject->email =  $email;
            $newObject->phone =  $telefono;
            $newObject->save();

            $returnData = $this->createMove($newObject, $authCode, $atms, $depto, false, $file);
            return Response::json($returnData, $returnData['status']);
        }
    }
    public function createMove($player, $authCode, $atmCode, $depto, $exist, $file)
    {
        // esta variable indica si se quiere limitar la entrega de premios a 1 por patrocinador al dia
        $limited = false;
        $returnData = array(
            'status' => 404,
            'msg' => 'Move Saved Error.',
            'obj' => null
        );
        $isValid = $this->validatePlayer($player, $authCode, $atmCode);
        if (!$isValid) {
            $winner = $this->getMyWinner($player);
            $returnObj = array(
                'id' => $player->id,
                'name' => $player->name,
                'dpi' => $player->dpi,
                'email' => $player->email,
                'phone' => $player->phone,
                'auth' => $authCode,
                'atm' => $atmCode->id,
                'move_id' => 0,
                'points' => $winner->points,
                'filePath' => $winner->file,
                'departamento' => $depto,
                'winner' => $winner->winner,
                'date' => $winner->created_at,
                'winObj' => null,
            );
            $returnData = array(
                'status' => 200,
                'msg' => 'User is alright winner.',
                'obj' => $returnObj
            );
            return $returnData;
        }
        $moveObj  = Moves::whereRaw("auth = ? or atm = ?", [$authCode, $atmCode->id]);
        $move_exists  = $moveObj->count();
        if ($move_exists > 0) {
            $moveObj  = $moveObj->first();
            if ($moveObj->player === $player->id) {
                $returnObj = array(
                    'id' => $moveObj->id,
                    'name' => $player->name,
                    'dpi' => $player->dpi,
                    'email' => $player->email,
                    'move_id' => $moveObj->id,
                    'phone' => $player->phone,
                    'auth' => $authCode,
                    'atm' => $atmCode->id,
                    'date' => $moveObj->created_at,
                    'points' => $moveObj->points,
                    'filePath' => $moveObj->file,
                    'departamento' => $depto,
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
                    'move_id' => $moveObj->id,
                    'auth' => $authCode,
                    'atm' => $atmCode->id,
                    'date' => $moveObj->created_at,
                    'points' => $moveObj->points,
                    'filePath' => $moveObj->file,
                    'departamento' => $depto,
                    'winner' => $moveObj->winner,
                );
                $returnData = array(
                    'status' => 400,
                    'msg' => 'Move Already Exist with other player.',
                    'obj' => $returnObj
                );
            }
        }

        $attachment_url = (new ImageRepository)->upload_image($file, 'ruleta/' . $player->dpi);
        $moveObj = new Moves();
        $moveObj->auth = $authCode;
        $moveObj->atm = $atmCode->id;
        $moveObj->file = $attachment_url;
        $moveObj->points = 0;
        $moveObj->winner = 0;
        $moveObj->player = $player->id;
        $moveObj->department = $depto;
        $opportunitiesObj  = $limited ? Opportunity::whereRaw("avaliable = 1 and status = 1")->groupBy('reward')->groupBy('reward') : Opportunity::whereRaw("(avaliable = 1 and status = 1)");
        $opportunities = $opportunitiesObj->get();
        $count = count($opportunities);
        // 4 = 25% posibilidad de ganar, 5 = 20, 10 = 10
        $maxRandon = $count * 10;
        $reward = null;
        srand(time());
        $ganador = false;
        // sorteo Random
        $numero_aleatorio = rand(0, $maxRandon);
        $validateRepechaje = $this->validateRepechaje();
        $dayAvaliable = $this->dayAvaliable();
        foreach ($opportunities as $key => $value) {
            if (!$validateRepechaje && $value->repechaje) {
                continue;
            }
            if ($value->department === $depto || $value->department === null) {
                if ($numero_aleatorio === $key) {
                    $ganador = true;
                }
                // Validacion reward taco bell en departamento totonicapan 21 nunca ganara
                if ($value->reward === 5 && $depto === 21) {
                    $ganador = false;
                    continue;
                }
                if ($value->avaliable && $ganador && $dayAvaliable) {
                    $reward  = Rewards::whereRaw("id = ?", $value->reward)->first();
                    if ($reward->avaliable > 0) {
                        $yetAvaliable = true;
                        //TODO validar si ya se dio una oportunidad de este premio el dia de hoy
                        if ($limited) {
                        }
                        // Validacion reward taco bell crear imagen antes de enviar ganador
                        if ($value->reward === 5) {
                            $yetAvaliable = $this->createTacoBellReward($value, $reward);
                        }
                        if ($yetAvaliable) {
                            $moveObj->points = $value->points;
                            $moveObj->winner = 1;
                            $moveObj->repechaje = 0;
                            $moveObj->opportunity = $value->id;
                            $opportunity  = Opportunity::whereRaw("id = ?", $value->id)->first();
                            if ($validateRepechaje && $opportunity->repechaje) {
                                $moveObj->repechaje = 1;
                            }
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
        }
        $moveObj->save();
        $returnObj = array(
            'id' => $player->id,
            'name' => $player->name,
            'dpi' => $player->dpi,
            'email' => $player->email,
            'phone' => $player->phone,
            'auth' => $authCode,
            'atm' => $atmCode->id,
            'move_id' => $moveObj->id,
            'points' => $moveObj->points,
            'filePath' => $attachment_url,
            'departamento' => $depto,
            'winner' => $moveObj->winner,
            'date' => $moveObj->created_at,
            'winObj' => $reward,
        );
        $returnData = array(
            'status' => 200,
            'msg' => $exist ? 'Mode Added to user success' : 'Move Created success.',
            'obj' => $returnObj,
            // 'count' => $count,
            // 'opportunities' => $opportunities,
            // 'maxRandon' => $maxRandon,
            'random' => $numero_aleatorio
        );
        return $returnData;
    }

    public function validatePlayer($player, $authCode, $atmCode, $limited = true)
    {
        $now = date('Y-m-d H:m:s');
        $moveObj  = $limited ? Moves::whereRaw("player = ? and auth = ? and atm = ? and winner = 1 and MONTH(created_at) = MONTH(?)", [$player->id, $authCode, $atmCode->id, $now]) : Moves::whereRaw("player = ? and winner = 1 and MONTH(created_at) = MONTH(?)", [$player->id, $now]);
        return $moveObj->count() === 0;
    }

    public function validateRepechaje()
    {
        $now = date('Y-m-d H:m:s');
        $moveObj  = Opportunity::whereRaw("repechaje = 1 and avaliable = 0 and DAY(updated_at) = DAY(?)", [$now]);
        return $moveObj->count() === 0;
    }

    public function dayAvaliable()
    {
        $now = date('Y-m-d H:m:s');
        $moveObj  = Opportunity::whereRaw("avaliable = 0 and DAY(updated_at) = DAY(?)", [$now]);
        return $moveObj->count() < 75;
    }

    public function getMyWinner($player)
    {
        $moveObj  = Moves::whereRaw("player = ? and winner = 1", $player->id)->first();
        return $moveObj;
    }

    public function createTacoBellReward($opportunity, $reward)
    {
        try {
            $winObj = $reward;
            $optObj = $opportunity;
            if ($optObj && $winObj) {
                if ($winObj && $optObj && $winObj->use_code) {
                    // $winObj->img = $winObj->img . "cupon_" . $optObj->code . ".png";
                }
            } else {
                return false;
            }
            //Cargamos la primera imagen(cabecera)
            if (file_exists("https://promociones5b.com/backend/public/premios/taco-bell.png")) {
                $logo = ImageCreateFromPng("https://promociones5b.com/backend/public/premios/taco-bell.png");
            } else {
                $logo = ImageCreateFromPng("https://promociones5b.com/backend/public/premios/taco-bell.png");
            }
            $imgBase = new TextToImage;
            $baseimagen = $imgBase->createImageBase();
            //Unimos la primera imagen con la imagen base
            imagecopymerge($baseimagen, $logo, 0, 0, 0, 0, 400, 400, 100);
            //Cargamos la segunda imagen(cuerpo)
            // $ts_viewer = ImageCreateFromPng("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=https://promociones5b.com/dashboard/verificacion.php?codigo=" . $objectSee->codigo);
            //Juntamos la segunda imagen con la imagen base
            //imagecopymerge($baseimagen, $ts_viewer, 110, 50, 0, 0, 300, 300, 100);
            $img = new TextToImage;
            $img->createImage(strtoupper($optObj->code), 17, 150, 60);
            $img->saveAsPng('texto_' . $optObj->code, './premios/textos/');
            $textImg = ImageCreateFromPng("premios/textos/texto_" . $optObj->code . ".png");
            imagecopymerge($baseimagen, $textImg, 250, 305, 0, 0, 150, 55, 100);
            //Mostramos la imagen en el navegador
            ImagePng($baseimagen, "./premios/tacobell/cupon_" . $optObj->code . ".png", 5);
            //Limpiamos la memoria utilizada con las imagenes
            ImageDestroy($logo);
            $img->imageDestroy();
            unlink("./premios/textos/texto_" . $optObj->code . ".png");
            ImageDestroy($baseimagen);
            $url = "http://localhost/premios/tacobell/cupon_" . $optObj->code . ".png";
            // echo $url;
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}
