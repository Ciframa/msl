<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\SeasonTeam;
use App\Models\Season;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Team::get();
    }
    public function getActiveTeam()
    {
        return SeasonTeam::where('season_id', '=', Season::select('id')->where("isActual", '=', 1)->get()->pluck('id')[0])
         ->join('teams', 'teams.id', '=', 'team_id')
         ->orderBy('team_id')
         ->get();
    }

 public function getTeamsSeasonly()
    {
         return SeasonTeam::select(
         'season_team.id', 'season_team.season_id', 'seasons.year', 'teams.name', 'season_team.team_id' )
        ->join('teams', 'teams.id', '=', 'team_id')
        ->join('seasons', 'seasons.id', '=', 'season_id')
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
        //
    }

    public function teamActiveStore(Request $request)
    {
        $newActiveTeam = new SeasonTeam;
        $newActiveTeam->season_id = $request->team["season_id"];
        $newActiveTeam->team_id = $request->team["id"];
        $newActiveTeam->save();

        return $newActiveTeam;
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
    public function teamActiveDestroy($id)
     {
         $existingItem = SeasonTeam::find($id);
        if($existingItem){
            $existingItem->delete();
            return "Item successfully deleted";
        }

    }

}
