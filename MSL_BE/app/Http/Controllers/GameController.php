<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePlayerStat;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Game::select(
            'games.id','games.time', 'teams.name','teams1.name as team_home_name','teams2.name as referee_name', 'referee', 'season_id',
             'teams.name as team_away_name', 'team_home_score', 'team_away_score', 'team_away_id', 'team_home_id', 'isFinal', 'teams.image_path as image_path_away', 
             'teams1.image_path as image_path_home',
            'seasons.isActual', 'seasons.year')
            ->leftJoin('seasons', 'games.season_id', '=', 'seasons.id')
            ->leftJoin('teams', 'games.team_away_id', '=', 'teams.id')
            ->leftJoin('teams as teams1', 'games.team_home_id', '=', 'teams1.id')
            ->leftJoin('teams as teams2', 'games.referee', '=', 'teams2.id')
            ->orderBy('seasons.year')
            ->orderBy('games.time')
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
        $newGame = new Game;
        $newGame->isFinal = $request->game["isFinal"];
        $newGame->season_id = $request->game["seasonId"];
        $newGame->save();

        return $newGame;
    }

    public function getStats()
    {
        return GamePlayerStat::select(
                 'player_stats.id', 'player_stats.hr_count', 'player_stats.run_count', 'player_stats.player_id', 'players.name', 'player_stats.game_id', 'players.team_id','teams.name as team_name')
            ->leftJoin('players', 'player_stats.player_id', '=', 'players.id')
            ->leftJoin('teams', 'players.team_id', '=', 'teams.id')
            ->orderBy('game_id')
            ->orderBy('team_name')
            ->orderBy('players.name')
            ->get();
    }

public function getStatsCounts()
    {
        return GamePlayerStat::select(
           'players.name', 'games.season_id')
            ->selectRaw('SUM(hr_count) as hr_count, SUM(run_count) as run_count')
            ->join('players', 'player_id', '=', 'players.id')
            ->join('games', 'game_id', '=', 'games.id')
            ->groupby('games.season_id', 'players.name')
            ->orderBy('season_id', 'asc')
            ->orderBy('run_count', 'desc')
            ->get();
    }

public function storeStat(Request $request)
    {
        $newStat = new GamePlayerStat;
        $newStat->game_id = $request->element["game_id"];
        $newStat->player_id = $request->element["player_id"];
        $newStat->hr_count = $request->element["hr_count"];
        $newStat->run_count = $request->element["run_count"];
        $newStat->save();

        return $newStat;
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
    public function updateFinalDate(Request $request)
    
    {
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
        $existingItem = Game::find($id);

        if($existingItem){
            $existingItem->team_home_score = $request->game["teamHomeScore"];
            $existingItem->team_away_score = $request->game["teamAwayScore"];
            $existingItem->team_home_id = $request->game["teamHomeId"];
            $existingItem->team_away_id = $request->game["teamAwayId"];
            $existingItem->referee = $request->game["refereeId"];
            $existingItem->time = $request->game["dateInput"] . ' ' . $request->game["time"] ;
            
            $existingItem->save();
            return $existingItem;
        }
        return "Game not found";

    }
    public function updateStat(Request $request, $id)
    {
        $existingItem = GamePlayerStat::find($id);

        if($existingItem){
            $existingItem->player_id = $request->element["player_id"];
            $existingItem->hr_count = $request->element["hr_count"];
            $existingItem->run_count = $request->element["run_count"];
            $existingItem->save();
            return $existingItem;
        }
        return "Game not found";

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingItem = Game::find($id);
        
        if($existingItem){
            $existingItem->delete();
            return "Item successfully deleted";
        }

    } 
    public function destroyStat($id)
    {
        $existingItem = GamePlayerStat::find($id);
        
        if($existingItem){
            $existingItem->delete();
            return "Item successfully deleted";
        }

    }
}
