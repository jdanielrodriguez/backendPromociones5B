<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Players;
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
        if($moveObj->opportunity !== null) {
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
            'date' => $moveObj->created_at,
            'points' => $moveObj->points,
            'winner' => $winner,
            'winObj' => $reward,
            'winOpt' => $opportunity,
        );
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
                    'move_id' => $moveObj->id,
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
                    'move_id' => $moveObj->id,
                    'auth' => $authCode,
                    'atm' => $atmCode,
                    'date' => $moveObj->created_at,
                    'points' => $moveObj->points,
                    'winner' => $moveObj->winner,
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
}
