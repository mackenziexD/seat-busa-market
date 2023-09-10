<?php

namespace Helious\SeatBusaHr\Http\Controllers\Character;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

use Seat\Eveapi\Models\Assets\CorporationAsset;

class MarketController extends CorporationAsset
{
    /**
     * Pulls the corp assets for corp id 170892597(CRICE Corporation) and lists all the items in the CorpSAG7 hangar.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $corpAssets = CorporationAsset::where('corporation_id', 170892597)
            ->where('location_flag', 'CorpSAG7')
            ->with('type')
        ->get();

        dd($corpAssets);
    }


}
