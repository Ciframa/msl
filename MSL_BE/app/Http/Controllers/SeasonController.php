<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Season::select(
         'HR_master as HR_master_id', 'MVP as MVP_id','players1.name as HR_master', 'players2.name as MVP', 'year', 'isActual' ,'seasons.id')
        ->leftJoin('players as players1', 'players1.id', '=', 'HR_Master')
        ->leftJoin('players as players2', 'players2.id', '=', 'MVP')
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
        $newSeason = new Season;
        $newSeason->year = $request->data["seasonYear"];
        $newSeason->save();

        return $newSeason;
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
        $existingItem = Season::find($id);

        if($existingItem){
            $existingItem->MVP = $request->season["MVP_id"];
            $existingItem->HR_master = $request->season["HR_master_id"];
            $existingItem->save();
            return $existingItem;
        }
        return "Game not found";

    }
public function updateActive(Request $request, $id)
    {
        Season::query()->update(['isActual' => 0]);

        $existingItem = Season::find($id);

        if($existingItem){
            $existingItem->isActual = 1;
            $existingItem->save();
            return $existingItem;
        }
        return "Season not found";
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
