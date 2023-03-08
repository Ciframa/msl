<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalRanks;

class FinalRanksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FinalRanks::select(
            "teams.id",
            "teams.image_path",
            "seasons.id",
            "seasons.year",
            "final_ranks.id",
            "final_ranks.team_id",
            "final_ranks.season_id"
        )
            ->leftJoin('seasons', 'final_ranks.season_id', '=', 'seasons.id')
            ->leftJoin('teams', 'final_ranks.team_id', '=', 'teams.id')
            ->orderBy('final_ranks.season_id')
            ->orderBy('final_ranks.id')
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
        $newItem = new FinalRanks();

        $newItem->season_id = $request->data["seasonId"];
        $newItem->team_id = $request->data["teamId"];
        $newItem->save();

        return $newItem;
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
        $existingItem = FinalRanks::find($id);

        if ($existingItem) {
            $existingItem->delete();
            return "Item successfully deleted";
        }
    }
}
