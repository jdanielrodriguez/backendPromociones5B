<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Moves;
use App\Correos;
use App\Opportunity;
use App\Rewards;
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
        $returnData = array(
            'status' => 200,
            'message' => 'Todos los registros.',
            'count' => Moves::count(),
            'moves' => Moves::get()
        );
        return Response::json($returnData, 200);
    }

    public function moverCount()
    {
        $returnData = array(
            'status' => 200,
            'message' => 'Conteo de todos los registros.',
            'count' => Moves::count()
        );
        return Response::json($returnData, 200);
    }

    public function moverDetail()
    {
        $arrayMoves = Moves::with(['players', 'winObj', 'departamento']);
        $returnData = array(
            'status' => 200,
            'message' => 'Todos los registros con Detalles.',
            'count' => $arrayMoves->count(),
            'moves' => $arrayMoves->get()
        );
        return Response::json($returnData, 200);
    }

    public function forbidden()
    {
        $arrayMoves = Moves::whereRaw('department = 21')->with(['players', 'winObj', 'departamento']);
        $newArrayMoves = $arrayMoves->get();
        $auxArrayMoves = [];
        foreach ($newArrayMoves as $key => $value) {
            $rewardId = Opportunity::whereRaw('reward = 5 and id = ?',[$value['opportunity']])->first();
            if($rewardId){
                $auxArrayMoves[] = $value;
            }
        }
        $returnData = array(
            'status' => 200,
            'message' => 'Todos los registros con Detalles.',
            'count' => count($auxArrayMoves),
            'moves' => $auxArrayMoves
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

    public function winnersByReward()
    {
        $arrayMoves = Opportunity::selectRaw('reward,count(*) total')->whereRaw('avaliable=0')->groupBy('reward')->with('premio')->get();
        $total = 0;
        foreach ($arrayMoves as $value) {
            $total += $value->total;
        }
        $avaliable = 0;
        foreach ($arrayMoves as $value) {
            $avaliable += $value->premio->avaliable;
        }
        $returnData = array(
            'status' => 200,
            'message' => 'Todos los registros Ganadores.',
            'count' => $total,
            'avaliable' => $avaliable,
            'total' => $total + $avaliable,
            'moves' => $arrayMoves
        );
        return Response::json($returnData, 200);
    }

    public function sendRegards()
    {
        $moves = Moves::whereRaw('winner = 0')->with(['players', 'atms'])->get();
        $losers = [];
        $winns = [];
        $playerController = new PlayController();
        $count = 0;
        foreach ($moves as $value) {
            $isWin = false;
            $movesWinners = Moves::whereRaw('winner = 1 and player = ?', [$value->players->id])->count();
            if($movesWinners > 0){
                $isWin = true;
            }
            if(!$isWin){
                $winns[] = $win = $playerController->createMove(json_decode($value->players, true), $value->authCode, json_decode($value->atms, true), $value->depto, true, $value->file);
                $value->players['winObj'] = $win;
                $losers[] = $value->players;
                $count++;
            }
            if($count === 100){
                break;
            }
        }
        
        $returnData = array(
            'status' => 200,
            'message' => 'Todos los juagadores perdedores.',
            'players' => $losers
        );
        return Response::json($returnData, 200);
    }

    public function sendEmails() {
        $moves = Moves::whereRaw('winner = 1')->with(['players', 'atms'])->get();
        // send Email
        foreach ($moves as $move) {
            $player = $move->players;
            $moveObj = $move;
            $emailExist = Correos::whereRaw("player = ? and move = ?", [$player->id, $move->id])->count();
            if ($move->winner === 1 && $emailExist === 0) {
                if ($moveObj->opportunity !== null) {
                    $opportunity  = Opportunity::whereRaw("id = ?", $moveObj->opportunity)->first();
                    $reward  = Rewards::whereRaw("id = ?", $opportunity->reward)->first();
                }
                try {
                    $returnObj = array(
                        'id' => $player->id,
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
                        'winner' => $moveObj->winner,
                        'winObj' => $reward,
                        'winOpt' => $opportunity,
                    );
                    EmailsController::enviarReward($returnObj);
                    $correo = new Correos();
                    $winObj = $reward;
                    $optObj = $opportunity;
                    $winImg = '';
                    if ($optObj && $winObj) {
                        if ($winObj && $optObj && $winObj->use_code) {
                            $winImg = $winObj->img . "cupon_" . $optObj->code . ".png";
                        } else if ($winObj && $optObj && !$winObj->use_code) {
                            $winImg = $winObj->img;
                        }
                    }
                    $correo->move = $moveObj->id;
                    $correo->player = $player->id;
                    $correo->adjunto = $winImg;
                    $correo->correo = $player->email;
                    $correo->estado = 1;
                    $correo->whatsapp = 1;
                    $correo->recibido = date('Y-m-d H:m:s');
                    $correo->save();
                } catch (Exception $e) {
                    $returnData = array(
                        'status' => 500,
                        'msg' => 'error',
                        'obj' => $e
                    );
                    return Response::json($returnData, 500);
                }
            }
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
