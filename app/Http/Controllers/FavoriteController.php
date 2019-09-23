<?php

namespace App\Http\Controllers;

use App\Appliance;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function favorite($id)
    {
        $appliance = Appliance::findOrFail($id);

        auth()->user()->favorite($appliance);

        return back();
    }
    public function destroy($id)
    {
        $appliance = Appliance::findOrFail($id);

        auth()->user()->unfavorite($appliance);

        return back();
    }
}
