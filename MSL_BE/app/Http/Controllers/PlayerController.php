<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Stat;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Player::select('players.id','players.name AS player_name', 'players.number', 'players.isActive','team_id', 'teams.name AS team_name')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('teams.id')
            ->orderBy('player_name')
            ->get();
    }

 public function getActivePlayers()
    {
        return Player::select('players.id','players.name AS player_name', 'players.number', 'players.isActive','team_id', 'teams.name AS team_name')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->where('players.isActive', '=' , '1')
            ->orderBy('teams.id')
            ->orderBy('player_name')
            ->get();
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
        $newPlayer = new Player;
        $newPlayer->team_id = $request->data["teamId"];
        $newPlayer->save();

        return $newPlayer;
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
        $existingItem = Player::find($id);

        if($existingItem){
            $existingItem->name = $request->player["player_name"];
            $existingItem->number = $request->player["number"];
            $existingItem->isActive = $request->player["isActive"];
            $existingItem->save();
            return $existingItem;
        }
        return "Player not found";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $existingItem = Player::find($id);
        
        if($existingItem){
            $existingItem->delete();
            return "Item successfully deleted";
        }

    }
}
